<?php

namespace Hatch\SocialNetwork\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

/**
 * Class PostsControllerProvider
 *
 * @package Hatch\SocialNetwork\Provider
 */
class PostsControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->post('/posts/', 'socialNetwork.posts.webAction:create')
            ->before('socialNetwork.posts.middleware.requestValidator:validateCreateRequest');
        $controllers->post(
            '/posts/{postId}/comments',
            'socialNetwork.posts.webAction:createCommentToId'
        )->before('socialNetwork.posts.middleware.requestValidator:validateCommentCreateRequest');

        $controllers->get('/posts/', 'socialNetwork.posts.webAction:read');
        $controllers->get('/posts/{id}', 'socialNetwork.posts.webAction:readById');
        $controllers->get(
            '/posts/{postId}/comments',
            'socialNetwork.posts.webAction:readCommentsById'
        );

        $controllers->put('/posts/{id}', 'socialNetwork.posts.webAction:updateById')
            ->before('socialNetwork.posts.middleware.requestValidator:validateUpdateRequest');

        $controllers->delete('/posts/{id}', 'socialNetwork.posts.webAction:deleteById');

        return $controllers;
    }
}