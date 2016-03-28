<?php

namespace Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM;

use Hatch\Core\Event\EventArgs;
use Hatch\Core\Event\EventHandlerContainer;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractCrudModel;
use Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM\Model as AnswerModel;
use Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM\ActiveRecord as AnswerActiveRecord;
use Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\Model as WorkOrderModel;
use Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\ActiveRecord as WorkOrderActiveRecord;
use Hatch\Inspections\Model\Entities\WorkOrder\WorkOrderState;

/**
 * Class Model
 *
 * @package Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM
 */
class Model extends AbstractCrudModel
{
    protected $primaryActiveRecord = 'inspections.inspection.activeRecord';
    protected $modelName = 'inspection';

    public function initialize()
    {
        /** @var AnswerModel $answerModel */
        $answerModel = $this->sysApp['inspections.answer.model'];
        /** @var WorkOrderModel $workOrderModel */
        $workOrderModel = $this->sysApp['inspections.workOrder.model'];

        $answerModel->attach(
            'afterCreate',
            new EventHandlerContainer(
                $this, 'afterCreateAnswerEventHandler'
            )
        );

        $workOrderModel->attach(
            'afterCreate',
            new EventHandlerContainer(
                $this, 'afterCreateWorkOrderEventHandler'
            )
        );

        $workOrderModel->attach(
            'afterUpdate',
            new EventHandlerContainer(
                $this, 'afterUpdateWorkOrderEventHandler'
            )
        );
    }

    public function afterCreateAnswerEventHandler($sender, EventArgs $eventArgs)
    {
        /** @var AnswerActiveRecord $answer */
        $answer = $eventArgs->data['response']['data'];

        $inspection = $answer->getInspection();
        $inspection->incTotalAnswers();
        $this->updateOrCreate($inspection, []);
    }

    public function afterCreateWorkOrderEventHandler($sender, EventArgs $eventArgs)
    {
        /** @var WorkOrderActiveRecord $workOrder */
        $workOrder = $eventArgs->data['response']['data'];

        /** @var WorkOrderState $workOrderState */
        $workOrderState = $this->sysApp['inspections.workOrder.state'];
        $inspection = $workOrder->getInspection();
        if ($workOrderState::COMPLETED !== $workOrder->getState()
        ) {
            $inspection->incTotalFailedWO();
        }
        $inspection->incTotalWO();
        $this->updateOrCreate($inspection, []);
    }

    public function afterUpdateWorkOrderEventHandler($sender, EventArgs $eventArgs)
    {
        /** @var WorkOrderActiveRecord $workOrder */
        $workOrder = $eventArgs->data['response']['data'];
        $inspection = $workOrder->getInspection();

        /** @var WorkOrderState $workOrderState */
        $workOrderState = $this->sysApp['inspections.workOrder.state'];
        if ($workOrder->stateChanged()
            && $workOrderState::COMPLETED !== $workOrder->getState()
        ) {
            $inspection->incTotalFailedWO();
        } else {
            $inspection->decTotalFailedWO();
        }

        $this->updateOrCreate($inspection, []);
    }
}
