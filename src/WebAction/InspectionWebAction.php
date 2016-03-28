<?php

namespace Hatch\Inspections\WebAction;

use Hatch\Core\WebAction\AbstractWebAction;

/**
 * Class InspectionWebAction
 *
 * @package Hatch\Inspections\WebAction
 */
class InspectionWebAction extends AbstractWebAction
{
    public function create()
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'inspections.inspection.controller',
            'create',
            [$data]
        );
    }

    public function readById($id)
    {
        return $this->callControllerAction(
            'inspections.inspection.controller',
            'readById',
            [$id]
        );
    }

    public function readByTemplateVersionId($templateVersionId)
    {
        $queryData = $this->sysApp['request']->query->all();

        return $this->callControllerAction(
            'inspections.inspection.controller',
            'readByTemplateVersionId',
            [$templateVersionId, $queryData]
        );
    }

    public function read()
    {
        $queryData = $this->sysApp['request']->query->all();

        return $this->callControllerAction(
            'inspections.inspection.controller',
            'read',
            [$queryData]
        );
    }

    public function deleteById($id)
    {
        return $this->callControllerAction(
            'inspections.inspection.controller',
            'deleteById',
            [$id]
        );
    }

    public function updateById($id)
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'inspections.inspection.controller',
            'updateById',
            [$id, $data]
        );
    }

    public function readCommentsByInspectionId($inspectionId)
    {
        return $this->callControllerAction(
            'inspections.inspection.controller',
            'readCommentsByInspectionId',
            [$inspectionId]
        );
    }

    public function createCommentToInspectionById($inspectionId)
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'inspections.inspection.controller',
            'createCommentToInspectionById',
            [$inspectionId, $data]
        );
    }

    public function readDashboard()
    {
        return $this->callControllerAction(
            'inspections.inspection.controller',
            'readDashboard',
            []
        );
    }

    public function readStats()
    {
        return $this->callControllerAction(
            'inspections.inspection.controller',
            'readStats',
            []
        );
    }

    public function readInspectionReport($state)
    {
        $date = $this->sysApp['request']->query->get('date');
        $date = null === $date ? null : new \DateTime($date);

        return $this->callControllerAction(
            'inspections.inspection.controller',
            'readInspectionReport',
            [$state, $date]
        );
    }
}
