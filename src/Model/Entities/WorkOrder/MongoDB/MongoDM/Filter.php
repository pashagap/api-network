<?php

namespace Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractFilter;

/**
 * Class Filter
 *
 * @package Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM
 */
class Filter extends AbstractFilter
{
    protected function getPrimaryActiveRecord()
    {
        return $this->sysApp['inspections.workOrder.activeRecord'];
    }

    protected function defineSearchCriteria(array &$criteria)
    {
        $definitionObjectList = [
            'inspection',
            'question',
            'templateVersion',
            'answer',
            'location',
            'assigned'
        ];

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
        if (isset($data['question'])) {
            $this->defineQuestion($data);
        }

        if (isset($data['inspection'])) {
            $this->defineInspection($data);
        }

        if (isset($data['templateVersion'])) {
            $this->defineTemplateVersion($data);
        }

        if (isset($data['location'])) {
            $this->defineLocation($data);
        }

        if (isset($data['answer'])) {
            $this->defineAnswer($data);
        }

        if (isset($data['assigned'])) {
            $this->defineAssigned($data);
        }

        return parent::prepareModifyingData($data);
    }

    private function defineLocation(array &$data)
    {
        $locationModel = $this->sysApp['core.location.model'];
        $location = $locationModel->readOne(
            ['_id' => self::formatId($data['location'])]
        );
        $data['location'] = $location;
    }

    private function defineAnswer(array &$data)
    {
        $answerModel = $this->sysApp['inspections.answer.model'];
        $answer = $answerModel->readOne(
            ['_id' => self::formatId($data['answer'])]
        );
        $data['answer'] = $answer;
    }

    private function defineTemplateVersion(array &$data)
    {
        $templateVersionModel = $this->sysApp['inspections.templateVersion.model'];
        $templateVersion = $templateVersionModel->readOne(
            ['_id' => self::formatId($data['templateVersion'])]
        );
        $data['templateVersion'] = $templateVersion;
    }

    private function defineQuestion(array &$data)
    {
        $questionModel = $this->sysApp['inspections.question.model'];
        $question = $questionModel->readOne(
            ['_id' => self::formatId($data['question'])]
        );
        $data['question'] = $question;
    }

    private function defineInspection(array &$data)
    {
        $inspectionModel = $this->sysApp['inspections.inspection.model'];
        $inspection = $inspectionModel->readOne(
            ['_id' => self::formatId($data['inspection'])]
        );
        $data['inspection'] = $inspection;
    }

    private function defineAssigned(array &$data)
    {
        $userModel = $this->sysApp['core.user.model'];
        $user = $userModel->readOne(
            ['_id' => self::formatId($data['assigned'])]
        );
        $data['assigned'] = $user;
    }
}
