<?php

namespace Hatch\SocialNetwork\Provider;

use Hatch\SocialNetwork\Controller\PostsController;
use Hatch\SocialNetwork\Middleware\PostsRequestValidator;
use Hatch\SocialNetwork\WebAction\PostsWebAction;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class PostsServiceProvider
 *
 * @package Hatch\SocialNetwork\Provider
 */
class PostsServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $this->registerCommon($app);
        $this->registerMiddlewares($app);
        $this->registerWebActions($app);
        $this->registerControllers($app);
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
        // TODO: Implement boot() method.
    }

    public function registerCommon(Application $app)
    {

    }

    public function registerMiddlewares(Application $app)
    {
        $app['socialNetwork.posts.middleware.requestValidator'] = $app->share(
            function () {
                return new PostsRequestValidator();
            }
        );
    }

    public function registerWebActions(Application $app)
    {
        $app['socialNetwork.posts.webAction'] = $app->share(
            function () {
                return new PostsWebAction();
            }
        );
    }

    public function registerControllers(Application $app)
    {
        $app['socialNetwork.posts.controller'] = $app->share(
            function () {
                return new PostsController();
            }
        );
    }
}