<?php

namespace Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractNormalizer;

/**
 * Class Normalizer
 *
 * @package Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM
 */
class Normalizer extends AbstractNormalizer
{
    protected function getNormalizationRulesList()
    {
        $normalizationRules = [
            [
                'normalizer' => 'inspections.question.normalizer',
                'dataKey' => 'question',
                'dataType' => 'single'
            ],
            [
                'normalizer' => 'inspections.inspection.normalizer',
                'dataKey' => 'inspection',
                'dataType' => 'single'
            ],
            [
                'normalizer' => 'core.location.normalizer',
                'dataKey' => 'location',
                'dataType' => 'single'
            ],
            [
                'normalizer' => 'inspections.question.response.normalizer',
                'dataKey' => 'content',
                'dataType' => 'collection'
            ]
        ];

        return array_merge(
            $normalizationRules,
            parent::getNormalizationRulesList()
        );
    }
}
