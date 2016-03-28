<?php

namespace Hatch\Inspections\WebAction;

use Hatch\Core\WebAction\AbstractWebAction;

/**
 * Class AnswerWebAction
 *
 * @package Hatch\Inspections\WebAction
 */
class AnswerWebAction extends AbstractWebAction
{
    public function readByInspectionId($inspectionId)
    {
        $queryData = $this->sysApp['request']->query->all();

        return $this->callControllerAction(
            'inspections.answer.controller',
            'readByInspectionId',
            [$inspectionId, $queryData]
        );
    }

    public function readById($id)
    {
        return $this->callControllerAction(
            'inspections.answer.controller',
            'readById',
            [$id]
        );
    }

    public function updateById($id)
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'inspections.answer.controller',
            'updateById',
            [$id, $data]
        );
    }

    public function create()
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'inspections.answer.controller',
            'create',
            [$data]
        );
    }

    public function read()
    {
        $queryData = $this->sysApp['request']->query->all();

        return $this->callControllerAction(
            'inspections.answer.controller',
            'read',
            [$queryData]
        );
    }
}
