<?php

namespace Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractCrudModel;

/**
 * Class Model
 *
 * @package Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM
 */
class Model extends AbstractCrudModel
{
    protected $primaryActiveRecord = 'inspections.question.activeRecord';
    protected $modelName = 'inspection question';
}
