<?php

namespace Hatch\Inspections\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

/**
 * Class TemplateVersionControllerProvider
 *
 * @package Hatch\Inspections\Provider
 */
class TemplateVersionControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'inspections.templateVersion.webAction:read');
        $controllers->get('/{id}', 'inspections.templateVersion.webAction:readById');

        $controllers->post('/', 'inspections.templateVersion.webAction:create')
            ->before('inspections.templateVersion.middleware.requestValidator:validateCreateRequest');

        $controllers->put('/{id}', 'inspections.templateVersion.webAction:updateById')
            ->before(
                'inspections.templateVersion.middleware.requestValidator:validateUpdateRequest'
            );

        return $controllers;
    }
}
