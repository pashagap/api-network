<?php

namespace Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractNormalizer;

/**
 * Class StateHistoryNormalizer
 *
 * @package Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM
 */
class StateHistoryNormalizer extends AbstractNormalizer
{
    protected function getNormalizationRulesList()
    {
        $normalizationRules = [
            [
                'dataKey' => 'on',
                'dataType' => 'dateTime'
            ]
        ];

        return array_merge(
            $normalizationRules,
            parent::getNormalizationRulesList()
        );
    }
}
