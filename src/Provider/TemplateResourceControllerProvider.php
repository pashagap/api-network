<?php

namespace Hatch\Inspections\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

/**
 * Class TemplateResourceControllerProvider
 *
 * @package Hatch\Inspections\Provider
 */
class TemplateResourceControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/report/template/simple', 'inspections.template.webAction:readSimpleTemplateReport')
            ->before(
                'inspections.template.middleware.requestValidator:validateReadSimpleTemplateReport'
            );

        $controllers->put('/{id}', 'inspections.template.webAction:updateById')
            ->before(
                'inspections.template.middleware.requestValidator:validateUpdateRequest'
            );

        return $controllers;
    }
}
