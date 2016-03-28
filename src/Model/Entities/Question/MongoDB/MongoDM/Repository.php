<?php

namespace Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractRepositoryCrud;
use Hatch\Inspections\Model\Entities\Question\QuestionRepositoryInterface;

/**
 * Class Repository
 *
 * @package Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM
 */
class Repository extends AbstractRepositoryCrud implements QuestionRepositoryInterface
{
    protected $primaryCrudModel = 'inspections.question.model';

    public function getPrimaryFilter()
    {
        return $this->sysApp['inspections.question.filter'];
    }

    public function getPrimaryNormalizer()
    {
        return $this->sysApp['inspections.question.normalizer'];
    }
}
