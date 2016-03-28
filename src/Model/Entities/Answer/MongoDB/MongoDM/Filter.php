<?php

namespace Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractFilter;
use Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\QuestionResponseActiveRecord;

/**
 * Class Filter
 *
 * @package Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM
 */
class Filter extends AbstractFilter
{
    protected function getPrimaryActiveRecord()
    {
        return $this->sysApp['inspections.answer.activeRecord'];
    }

    protected function defineSearchCriteria(array &$criteria)
    {
        $definitionObjectList = ['templateVersion', 'question', 'inspection', 'location'];

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

        if(isset($data['location'])) {
            $this->defineLocation($data);
        }

        if (isset($data['content'])) {
            $data['content'] = $this->generateContent($data['content']);
        }

        return parent::prepareModifyingData($data);
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
        $data['inspection'] = $inspectionModel->readOne(
            ['_id' => self::formatId($data['inspection'])]
        );
    }

    private function generateContent($responses)
    {
        $result = [];
        foreach ($responses as $responseItem) {
            /** @var QuestionResponseActiveRecord $questionResponseInstance */
            $questionResponseInstance
                = $this->sysApp['inspections.question.response.activeRecord'];
            $questionResponseInstance->update($responseItem);
            array_push($result, $questionResponseInstance);
        }

        return $result;
    }

    private function defineLocation(array &$data)
    {
        $locationModel = $this->sysApp['core.location.model'];
        $location = $locationModel->readOne(
            ['_id' => self::formatId($data['location'])]
        );
        $data['location'] = $location;
    }
}
