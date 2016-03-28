<?php

namespace Hatch\Inspections\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

/**
 * Class TemplateControllerProvider
 *
 * @package Hatch\Inspections\Provider
 */
class TemplateControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'inspections.template.webAction:read');
        $controllers->get('/{id}', 'inspections.template.webAction:readById');

        $controllers->post('/', 'inspections.template.webAction:create')
            ->before('inspections.template.middleware.requestValidator:validateCreateRequest');

        $controllers->put('/{id}', 'inspections.template.webAction:updateById')
            ->before(
                'inspections.template.middleware.requestValidator:validateUpdateRequest'
            );

        return $controllers;
    }
}
