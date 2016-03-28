<?php

namespace Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM;

use Hatch\Core\Event\EventArgs;
use Hatch\Core\Event\EventHandlerContainer;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractCrudModel;
use Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\Model as TemplateVersionModel;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractActiveRecord;
use Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM\ActiveRecord as TemplateActiveRecord;
use Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\ActiveRecord as TemplateVersionActiveRecord;

/**
 * Class Model
 *
 * @package Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM
 */
class Model extends AbstractCrudModel
{
    protected $primaryActiveRecord = 'inspections.template.activeRecord';
    protected $modelName = 'inspection template';

    public function unsetPublishedVersionById($templateId)
    {
        /** @var TemplateVersionModel $templateVersionModel */
        $templateVersionModel = $this->sysApp['inspections.templateVersion.model'];

        /** @var AbstractActiveRecord $publishedTemplateVersion */
        $publishedVersion = $templateVersionModel->readPublishedOfTemplate(
            $templateId
        );

        $templateVersionModel->unpublish($publishedVersion);
    }

    public function initialize()
    {
        /** @var TemplateVersionModel $templateModel */
        $templateModel = $this->sysApp['inspections.templateVersion.model'];

        $templateModel->attach(
            'afterSave',
            new EventHandlerContainer($this, 'afterSaveTemplateVersionHandler')
        );
    }

    public function afterSaveTemplateVersionHandler($sender, EventArgs $eventArgs)
    {
        /** @var TemplateVersionActiveRecord $templateVersion */
        $templateVersion = $eventArgs->data['response']['data'];
        /** @var TemplateActiveRecord $template */
        $template = $templateVersion->getTemplate();

        $template->setModifiedDate($templateVersion->getModifiedDate());
        $template->setModifiedBy($templateVersion->getModifiedBy());

        $this->updateOrCreate($template, []);
    }

}
