<?php

namespace Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractCrudModel;

/**
 * Class Model
 *
 * @package Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM
 */
class Model extends AbstractCrudModel
{
    protected $primaryActiveRecord = 'socialNetwork.posts.activeRecord';
    protected $modelName = 'post';

    public function initialize()
    {

    }
}