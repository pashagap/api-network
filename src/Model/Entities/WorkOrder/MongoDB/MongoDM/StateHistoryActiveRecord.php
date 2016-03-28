<?php

namespace Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractActiveRecord;

/**
 * Class StateHistoryActiveRecord
 *
 * @package Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM
 */
class StateHistoryActiveRecord extends AbstractActiveRecord
{
    public static $attrs
        = [
            'state' => ['type' => 'string'],
            'on' => ['type' => 'date']
        ];
}
