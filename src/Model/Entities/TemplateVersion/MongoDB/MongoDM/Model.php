<?php

namespace Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM;

use Hatch\Core\Exception\Model\DataNotFoundException;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractActiveRecord;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractCrudModel;
use Hatch\Inspections\Model\Entities\TemplateVersion\TemplateVersionPublishState;

/**
 * Class Model
 *
 * @package Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM
 */
class Model extends AbstractCrudModel
{
    protected $primaryActiveRecord = 'inspections.templateVersion.activeRecord';
    protected $modelName = 'inspection template version';

    /**
     * @param \MongoId $templateId
     *
     * @return AbstractActiveRecord
     * @throws DataNotFoundException
     */
    public function readPublishedOfTemplate($templateId)
    {
        /** @var TemplateVersionPublishState $TemplateVersionPublishState */
        $publishState = $this->sysApp['inspections.templateVersion.state'];

        $publishedVersionCriteria = [
            'template.$id' => $templateId,
            'state' => $publishState::PUBLISHED
        ];

        try {
            return $this->readOne($publishedVersionCriteria);
        } catch (DataNotFoundException $e) {
            throw new DataNotFoundException(
                sprintf(
                    'There is no published version of template %s',
                    $templateId->{'$id'}
                ),
                400,
                $e
            );
        }
    }

    /**
     * @param AbstractActiveRecord $templateVersion
     */
    public function unpublish($templateVersion)
    {
        $this->allowLockedVersionUpdate();

        /** @var TemplateVersionPublishState $TemplateVersionPublishState */
        $publishState = $this->sysApp['inspections.templateVersion.state'];

        $this->updateOrCreate(
            $templateVersion,
            ['state' => $publishState::UNPUBLISHED]
        );
    }

    /**
     * @param \MongoId $versionId
     */
    public function unpublishById($versionId)
    {
        $this->unpublish(
            $this->readOne(['_id' => $versionId])
        );
    }

    public function allowLockedVersionUpdate()
    {
        $this->sysApp['inspections.templateVersion.model.updateLocked'] = false;
    }

    public function lockedVersionUpdateAllowed()
    {
        return isset($this->sysApp['inspections.templateVersion.model.updateLocked'])
            ? !$this->sysApp['inspections.templateVersion.model.updateLocked']
            : false;
    }
}
