<?php

namespace Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM;

use Hatch\Core\Event\EventArgs;
use Hatch\Core\Event\EventHandlerContainer;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractCrudModel;
use Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\Model as InspectionModel;
use Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\ActiveRecord as InspectionActiveRecord;

/**
 * Class Model
 *
 * @package Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM
 */
class Model extends AbstractCrudModel
{
    protected $primaryActiveRecord = 'inspections.answer.activeRecord';
    protected $modelName = 'inspection answer';

    public function initialize()
    {
        /** @var InspectionModel $inspectionModel */
        $inspectionModel = $this->sysApp['inspections.inspection.model'];

        $inspectionModel->attach(
            'afterDelete',
            new EventHandlerContainer($this, 'afterDeleteInspectionEventHandler')
        );
    }

    public function afterDeleteInspectionEventHandler($sender, EventArgs $eventArgs)
    {
        /** @var InspectionActiveRecord $deletedInspection */
        $deletedInspection = $eventArgs->data['response']['data'];

        $this->delete(['inspection.$id' => $deletedInspection->getId()]);
    }
}
