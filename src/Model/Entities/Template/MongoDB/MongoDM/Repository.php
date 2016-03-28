<?php

namespace Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM;

use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractRepositoryCrud;
use Hatch\Inspections\Model\Entities\Template\TemplateRepositoryInterface;
use Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM\Model as TemplateModel;

/**
 * Class Repository
 *
 * @package Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM
 */
class Repository extends AbstractRepositoryCrud implements TemplateRepositoryInterface
{
    protected $primaryCrudModel = 'inspections.template.model';

    public function getPrimaryFilter()
    {
        return $this->sysApp['inspections.template.filter'];
    }

    public function getPrimaryNormalizer()
    {
        return $this->sysApp['inspections.template.normalizer'];
    }

    public function unsetPublishedVersionById($templateId)
    {
        $primaryFiler = $this->getPrimaryFilter();
        $templateId = $primaryFiler->formatId($templateId);

        /** @var TemplateModel $primaryModel */
        $primaryModel = $this->sysApp[$this->primaryCrudModel];
        $primaryModel->unsetPublishedVersionById($templateId);
    }
}
