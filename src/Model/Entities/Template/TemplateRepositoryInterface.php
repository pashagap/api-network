<?php

namespace Hatch\Inspections\Model\Entities\Template;

use Hatch\Core\Model\Common\RepositoryCrudInterface;

/**
 * Interface TemplateRepositoryInterface
 *
 * @package Hatch\Inspections\Model\Entities\Template
 */
interface TemplateRepositoryInterface extends RepositoryCrudInterface
{
    /**
     * @param mixed $templateId
     *
     * @return array
     */
    function unsetPublishedVersionById($templateId);
}
