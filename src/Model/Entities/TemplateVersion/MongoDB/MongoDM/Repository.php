<?php

namespace Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractRepositoryCrud;
use Hatch\Inspections\Model\Entities\TemplateVersion\TemplateVersionRepositoryInterface;
use Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\Model as TemplateVersionModel;

/**
 * Class Repository
 *
 * @package Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM
 */
class Repository extends AbstractRepositoryCrud implements TemplateVersionRepositoryInterface
{
    protected $primaryCrudModel = 'inspections.templateVersion.model';

    public function getPrimaryFilter()
    {
        return $this->sysApp['inspections.templateVersion.filter'];
    }

    public function getPrimaryNormalizer()
    {
        return $this->sysApp['inspections.templateVersion.normalizer'];
    }

    public function erase()
    {
        /** @var TemplateVersionModel $templateVersionModel */
        $templateVersionModel = $this->sysApp[$this->primaryCrudModel];
        $templateVersionModel->allowLockedVersionUpdate();
        
        return parent::erase();
    }
}
