<?php

namespace Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractRepositoryCrud;
use Hatch\Inspections\Model\Entities\Answer\AnswerRepositoryInterface;

/**
 * Class Repository
 *
 * @package Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM
 */
class Repository extends AbstractRepositoryCrud implements AnswerRepositoryInterface
{
    protected $primaryCrudModel = 'inspections.answer.model';

    public function getPrimaryFilter()
    {
        return $this->sysApp['inspections.answer.filter'];
    }

    public function getPrimaryNormalizer()
    {
        return $this->sysApp['inspections.answer.normalizer'];
    }
}
