<?php

namespace Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractCrudModel;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractRepositoryCrud;
use Hatch\Inspections\Model\Entities\WorkOrder\WorkOrderRepositoryInterface;
use Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\ActiveRecord as WorkOrderActiveRecord;
use Purekid\Mongodm\Collection;

/**
 * Class Repository
 *
 * @package Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM
 */
class Repository extends AbstractRepositoryCrud implements WorkOrderRepositoryInterface
{
    protected $primaryCrudModel = 'inspections.workOrder.model';

    public function getPrimaryFilter()
    {
        return $this->sysApp['inspections.workOrder.filter'];
    }

    public function getPrimaryNormalizer()
    {
        return $this->sysApp['inspections.workOrder.normalizer'];
    }

    public function readCommentsById($id)
    {
        $primaryFilter = $this->getPrimaryFilter();
        $primaryModel = $this->sysApp[$this->primaryCrudModel];

        /** @var WorkOrderActiveRecord $workOrder */
        $workOrder = $primaryModel->readOne(['_id' => $primaryFilter->formatId($id)]);
        $comments = $workOrder->getComments();

        $commentNormalizer = $this->sysApp['inspections.workOrder.comment.normalizer'];

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

        /** @var WorkOrderActiveRecord $workOrder */
        $workOrder = $primaryModel->readOne(
            ['_id' => $primaryFilter->formatId($id)]
        );
        /** @var Collection $comments */
        $comments = $workOrder->getComments();

        $comment = $this->sysApp['inspections.workOrder.comment.activeRecord'];
        $comment->update($data);

        $comments->add($comment);
        $workOrder->setComments($comments);
        $primaryModel->updateOrCreate($workOrder, []);

        $commentNormalizer = $this->sysApp['inspections.workOrder.comment.normalizer'];

        return $this->response(
            200,
            [],
            $this->buildFetchedData($comment, $commentNormalizer)
        );
    }
}
