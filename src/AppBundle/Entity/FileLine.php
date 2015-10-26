<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="file_line")
 */
class FileLine
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $fileId;

    /**
     * @ORM\Column(type="integer")
     */
    protected $lineNumber;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $host;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $logname;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $user;

    /**
     * @ORM\Column(type="datetime", name="timestamp")
     */
    protected $timestamp;

    /**
     * @ORM\Column(type="string", length=500)
     */
    protected $request;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @ORM\Column(type="integer")
     */
    protected $responseBytes;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    protected $fullLine;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set host
     *
     * @param string $host
     *
     * @return FileLine
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set logname
     *
     * @param string $logname
     *
     * @return FileLine
     */
    public function setLogname($logname)
    {
        $this->logname = $logname;

        return $this;
    }

    /**
     * Get logname
     *
     * @return string
     */
    public function getLogname()
    {
        return $this->logname;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return FileLine
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return FileLine
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set request
     *
     * @param string $request
     *
     * @return FileLine
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return FileLine
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set responseBytes
     *
     * @param integer $responseBytes
     *
     * @return FileLine
     */
    public function setResponseBytes($responseBytes)
    {
        $this->responseBytes = $responseBytes;

        return $this;
    }

    /**
     * Get responseBytes
     *
     * @return integer
     */
    public function getResponseBytes()
    {
        return $this->responseBytes;
    }

    /**
     * Set fileId
     *
     * @param integer $fileId
     *
     * @return FileLine
     */
    public function setFileId($fileId)
    {
        $this->fileId = $fileId;

        return $this;
    }

    /**
     * Get fileId
     *
     * @return integer
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * Set lineNumber
     *
     * @param integer $lineNumber
     *
     * @return FileLine
     */
    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = $lineNumber;

        return $this;
    }

    /**
     * Get lineNumber
     *
     * @return integer
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * Set fullLine
     *
     * @param string $fullLine
     *
     * @return FileLine
     */
    public function setFullLine($fullLine)
    {
        $this->fullLine = $fullLine;

        return $this;
    }

    /**
     * Get fullLine
     *
     * @return string
     */
    public function getFullLine()
    {
        return $this->fullLine;
    }
}
