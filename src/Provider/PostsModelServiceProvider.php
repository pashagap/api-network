<?php

namespace Hatch\SocialNetwork\Provider;

use Hatch\Core\Exception\Application\InvalidConfigException;
use Hatch\Core\Provider\AbstractModelServiceProvider;
use Silex\Application;

/**
 * Class PostsModelServiceProvider
 *
 * @package Hatch\SocialNetwork\Provider
 */
class PostsModelServiceProvider extends AbstractModelServiceProvider
{
    protected function getInjectionList(Application $app)
    {
        $databaseConfig = $app['application.configLoader']->getResource(
            'DATABASE'
        );

        switch ($databaseConfig['db_type']) {
            case'mongodb':
                switch ($databaseConfig['tool_type']) {
                    case 'mongodm' :
                        return $this->getInjectionListForMongoDBMongoDM();
                }
        }

        throw new InvalidConfigException(
            'Model hasn\'t been loaded in singleInvoice module'
        );
    }

    private function getInjectionListForMongoDBMongoDM()
    {
        $injectionList = [
            [
                'name' => 'socialNetwork.posts.activeRecord',
                'value' => 'Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM\ActiveRecord',
                'method' => 'ReCreate'
            ],
            [
                'name' => 'socialNetwork.posts.model',
                'value' => 'Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM\Model',
                'method' => 'Single'
            ],
            [
                'name' => 'socialNetwork.posts.repository',
                'value' => 'Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM\Repository',
                'method' => 'Single'
            ],
            [
                'name' => 'socialNetwork.posts.filter',
                'value' => 'Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM\Filter',
                'method' => 'Single'
            ],
            [
                'name' => 'socialNetwork.posts.normalizer',
                'value' => 'Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM\Normalizer',
                'method' => 'Single'
            ],
            [
                'name' => 'socialNetwork.posts.comments.activeRecord',
                'value' => 'Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM\CommentsActiveRecord',
                'method' => 'ReCreate'
            ],
            [
                'name' => 'socialNetwork.posts.comments.normalizer',
                'value' => 'Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM\CommentsNormalizer',
                'method' => 'Single'
            ]
        ];

        return $injectionList;
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {

    }
}