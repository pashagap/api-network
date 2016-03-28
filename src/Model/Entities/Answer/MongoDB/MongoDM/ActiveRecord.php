<?php

namespace Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM;

use Hatch\Core\Exception\Model\ValidationModelException;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractActiveRecord;
use Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\ActiveRecord as InspectionActiveRecord;

/**
 * Class ActiveRecord
 *
 * @package Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM
 */
class ActiveRecord extends AbstractActiveRecord
{
    protected $primaryModel = 'inspections.answer.model';
    public static $collection = 'InspectionsAnswer';

    public static $attrs
        = [
            'question' => [
                'model' => 'Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],
            'inspection' => [
                'model' => 'Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],
            'location' => [
                'model' => 'Hatch\Core\Model\Entities\Location\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],

            //QuestionOutcomeType
            'outcome' => [
                'type' => 'string',
                'default' => 'N/A'
            ],
            'content' => [
                'model' => 'Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\QuestionResponseActiveRecord',
                'type' => 'embeds'
            ]
        ];

    public function validate()
    {
        $uniqueCriteria = [
            'question.$id' => $this->question->getId(),
            'inspection.$id' => $this->inspection->getId()
        ];

        if (!$this->isUnique($uniqueCriteria)) {
            throw new ValidationModelException(
                'Answer',
                sprintf(
                    'in this inspection with question "%s" already exists',
                    $this->question->question
                )
            );
        }

        parent::validate();
    }

    public function getOutcome()
    {
        return $this->__getter('outcome');
    }

    public function setOutcome($outcome)
    {
        $this->__setter('outcome', $outcome);
    }

    /**
     * @return InspectionActiveRecord
     */
    public function getInspection()
    {
        return $this->__getter('inspection');
    }

    public function setInspection($inspection)
    {
        $this->__setter('inspection', $inspection);
    }
}
