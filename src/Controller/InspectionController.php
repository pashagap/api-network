<?php

namespace Hatch\Inspections\Controller;

use Hatch\Core\Controller\AbstractController;

/**
 * Class InspectionController
 *
 * @package Hatch\Inspections\Controller
 */
class InspectionController extends AbstractController
{
    public function create($data)
    {
        $currentLocation = $this->sysApp->getCurrentLocation();
        $data['location'] = $currentLocation['id'];

        return $this->sysApp['inspections.inspection.repository']->create($data);
    }

    public function read($queryData = [])
    {
        $filter = isset($queryData['filter']) ? $queryData['filter'] : [];

        $currentLocation = $this->sysApp->getCurrentLocation();
        $filter['where']['location'] = $currentLocation['id'];

        return $this->sysApp['inspections.inspection.repository']->read($queryData);
    }

    public function readById($id)
    {
        return $this->sysApp['inspections.inspection.repository']->readById($id);
    }

    public function readByTemplateVersionId($id, $queryData = [])
    {
        $filter = isset($queryData['filter']) ? $queryData['filter'] : [];
        $filter['where']['templateVersion'] = $id;

        return $this->sysApp['inspections.inspection.repository']->read($filter);
    }

    public function deleteById($id)
    {
        return $this->sysApp['inspections.inspection.repository']->deleteById($id);
    }

    public function updateById($id, array $data)
    {
        return $this->sysApp['inspections.inspection.repository']->updateById(
            $id,
            $data
        );
    }

    public function createCommentToInspectionById($inspectionId, $data)
    {
        return $this->sysApp['inspections.inspection.repository']->createCommentToId(
            $inspectionId,
            $data
        );
    }

    public function readCommentsByInspectionId($inspectionId)
    {
        return $this->sysApp['inspections.inspection.repository']->readCommentsById(
            $inspectionId
        );
    }

    public function readDashboard()
    {
        $location = $this->sysApp->getCurrentLocation();

        return $this->sysApp['inspections.service.statisticService']
            ->readInspectionsDashboard($location['id']);
    }

    public function readStats()
    {
        $location = $this->sysApp->getCurrentLocation();

        return $this->sysApp['inspections.service.statisticService']
            ->readInspectionsStatistic($location['id']);
    }

    public function readInspectionReport($state, $date)
    {
        $location = $this->sysApp->getCurrentLocation();

        return $this->sysApp['inspections.service.statisticService']
            ->readInspectionReport($location['id'], $state, $date);
    }
}
