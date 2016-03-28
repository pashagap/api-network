<?php

namespace Hatch\Inspections\WebAction;

use Hatch\Core\WebAction\AbstractWebAction;

/**
 * Class TemplateVersionWebAction
 *
 * @package Hatch\Inspections\WebAction
 */
class TemplateVersionWebAction extends AbstractWebAction
{
    public function read()
    {
        $queryData = $this->sysApp['request']->query->all();

        return $this->callControllerAction(
            'inspections.templateVersion.controller',
            'read',
            [$queryData]
        );
    }

    public function readById($id)
    {
        return $this->callControllerAction(
            'inspections.templateVersion.controller',
            'readById',
            [$id]
        );
    }

    public function updateById($id)
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'inspections.templateVersion.controller',
            'updateById',
            [$id, $data]
        );
    }

    public function deleteById($id)
    {
        return $this->callControllerAction(
            'inspections.templateVersion.controller',
            'deleteById',
            [$id]
        );
    }

    public function create()
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'inspections.templateVersion.controller',
            'create',
            [$data]
        );
    }

    public function readByTemplateId($templateId)
    {
        $queryData = $this->sysApp['request']->query->all();

        return $this->callControllerAction(
            'inspections.templateVersion.controller',
            'readByTemplateId',
            [$templateId, $queryData]
        );
    }

    public function readSimpleTemplateVersionReport()
    {
        $createdDate = $this->sysApp['request']->query->get('createdDate');
        $modifiedDate = $this->sysApp['request']->query->get('modifiedDate');
        $templateId = $this->sysApp['request']->query->get('templateId');
        $state = $this->sysApp['request']->query->get('state');

        $createdDate = null === $createdDate ? null : new \DateTime($createdDate);
        $modifiedDate = null === $modifiedDate ? null : new \DateTime($modifiedDate);

        return $this->callControllerAction(
            'inspections.templateVersion.controller',
            'readSimpleTemplateVersionReport',
            [$templateId, $createdDate, $modifiedDate, $state]
        );
    }
}
