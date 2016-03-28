<?php

namespace Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractNormalizer;

/**
 * Class CommentNormalizer
 *
 * @package Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM
 */
class CommentNormalizer extends AbstractNormalizer
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
                'dataKey' => 'createdByData',
                'dataType' => 'single'
            ],
            [
                'normalizer' => 'core.model.image.normalizer',
                'dataKey' => 'images',
                'dataType' => 'collection'
            ],
            [
                'dataKey' => 'createdDate',
                'dataType' => 'dateTime'
            ]
        ];

        return array_merge(
            $normalizationRules,
            parent::getNormalizationRulesList()
        );
    }

    protected function hideUnnecessaryFields(array &$data)
    {
        parent::hideUnnecessaryFields($data);

        if (isset($data['id'])
            && $data['id'] instanceof \MongoId
        ) {
            $data['id'] = $data['id']->{'$id'};
        }
    }
}
