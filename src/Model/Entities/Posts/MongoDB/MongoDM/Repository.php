<?php

namespace Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractCrudModel;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractRepositoryCrud;
use Hatch\SocialNetwork\Model\Entities\Posts\PostsRepositoryInterface;
use Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM\ActiveRecord as PostsActiveRecord;
use Purekid\Mongodm\Collection;

/**
 * Class Repository
 *
 * @package Hatch\SocialNetwork\Model\Entities\Posts\MongoDB\MongoDM
 */
class Repository extends AbstractRepositoryCrud implements PostsRepositoryInterface
{
    protected $primaryCrudModel = 'socialNetwork.posts.model';

    public function getPrimaryFilter()
    {
        return $this->sysApp['socialNetwork.posts.filter'];
    }

    public function getPrimaryNormalizer()
    {
        return $this->sysApp['socialNetwork.posts.normalizer'];
    }

    public function createCommentToId($id, array $data = [])
    {
        $primaryFilter = $this->getPrimaryFilter();
        /** @var AbstractCrudModel $primaryModel */
        $primaryModel = $this->sysApp[$this->primaryCrudModel];

        /** @var PostsActiveRecord $post */
        $post = $primaryModel->readOne(
            ['_id' => $primaryFilter->formatId($id)]
        );
        /** @var Collection $comments */
        $comments = $post->getComments();

        $comment = $this->sysApp['socialNetwork.posts.comments.activeRecord'];
        $comment->update($data);

        $comments->add($comment);
        $post->setComments($comments);
        $primaryModel->updateOrCreate($post, []);

        $commentNormalizer = $this->sysApp['socialNetwork.posts.comments.normalizer'];

        return $this->response(
            200,
            [],
            $this->buildFetchedData($comment, $commentNormalizer)
        );
    }

    public function readCommentsById($id)
    {
        $primaryFilter = $this->getPrimaryFilter();
        $primaryModel = $this->sysApp[$this->primaryCrudModel];

        /** @var PostsActiveRecord $post */
        $post = $primaryModel->readOne(['_id' => $primaryFilter->formatId($id)]);
        $comments = $post->getComments();

        $commentNormalizer = $this->sysApp['socialNetwork.posts.comments.normalizer'];

        return $this->response(
            200,
            [],
            $this->buildFetchedData($comments, $commentNormalizer)
        );
    }
}