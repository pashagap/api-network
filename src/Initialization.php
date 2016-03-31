<?php

namespace Hatch\SocialNetwork;

use Hatch\Core\AbstractInitialization;
use Hatch\SocialNetwork\Provider\PostsControllerProvider;
use Hatch\SocialNetwork\Provider\PostsServiceProvider;
use Hatch\SocialNetwork\Provider\PostsModelServiceProvider;

/**
 * Class Initialization
 *
 * @package Hatch\SocialNetwork
 */
final class Initialization extends AbstractInitialization
{
    public function init()
    {
        $this->sysApp->register(new PostsServiceProvider());
        $this->sysApp->register(new PostsModelServiceProvider());

        $this->sysApp->mount(
            '/',
            new PostsControllerProvider()
        );
    }

    /**
     * Function That handle to boot module
     *
     * @return mixed
     */
    public function boot()
    {
        $this->sysApp['socialNetwork.posts.model']->initialize();
    }

    /**
     * @return array
     */
    protected function getControllerPaths()
    {
        return glob(__DIR__.'/Controller/*');
    }
}
