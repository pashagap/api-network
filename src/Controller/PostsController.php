<?php

namespace Hatch\SocialNetwork\Controller;

use Hatch\Core\Controller\AbstractController;

/**
 * Class PostsController
 *
 * @package Hatch\SocialNetwork\Controller
 */
class PostsController extends AbstractController
{
    public function create($data)
    {
        $currentLocation = $this->sysApp->getCurrentLocation();
        $data['location'] = $currentLocation['id'];

        return $this->sysApp['socialNetwork.posts.repository']->create($data);
    }

    public function read($queryData = [])
    {
        $currentLocation = $this->sysApp->getCurrentLocation();

        $filter = isset($queryData['filter']) ? $queryData['filter'] : [];
        $filter['where']['location'] = $currentLocation['id'];

        return $this->sysApp['socialNetwork.posts.repository']->read($filter);
    }

    public function readById($id)
    {
        return $this->sysApp['socialNetwork.posts.repository']->readById($id);
    }

    public function updateById($id, array $data)
    {
        return $this->sysApp['socialNetwork.posts.repository']->updateById($id, $data);
    }

    public function deleteById($id)
    {
        return $this->sysApp['socialNetwork.posts.repository']->deleteById($id);
    }

    public function createCommentToId($postId, $data)
    {
        return $this->sysApp['socialNetwork.posts.repository']->createCommentToId(
            $postId,
            $data
        );
    }

    public function readCommentsById($postId)
    {
        return $this->sysApp['socialNetwork.posts.repository']->readCommentsById(
            $postId
        );
    }
}