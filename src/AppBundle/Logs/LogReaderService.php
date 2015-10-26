<?php

namespace AppBundle\Logs;

use Symfony\Component\Finder\Finder;

use AppBundle\Entity\FileInfo;
use AppBundle\Entity\FileLine;
use \Doctrine\ORM\Query;

class LogReaderService
{
    protected $path;
    protected $em;
    protected $parser;

    public function __construct($em, $path, $parser)
    {
        $this->em   = $em;
        $this->path = $path;
        $this->parser = $parser;
    }

    public function getQbForPagination($search = null, $nameFilters = array(), $timeFilters = array())
    {
        $storedFiles = $this->getFilesInfoByFilters($nameFilters);

        $queryBuilders = [];
        foreach ($storedFiles as $fileInfo) {
            $queryBuilders[$fileInfo->getFilePath()] = $this->getRowQbByFilters($fileInfo, $search, $timeFilters);
        }

        return $queryBuilders;
    }

    public function actualizeLogsInDb()
    {
        $dataToUpdate = $this->getFilesToUpdate();
        $this->updateFileInfoDb($dataToUpdate);
    }

    private function getFilesInfoByFilters($nameFilters = array())
    {
        $fileRepository = $this->em->getRepository('AppBundle:FileInfo');

        if (!empty($nameFilters)) {
            $storedFiles = $fileRepository->findByFilePath($nameFilters);
        } else {
            $storedFiles = $fileRepository->findAll();
        }

        return $storedFiles;
    }

    private function getRowQbByFilters($fileInfo, $search = null,$timeFilters = array())
    {
        $queryBuilder = $this->em->getRepository('AppBundle:FileLine')
            ->createQueryBuilder('l')
            ->where('l.fileId = :fileId')
            ->setParameter('fileId', $fileInfo->getId())
            ->orderBy('l.lineNumber', 'DESC');

        if (!empty($search)) {
            $isRegExp = false;
            //check for regexp
            if (strlen($search) > 2) {
                $first = substr($search, 0, 1);
                $last = substr($search, -1);
                if ($first == '/' && $last == '/') {
                    $isRegExp = true;
                }
            }

            if ($isRegExp) {
                $queryBuilder->andWhere("REGEXP(l.fullLine, :search) = 1")
                    ->setParameter('search', trim($search, '/'));
            } else {
                $queryBuilder->andWhere("l.fullLine LIKE :search")
                    ->setParameter('search', '%' . $search . '%');
            }
        }

        //filter rows by date and time
        if(!empty($timeFilters)) {
            foreach($timeFilters as $timeFilter) {
                $queryBuilder->andWhere("l.timestamp > :dateFrom")
                    ->andWhere("l.timestamp < :dateTo")
                    ->setParameter('dateFrom', new \DateTime($timeFilter['from']))
                    ->setParameter('dateTo', new \DateTime($timeFilter['to']));
            }
        }

        return $queryBuilder;
    }

    private function getFilesToUpdate()
    {
        $fileRepository = $this->em->getRepository('AppBundle:FileInfo');
        $finder = new Finder();
        $finder->files()->in($this->path);
        $dataToUpdate = [];
        //compare files in file system with stored in DB
        foreach ($finder as $file) {
            //pracess only *.log files
            if ($file->isFile() == false || $file->getExtension() != 'log') {
                continue;
            }
            $storedFile = $fileRepository->findOneByFilePath($file->getRealpath());
            $updatedAt = new \DateTime(date('Y-m-d h:i:s', $file->getMTime()));
            if (empty($storedFile)) { //if no file info in db, create new entry
                $dataToUpdate[] = [
                    'file'          => $file,
                    'storedFile'    => null,
                ];
            } elseif($updatedAt > $storedFile->getUpdatedAt()) { //check last file modification time
                $dataToUpdate[] = [
                    'file'          => $file,
                    'storedFile'    => $storedFile,
                ];
            }
        }

        return $dataToUpdate;
    }

    private function updateFileInfoDb($dataToUpdate)
    {
        if (empty($dataToUpdate)) {
            return;
        }

        foreach ($dataToUpdate as $fileToSave) {
            $fileInfo = $fileToSave['storedFile'];
            if (empty($fileInfo)) {
                $fileInfo = new FileInfo();
                $fileInfo->setFilePath($fileToSave['file']->getRealpath());
            }
            $fileInfo->setUpdatedAt(new \DateTime(date('Y-m-d h:i:s', $fileToSave['file']->getMTime())));
            $this->em->persist($fileInfo);
            $this->em->flush();
            $lastLineNumber = $this->getLastLineNumber($fileInfo);

            $file = new \SplFileObject($fileInfo->getFilePath());
            //start from last stored line in DB
            $file->seek($lastLineNumber);
            $flushCounter = 0;
            while ($line = $file->current()) {
                //store each line
                $this->saveLine($fileInfo, ++$lastLineNumber, $line);
                //flush pack of 1000 row
                if ($flushCounter == 1000) {
                    $this->em->flush();
                    $this->em->clear();
                    $flushCounter = 0;
                } else {
                    $flushCounter++;
                }
                $file->next();
            }
            $this->em->flush();
            $this->em->clear();
        }
    }

    private function saveLine($fileInfo, $lineNumber, $line)
    {
        $line = trim($line);
        $logInfo = $this->parser->parse($line);

        $fileLine = new FileLine();
        $fileLine->setFileId($fileInfo->getId());
        $fileLine->setLineNumber($lineNumber);
        $fileLine->setHost($logInfo->host);
        $fileLine->setLogname($logInfo->logname);
        $fileLine->setUser($logInfo->user);
        $fileLine->setTimestamp(new \DateTime($logInfo->time));
        $fileLine->setRequest($logInfo->request);
        $fileLine->setStatus($logInfo->status);
        $fileLine->setResponseBytes($logInfo->responseBytes);
        $fileLine->setFullLine($line);

        $this->em->persist($fileLine);
    }

    private function getLastLineNumber(FileInfo $file)
    {
        $lastLineNumber = $this->em->createQuery(
            'SELECT MAX(l.lineNumber)
            FROM AppBundle:FileLine l
            WHERE l.fileId = :fileId'
        )->setParameter('fileId', $file->getId())
        ->getSingleScalarResult();

        if ($lastLineNumber) {
            return $lastLineNumber;
        }

        return 0;
    }
}
