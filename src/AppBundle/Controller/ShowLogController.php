<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\File;

class ShowLogController extends Controller
{
    /**
     * @Route("/showLog", name="showLog")
     */
    public function indexAction(Request $request)
    {
        $search = $request->query->get('search', null);
        $filterName = $request->query->get('filterName', null);
        $filterTime = $request->query->get('filterTime', null);
        $filterTimeFrom = $request->query->get('filterTimeFrom', null);
        $filterTimeTo = $request->query->get('filterTimeTo', null);

        if (!empty($filterName) && is_string($filterName)) {
            $filterName = [$filterName];
        }

        if (empty($filterTime) && !empty($filterTimeFrom) && !empty($filterTimeTo)) {
            $filterTime[] = [
                'from'  => $filterTimeFrom,
                'to'    => $filterTimeTo,
            ];
        }

        $logReader = $this->get('logReader');
        $logReader->actualizeLogsInDb();
        $queryBuilders = $logReader->getQbForPagination($search, $filterName, $filterTime);
        $paginator  = $this->get('knp_paginator');

        $paginations = [];
        foreach ($queryBuilders as $filePath => $qb) {
            $paginations[$filePath] = $paginator->paginate(
                $qb,
                $request->query->getInt('page', 1)/*page number*/,
                $request->query->getInt('perPage', 10)/*limit per page*/
            );
        }

        return $this->render(
            'default/showLog.html.twig',
            [
                'paginations'       => $paginations,
                'perPageSettings'   => $this->getPerPageSettings(),
            ]
        );
    }

    private function getPerPageSettings()
    {
        return [10, 25, 50, 100];
    }
}
