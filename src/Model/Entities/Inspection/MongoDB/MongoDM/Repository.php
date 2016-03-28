<?php

namespace Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractCrudModel;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractRepositoryCrud;
use Hatch\Inspections\Model\Entities\Inspection\InspectionRepositoryInterface;
use Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\ActiveRecord as InspectionActiveRecord;
use Purekid\Mongodm\Collection;

/**
 * Class Repository
 *
 * @package Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM
 */
class Repository extends AbstractRepositoryCrud implements InspectionRepositoryInterface
{
    protected $primaryCrudModel = 'inspections.inspection.model';

    public function getPrimaryFilter()
    {
        return $this->sysApp['inspections.inspection.filter'];
    }

    public function getPrimaryNormalizer()
    {
        return $this->sysApp['inspections.inspection.normalizer'];
    }

    public function readCommentsById($id)
    {
        $primaryFilter = $this->getPrimaryFilter();
        $primaryModel = $this->sysApp[$this->primaryCrudModel];

        /** @var InspectionActiveRecord $inspection */
        $inspection = $primaryModel->readOne(['_id' => $primaryFilter->formatId($id)]);
        $comments = $inspection->getComments();

        $commentNormalizer = $this->sysApp['inspections.inspection.comment.normalizer'];

        return $this->response(
            200,
            [],
            $this->buildFetchedData($comments, $commentNormalizer)
        );
    }

    public function createCommentToId($id, array $data = [])
    {
        $primaryFilter = $this->getPrimaryFilter();
        /** @var AbstractCrudModel $primaryModel */
        $primaryModel = $this->sysApp[$this->primaryCrudModel];

        /** @var InspectionActiveRecord $inspection */
        $inspection = $primaryModel->readOne(
            ['_id' => $primaryFilter->formatId($id)]
        );
        /** @var Collection $comments */
        $comments = $inspection->getComments();

        $comment = $this->sysApp['inspections.inspection.comment.activeRecord'];
        $comment->update($data);

        $comments->add($comment);
        $inspection->setComments($comments);
        $primaryModel->updateOrCreate($inspection, []);

        $commentNormalizer = $this->sysApp['inspections.inspection.comment.normalizer'];

        return $this->response(
            200,
            [],
            $this->buildFetchedData($comment, $commentNormalizer)
        );
    }
}
