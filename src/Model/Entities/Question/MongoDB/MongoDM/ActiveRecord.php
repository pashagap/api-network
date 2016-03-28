<?php

namespace Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractActiveRecord;

class ActiveRecord extends AbstractActiveRecord
{
    protected $primaryModel = 'inspections.question.model';
    public static $collection = 'InspectionsQuestion';

    public static $attrs
        = [
            'question' => ['type' => 'string'],
            // empty
            'type' => [
                'type' => 'string',
                'default' => 'empty'
            ],

            'responses' => [
                'model' => 'Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\QuestionResponseActiveRecord',
                'type' => 'embeds'
            ]
        ];
}
