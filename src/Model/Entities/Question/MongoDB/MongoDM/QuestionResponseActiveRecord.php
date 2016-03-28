<?php

namespace Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractActiveRecord;

/**
 * Class QuestionResponseActiveRecord
 *
 * @package Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM
 */
class QuestionResponseActiveRecord extends AbstractActiveRecord
{
    public static $attrs
        = [
            'value' => ['type' => 'string']
        ];
}
