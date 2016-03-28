<?php

namespace Hatch\Inspections;

use Hatch\Core\AbstractInitialization;
use Hatch\Core\ResourceLoaderInterface;
use Hatch\Inspections\Provider\AnswerControllerProvider;
use Hatch\Inspections\Provider\AnswerResourceControllerProvider;
use Hatch\Inspections\Provider\InspectionControllerProvider;
use Hatch\Inspections\Provider\InspectionResourceControllerProvider;
use Hatch\Inspections\Provider\InspectionsModelServiceProvider;
use Hatch\Inspections\Provider\InspectionsServiceProvider;
use Hatch\Inspections\Provider\TemplateControllerProvider;
use Hatch\Inspections\Provider\TemplateResourceControllerProvider;
use Hatch\Inspections\Provider\TemplateVersionControllerProvider;
use Hatch\Inspections\Provider\TemplateVersionResourceControllerProvider;
use Hatch\Inspections\Provider\WorkOrderControllerProvider;
use Hatch\Inspections\Provider\WorkOrderResourceControllerProvider;
use InspectionStatsMcs\InspStatsMcsSilexProvider;

/**
 * Class Initialization
 *
 * @package Hatch\Inspections
 */
final class Initialization extends AbstractInitialization
{
    public function init()
    {
        $this->sysApp->register(new InspectionsServiceProvider());
        $this->sysApp->register(new InspectionsModelServiceProvider());
        $this->registerStatisticServiceTool();

        $this->sysApp->mount(
            '/templateVersion',
            new TemplateVersionControllerProvider()
        );

        $this->sysApp->mount(
            '/template',
            new TemplateControllerProvider()
        );

        $this->sysApp->mount(
            '/inspection',
            new InspectionControllerProvider()
        );

        $this->sysApp->mount(
            '/answer',
            new AnswerControllerProvider()
        );

        $this->sysApp->mount(
            '/workOrder',
            new WorkOrderControllerProvider()
        );

        $this->sysApp->mount(
            '/',
            new AnswerResourceControllerProvider()
        );

        $this->sysApp->mount(
            '/',
            new TemplateVersionResourceControllerProvider()
        );

        $this->sysApp->mount(
            '/',
            new InspectionResourceControllerProvider()
        );

        $this->sysApp->mount(
            '/',
            new WorkOrderResourceControllerProvider()
        );

        $this->sysApp->mount(
            '/',
            new TemplateResourceControllerProvider()
        );
    }

    /**
     * Function That handle to boot module
     *
     * @return mixed
     */
    public function boot()
    {
        $this->sysApp['inspections.inspection.model']->initialize();
        $this->sysApp['inspections.answer.model']->initialize();
        $this->sysApp['inspections.workOrder.model']->initialize();
        $this->sysApp['inspections.template.model']->initialize();
    }

    /**
     * @return array
     */
    protected function getControllerPaths()
    {
        return glob(__DIR__.'/Controller/*');
    }

    protected function registerStatisticServiceTool()
    {
        /** @var ResourceLoaderInterface $configLoader */
        $configLoader = $this->sysApp['application.configLoader'];
        $config = $configLoader->getResource('INSPECTIONS');

        $this->sysApp->register(
            new InspStatsMcsSilexProvider,
            [
                'hatch-is.insp-stats-mcs.endpoint' => $config['INSP_STATS_MCS_ENDPOINT']
            ]
        );
    }
}
