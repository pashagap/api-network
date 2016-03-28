<?php

namespace Hatch\Inspections\Controller;

use Hatch\Core\Controller\AbstractController;

/**
 * Class AnswerController
 *
 * @package Hatch\Inspections\Controller
 */
class AnswerController extends AbstractController
{
    public function create($data)
    {
        $locationData = $this->sysApp->getCurrentLocation();
        $data['location'] = $locationData['id'];
        return $this->sysApp['inspections.answer.repository']->create($data);
    }

    public function readById($id)
    {
        return $this->sysApp['inspections.answer.repository']->readById($id);
    }

    public function readByInspectionId($inspectionId, $queryData = [])
    {
        $filter = isset($queryData['filter']) ? $queryData['filter'] : [];
        $filter['where']['inspection'] = $inspectionId;

        return $this->sysApp['inspections.answer.repository']->read(
            $filter
        );
    }

    public function updateById($id, array $data)
    {
        return $this->sysApp['inspections.answer.repository']->updateById(
            $id,
            $data
        );
    }

    public function read($queryData = [])
    {
        $filter = isset($queryData['filter']) ? $queryData['filter'] : [];
        $currentLocation = $this->sysApp->getCurrentLocation();

        $filter['where']['location'] = $currentLocation['id'];

        return $this->sysApp['inspections.answer.repository']->read(
            $filter
        );
    }
}
