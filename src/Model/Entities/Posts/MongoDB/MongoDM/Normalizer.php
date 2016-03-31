<?php

namespace Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractNormalizer;

/**
 * Class Normalizer
 *
 * @package Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM
 */
class Normalizer extends AbstractNormalizer
{
    protected function getNormalizationRulesList()
    {
        $normalizationRules = [
            [
                'normalizer' => 'core.location.normalizer',
                'dataKey' => 'location',
                'dataType' => 'single'
            ],
            [
                'normalizer' => 'core.user.normalizer',
                'dataKey' => 'createdBy',
                'dataType' => 'single'
            ],
            [
                'normalizer' => 'core.user.normalizer',
                'dataKey' => 'modifiedBy',
                'dataType' => 'single'
            ],
            [
                'dataKey' => 'createdDate',
                'dataType' => 'dateTime'
            ],
            [
                'dataKey' => 'modifiedDate',
                'dataType' => 'dateTime'
            ]
        ];

        return array_merge(
            $normalizationRules,
            parent::getNormalizationRulesList()
        );
    }
}