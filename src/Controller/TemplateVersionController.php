<?php

namespace Hatch\Inspections\Controller;

use Hatch\Core\Controller\AbstractController;

/**
 * Class TemplateVersionController
 *
 * @package Hatch\Inspections\Controller
 */
class TemplateVersionController extends AbstractController
{
    public function create($data)
    {
        $locationData = $this->sysApp->getCurrentLocation();
        $data['location'] = $locationData['id'];

        return $this->sysApp['inspections.templateVersion.repository']->create($data);
    }

    public function read($queryData = [])
    {
        $currentLocation = $this->sysApp->getCurrentLocation();

        $filter = isset($queryData['filter']) ? $queryData['filter'] : [];
        $filter['where']['location'] = $currentLocation['id'];

        return $this->sysApp['inspections.templateVersion.repository']->read(
            $filter
        );
    }

    public function readById($id)
    {
        return $this->sysApp['inspections.templateVersion.repository']->readById($id);
    }

    public function deleteById($id)
    {
        return $this->sysApp['inspections.templateVersion.repository']->deleteById(
            $id
        );
    }

    public function updateById($id, array $data)
    {
        return $this->sysApp['inspections.templateVersion.repository']->updateById(
            $id,
            $data
        );
    }

    public function readByTemplateId($templateId, $queryData = [])
    {
        $filter = isset($queryData['filter']) ? $queryData['filter'] : [];
        $filter['where']['template'] = $templateId;

        return $this->sysApp['inspections.templateVersion.repository']->read(
            $filter
        );
    }

    public function readSimpleTemplateVersionReport(
        $templateId = null,
        $createdDate = null,
        $modifiedDate = null,
        $state = null
    ) {
        $location = $this->sysApp->getCurrentLocation();

        return $this->sysApp['inspections.service.statisticService']
            ->readSimpleTemplateVersionReport(
                $location['id'],
                $templateId,
                $createdDate,
                $modifiedDate,
                $state
            );
    }
}
