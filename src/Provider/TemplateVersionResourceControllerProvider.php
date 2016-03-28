<?php

namespace Hatch\Inspections\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * Class TemplateVersionResourceControllerProvider
 *
 * @package Hatch\Inspections\Provider
 */
class TemplateVersionResourceControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get(
            '/report/templateVersion/simple',
            'inspections.templateVersion.webAction:readSimpleTemplateVersionReport'
        )->before(
            'inspections.templateVersion.middleware.requestValidator:validateReadSimpleTemplateVersionReport'
        );

        $controllers->get(
            '/template/{templateId}/templateVersion',
            'inspections.templateVersion.webAction:readByTemplateId'
        );

        return $controllers;
    }
}
