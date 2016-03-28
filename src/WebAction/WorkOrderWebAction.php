<?php

namespace Hatch\Inspections\WebAction;

use Hatch\Core\WebAction\AbstractWebAction;

/**
 * Class WorkOrderWebAction
 *
 * @package Hatch\Inspections\WebAction
 */
class WorkOrderWebAction extends AbstractWebAction
{
    public function create()
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'inspections.workOrder.controller',
            'create',
            [$data]
        );
    }

    public function updateById($id)
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'inspections.workOrder.controller',
            'updateById',
            [$id, $data]
        );
    }

    public function readByInspectionId($inspectionId)
    {
        $queryData = $this->sysApp['request']->query->all();

        return $this->callControllerAction(
            'inspections.workOrder.controller',
            'readByInspectionId',
            [$inspectionId, $queryData]
        );
    }

    public function readByTemplateVersionId($templateVersionId)
    {
        $queryData = $this->sysApp['request']->query->all();

        return $this->callControllerAction(
            'inspections.workOrder.controller',
            'readByTemplateVersionId',
            [$templateVersionId, $queryData]
        );
    }

    public function read()
    {
        $queryData = $this->sysApp['request']->query->all();

        return $this->callControllerAction(
            'inspections.workOrder.controller',
            'read',
            [$queryData]
        );
    }

    public function readById($id)
    {
        return $this->callControllerAction(
            'inspections.workOrder.controller',
            'readById',
            [$id]
        );
    }

    public function readCommentsByWorkOrderId($workOrderId)
    {
        return $this->callControllerAction(
            'inspections.workOrder.controller',
            'readCommentsByWorkOrderId',
            [$workOrderId]
        );
    }

    public function createCommentToWorkOrderById($workOrderId)
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'inspections.workOrder.controller',
            'createCommentToWorkOrderById',
            [$workOrderId, $data]
        );
    }

    public function readDashboard()
    {
        return $this->callControllerAction(
            'inspections.workOrder.controller',
            'readDashboard',
            []
        );
    }

    public function readSimpleWorkOrderReport()
    {
        $createdDate = $this->sysApp['request']->query->get('createdDate');
        $completedDate = $this->sysApp['request']->query->get('completedDate');
        $state = $this->sysApp['request']->query->get('state');

        $createdDate = null === $createdDate ? null : new \DateTime($createdDate);
        $completedDate = null === $completedDate ? null : new \DateTime($completedDate);


        return $this->callControllerAction(
            'inspections.workOrder.controller',
            'readSimpleWorkOrderReport',
            [$createdDate, $completedDate, $state]
        );
    }
}
