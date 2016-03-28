<?php

namespace Hatch\Inspections\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

/**
 * Class AnswerControllerProvider
 *
 * @package Hatch\Inspections\Provider
 */
class AnswerControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'inspections.answer.webAction:read');

        $controllers->get('/{id}', 'inspections.answer.webAction:readById');

        $controllers->put('/{id}', 'inspections.answer.webAction:updateById')
            ->before('inspections.answer.middleware.requestValidator:validateUpdateRequest');

        $controllers->post('/', 'inspections.answer.webAction:create')
            ->before('inspections.answer.middleware.requestValidator:validateCreateRequest');

        return $controllers;
    }
}
