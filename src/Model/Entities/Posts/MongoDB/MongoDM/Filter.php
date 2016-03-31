<?php

namespace Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractFilter;

/**
 * Class Filter
 *
 * @package Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM
 */
class Filter extends AbstractFilter
{
    protected function getPrimaryActiveRecord()
    {
        return $this->sysApp['socialNetwork.posts.activeRecord'];
    }

    public function prepareModifyingData(array &$data)
    {
        if (isset($data['location'])) {
            $this->defineLocation($data);
        }

        if (isset($data['createdBy'])) {
            $this->defineCreatedBy($data);
        }

        if (isset($data['modifiedBy'])) {
            $this->defineModifiedBy($data);
        }

        return parent::prepareModifyingData($data);
    }

    protected function defineSearchCriteria(array &$criteria)
    {
        $definitionObjectList = ['location', 'createdBy', 'modifiedBy'];

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

        if (isset($criteria['tags'])) {
            if (!is_array($criteria['tags'])) {
                $searchValue = [$criteria['tags']];
            } else {
                $searchValue = $criteria['tags'];
            }
            $criteria['tags'] = [
                '$in' => $searchValue
            ];
        }

        parent::defineSearchCriteria($criteria);
    }

    private function defineLocation(array &$data)
    {
        $locationModel = $this->sysApp['core.location.model'];
        $data['location'] = $locationModel->readOne(
            ['_id' => self::formatId($data['location'])]
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