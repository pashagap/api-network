<?php

namespace Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractFilter;

/**
 * Class Filter
 *
 * @package Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM
 */
class Filter extends AbstractFilter
{
    protected function getPrimaryActiveRecord()
    {
        return $this->sysApp['inspections.inspection.activeRecord'];
    }

    protected function defineSearchCriteria(array &$criteria)
    {
        $definitionObjectList = ['templateVersion', 'location', 'question'];

        foreach ($definitionObjectList as $definition) {
            if (isset($criteria[$definition])) {
                if (is_array($criteria[$definition])) {
                    $in = [];
                    foreach ($criteria[$definition] as $itemId) {
                        array_push($in, self::formatId($itemId));
                    }
                    $definedVariable = ['$in' => $in];
                } else {
                    $definedVariable = self::formatId($criteria[$definition]);
                }
                $criteria[$definition.'.$id'] = $definedVariable;
                unset($criteria[$definition]);
            }
        }

        parent::defineSearchCriteria($criteria);
    }

    public function prepareModifyingData(array &$data)
    {
        if (isset($data['createdBy'])) {
            $this->defineCreatedBy($data);
        }

        if (isset($data['modifiedBy'])) {
            $this->defineModifiedBy($data);
        }

        if (isset($data['templateVersion'])) {
            $this->defineTemplateVersion($data);
        }

        if(isset($data['location'])) {
            $this->defineLocation($data);
        }

        return parent::prepareModifyingData($data);
    }

    private function defineLocation(array &$data)
    {
        $locationModel = $this->sysApp['core.location.model'];
        $data['location'] = $locationModel->readOne(
            ['_id' => self::formatId($data['location'])]
        );
    }

    private function defineTemplateVersion(array &$data)
    {
        $templateVersionModel = $this->sysApp['inspections.templateVersion.model'];
        $data['templateVersion'] = $templateVersionModel->readOne(
            ['_id' => self::formatId($data['templateVersion'])]
        );
    }

    private function defineCreatedBy(array &$data)
    {
        $userModel = $this->sysApp['core.user.model'];
        $data['createdBy'] = $userModel->readOne(
            ['_id' => self::formatId($data['createdBy'])]
        );
    }

    private function defineModifiedBy($data)
    {
        $userModel = $this->sysApp['core.user.model'];
        $data['modifiedBy'] = $userModel->readOne(
            ['_id' => self::formatId($data['modifiedBy'])]
        );
    }
}
