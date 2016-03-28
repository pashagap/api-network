<?php

namespace Hatch\Inspections\Controller;

use Hatch\Core\Controller\AbstractController;
use Hatch\Inspections\Model\Entities\Template\TemplateRepositoryInterface;

/**
 * Class TemplateController
 *
 * @package Hatch\Inspections\Controller
 */
class TemplateController extends AbstractController
{
    public function create($data)
    {
        $locationData = $this->sysApp->getCurrentLocation();
        $data['location'] = $locationData['id'];

        /** @var TemplateRepositoryInterface $templateRepository */
        $templateRepository = $this->sysApp['inspections.template.repository'];

        return $templateRepository->create($data);
    }

    public function read($queryData = [])
    {
        $currentLocation = $this->sysApp->getCurrentLocation();

        $filter = isset($queryData['filter']) ? $queryData['filter'] : [];
        $filter['where']['location'] = $currentLocation['id'];

        return $this->sysApp['inspections.template.repository']->read(
            $filter
        );
    }

    public function readById($id)
    {
        return $this->sysApp['inspections.template.repository']->readById($id);
    }

    public function deleteById($id)
    {
        return $this->sysApp['inspections.template.repository']->deleteById(
            $id
        );
    }

    public function updateById($id, array $data)
    {
        return $this->sysApp['inspections.template.repository']->updateById(
            $id,
            $data
        );
    }

    public function readSimpleTemplateReport($createdDate = null, $modifiedDate = null)
    {
        $location = $this->sysApp->getCurrentLocation();

        return $this->sysApp['inspections.service.statisticService']
            ->readSimpleTemplateReport($location['id'], $createdDate, $modifiedDate);
    }
}
