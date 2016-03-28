<?php

namespace Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractFilter;
use Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\Model as QuestionModel;
use Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\Filter as QuestionFilter;

/**
 * Class Filter
 *
 * @package Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM
 */
class Filter extends AbstractFilter
{
    protected function getPrimaryActiveRecord()
    {
        return $this->sysApp['inspections.templateVersion.activeRecord'];
    }

    protected function defineSearchCriteria(array &$criteria)
    {
        $definitionObjectList = ['createdBy', 'modifiedBy', 'question', 'template', 'location'];

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

        if (isset($data['template'])) {
            $this->defineTemplate($data);
        }

        if(isset($data['questions'])) {
            $this->defineQuestions($data);
        }

        if(isset($data['location'])) {
            $this->defineLocation($data);
        }

        return parent::prepareModifyingData($data);
    }

    private function defineQuestions(array &$data)
    {
        $questions = [];

        if (isset($data['questions'])) {

            /** @var QuestionModel $questionModel */
            $questionModel = $this->sysApp['inspections.question.model'];
            /** @var QuestionFilter $questionFilter */
            $questionFilter = $this->sysApp['inspections.question.filter'];

            foreach ($data['questions'] as $question) {
                $questionFilter->prepareModifyingData($question);
                $question = $questionModel->create($question);
                array_push($questions, $question);
            }
        }

        $data['questions'] = $questions;
    }

    private function defineTemplate(array &$data)
    {
        $locationModel = $this->sysApp['inspections.template.model'];
        $data['template'] = $locationModel->readOne(
            ['_id' => self::formatId($data['template'])]
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

    private function defineLocation(array &$data)
    {
        $locationModel = $this->sysApp['core.location.model'];
        $data['location'] = $locationModel->readOne(
            ['_id' => self::formatId($data['location'])]
        );
    }
}
