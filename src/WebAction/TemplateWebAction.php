<?php

namespace Hatch\Inspections\WebAction;

use Hatch\Core\WebAction\AbstractWebAction;

/**
 * Class TemplateWebAction
 *
 * @package Hatch\Inspections\WebAction
 */
class TemplateWebAction extends AbstractWebAction
{
    public function read()
    {
        $queryData = $this->sysApp['request']->query->all();

        return $this->callControllerAction(
            'inspections.template.controller',
            'read',
            [$queryData]
        );
    }

    public function readById($id)
    {
        return $this->callControllerAction(
            'inspections.template.controller',
            'readById',
            [$id]
        );
    }

    public function updateById($id)
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'inspections.template.controller',
            'updateById',
            [$id, $data]
        );
    }

    public function deleteById($id)
    {
        return $this->callControllerAction(
            'inspections.template.controller',
            'deleteById',
            [$id]
        );
    }

    public function create()
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'inspections.template.controller',
            'create',
            [$data]
        );
    }

    public function readSimpleTemplateReport()
    {
        $createdDate = $this->sysApp['request']->query->get('createdDate');
        $modifiedDate = $this->sysApp['request']->query->get('modifiedDate');

        $createdDate = null === $createdDate ? null : new \DateTime($createdDate);
        $modifiedDate = null === $modifiedDate ? null : new \DateTime($modifiedDate);

        return $this->callControllerAction(
            'inspections.template.controller',
            'readSimpleTemplateReport',
            [$createdDate, $modifiedDate]
        );
    }
}
