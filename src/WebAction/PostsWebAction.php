<?php

namespace Hatch\SocialNetwork\WebAction;

use Hatch\Core\WebAction\AbstractWebAction;

/**
 * Class PostWebAction
 *
 * @package Hatch\SocialNetwork\WebAction
 */
class PostsWebAction extends AbstractWebAction
{
    public function create()
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'socialNetwork.posts.controller',
            'create',
            [$data]
        );
    }

    public function read()
    {
        $queryData = $this->sysApp['request']->query->all();

        return $this->callControllerAction(
            'socialNetwork.posts.controller',
            'read',
            [$queryData]
        );
    }

    public function readById($id)
    {
        return $this->callControllerAction(
            'socialNetwork.posts.controller',
            'readById',
            [$id]
        );
    }

    public function updateById($id)
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'socialNetwork.posts.controller',
            'updateById',
            [$id, $data]
        );
    }

    public function deleteById($id)
    {
        return $this->callControllerAction(
            'socialNetwork.posts.controller',
            'deleteById',
            [$id]
        );
    }

    public function createCommentToId($postId)
    {
        $data = $this->sysApp['request']->request->all();

        return $this->callControllerAction(
            'socialNetwork.posts.controller',
            'createCommentToId',
            [$postId, $data]
        );
    }

    public function readCommentsById($postId)
    {
        return $this->callControllerAction(
            'socialNetwork.posts.controller',
            'readCommentsById',
            [$postId]
        );
    }
}