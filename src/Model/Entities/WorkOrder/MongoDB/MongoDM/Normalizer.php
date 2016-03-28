<?php

namespace Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractNormalizer;

/**
 * Class Normalizer
 *
 * @package Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM
 */
class Normalizer extends AbstractNormalizer
{
    protected function getNormalizationRulesList()
    {
        $normalizationRules = [
            [
                'normalizer' => 'inspections.inspection.normalizer',
                'dataKey' => 'inspection',
                'dataType' => 'single'
            ],
            [
                'normalizer' => 'inspections.question.normalizer',
                'dataKey' => 'question',
                'dataType' => 'single'
            ],
            [
                'normalizer' => 'inspections.templateVersion.normalizer',
                'dataKey' => 'templateVersion',
                'dataType' => 'single'
            ],
            [
                'normalizer' => 'inspections.answer.normalizer',
                'dataKey' => 'answer',
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
            ],
            [
                'normalizer' => 'core.location.normalizer',
                'dataKey' => 'location',
                'dataType' => 'single'
            ],
            [
                'normalizer' => 'inspections.workOrder.comment.normalizer',
                'dataKey' => 'comments',
                'dataType' => 'collection'
            ],
            [
                'normalizer' => 'inspections.workOrder.stateHistory.normalizer',
                'dataKey' => 'stateHistory',
                'dataType' => 'collection'
            ],
            [
                'normalizer' => 'core.model.image.normalizer',
                'dataKey' => 'images',
                'dataType' => 'collection'
            ],
            [
                'normalizer' => 'core.user.normalizer',
                'dataKey' => 'assigned',
                'dataType' => 'single'
            ]
        ];

        return array_merge(
            $normalizationRules,
            parent::getNormalizationRulesList()
        );
    }
}
