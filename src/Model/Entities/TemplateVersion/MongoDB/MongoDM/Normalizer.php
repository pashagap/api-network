<?php

namespace Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractNormalizer;

/**
 * Class Normalizer
 *
 * @package Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM
 */
class Normalizer extends AbstractNormalizer
{
    protected function getNormalizationRulesList()
    {
        $normalizationRules = [
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
                'normalizer' => 'inspections.question.normalizer',
                'dataKey' => 'questions',
                'dataType' => 'collection'
            ],
            [
                'dataKey' => 'createdDate',
                'dataType' => 'dateTime'
            ],
            [
                'dataKey' => 'modifiedDate',
                'dataType' => 'dateTime'
            ],
            [
                'normalizer' => 'inspections.template.normalizer',
                'dataKey' => 'template',
                'dataType' => 'single'
            ],
            [
                'normalizer' => 'core.location.normalizer',
                'dataKey' => 'location',
                'dataType' => 'single'
            ]
        ];

        return array_merge(
            $normalizationRules,
            parent::getNormalizationRulesList()
        );
    }
}
