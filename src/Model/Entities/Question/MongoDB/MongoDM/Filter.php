<?php

namespace Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractFilter;

/**
 * Class Filter
 *
 * @package Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM
 */
class Filter extends AbstractFilter
{
    protected function getPrimaryActiveRecord()
    {
        return $this->sysApp['inspections.question.activeRecord'];
    }

    public function prepareModifyingData(array &$data)
    {
        if (isset($data['responses'])) {
            $data['responses'] = $this->generateResponses(
                $data['responses']
            );
        }

        return parent::prepareModifyingData($data);
    }

    /**
     * @param $responses
     *
     * @return array
     */
    private function generateResponses($responses)
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
}
