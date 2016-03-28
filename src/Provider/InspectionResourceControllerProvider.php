<?php

namespace Hatch\Inspections\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * Class InspectionResourceControllerProvider
 *
 * @package Hatch\Inspections\Provider
 */
class InspectionResourceControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get(
            '/templateVersion/{templateVersionId}/inspection',
            'inspections.inspection.webAction:readByTemplateVersionId'
        );
        $controllers->get(
            '/report/inspection/{state}',
            'inspections.inspection.webAction:readInspectionReport'
        )->before(
            'inspections.inspection.middleware.requestValidator:validateInspectionReport'
        );

        return $controllers;
    }
}
