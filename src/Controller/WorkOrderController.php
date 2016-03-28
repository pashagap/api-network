<?php

namespace Hatch\Inspections\Controller;

use Hatch\Core\Controller\AbstractController;

/**
 * Class WorkOrderController
 *
 * @package Hatch\Inspections\Controller
 */
class WorkOrderController extends AbstractController
{
    public function create($data)
    {
        $locationData = $this->sysApp->getCurrentLocation();
        $data['location'] = $locationData['id'];

        return $this->sysApp['inspections.workOrder.repository']->create($data);
    }

    public function updateById($id, array $data)
    {
        return $this->sysApp['inspections.workOrder.repository']->updateById(
            $id,
            $data
        );
    }

    public function readByInspectionId($inspectionId, $queryData = [])
    {
        $filter = isset($queryData['filter']) ? $queryData['filter'] : [];
        $filter['where']['inspection'] = $inspectionId;

        return $this->sysApp['inspections.workOrder.repository']->read(
            $filter
        );
    }

    public function readByTemplateVersionId($templateVersionId, $queryData = [])
    {
        $filter = isset($queryData['filter']) ? $queryData['filter'] : [];
        $filter['where']['templateVersion'] = $templateVersionId;

        return $this->sysApp['inspections.workOrder.repository']->read(
            $filter
        );
    }

    public function read($queryData = [])
    {
        $filter = isset($queryData['filter']) ? $queryData['filter'] : [];
        $currentLocation = $this->sysApp->getCurrentLocation();

        $filter['where']['location'] = $currentLocation['id'];

        return $this->sysApp['inspections.workOrder.repository']->read(
            $filter
        );
    }

    public function readCommentsByWorkOrderId($workOrderId)
    {
        return $this->sysApp['inspections.workOrder.repository']->readCommentsById(
            $workOrderId
        );
    }

    public function readById($id)
    {
        return $this->sysApp['inspections.workOrder.repository']->readById(
            $id
        );
    }

    public function createCommentToWorkOrderById($workOrderId, $data)
    {
        return $this->sysApp['inspections.workOrder.repository']->createCommentToId(
            $workOrderId,
            $data
        );
    }

    public function readDashboard()
    {
        $location = $this->sysApp->getCurrentLocation();

        return $this->sysApp['inspections.service.statisticService']
            ->readWorkOrdersDashboard($location['id']);
    }

    public function readSimpleWorkOrderReport(
        $createdDate = null,
        $completedDate = null,
        $state = null
    ) {
        $location = $this->sysApp->getCurrentLocation();

        return $this->sysApp['inspections.service.statisticService']
            ->readSimpleWorkOrderReport(
                $location['id'],
                $createdDate,
                $completedDate,
                $state
            );
    }
}
