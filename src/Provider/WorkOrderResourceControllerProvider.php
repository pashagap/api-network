<?php

namespace Hatch\Inspections\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * Class WorkOrderResourceControllerProvider
 *
 * @package Hatch\Inspections\Provider
 */
class WorkOrderResourceControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get(
            '/inspection/{inspectionId}/workOrder',
            'inspections.workOrder.webAction:readByInspectionId'
        );
        $controllers->get(
            '/templateVersion/{templateVersionId}/workOrder',
            'inspections.workOrder.webAction:readByTemplateVersionId'
        );

        $controllers->get(
            '/report/workOrder/simple',
            'inspections.workOrder.webAction:readSimpleWorkOrderReport'
        )->before(
            'inspections.workOrder.middleware.requestValidator:validateReadSimpleWorkOrderReport'
        );

        return $controllers;
    }
}
