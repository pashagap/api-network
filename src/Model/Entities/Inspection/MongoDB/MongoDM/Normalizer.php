<?php

namespace Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractNormalizer;

/**
 * Class Normalizer
 *
 * @package Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM
 */
class Normalizer extends AbstractNormalizer
{
    protected function getNormalizationRulesList()
    {
        $normalizationRules = [
            [
                'normalizer' => 'inspections.templateVersion.normalizer',
                'dataKey' => 'templateVersion',
                'dataType' => 'single'
            ],
            [
                'normalizer' => 'inspections.template.normalizer',
                'dataKey' => 'template',
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
                'normalizer' => 'core.location.normalizer',
                'dataKey' => 'location',
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
                'normalizer' => 'inspections.inspection.comment.normalizer',
                'dataKey' => 'comments',
                'dataType' => 'collection'
            ],
            [
                'normalizer' => 'core.model.image.normalizer',
                'dataKey' => 'images',
                'dataType' => 'collection'
            ]
        ];

        return array_merge(
            $normalizationRules,
            parent::getNormalizationRulesList()
        );
    }
}
