<?php

namespace Hatch\Inspections\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

/**
 * Class InspectionControllerProvider
 *
 * @package Hatch\Inspections\Provider
 */
class InspectionControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'inspections.inspection.webAction:read');
        $controllers->get('/info/dashboard', 'inspections.inspection.webAction:readDashboard');
        $controllers->get('/info/stats', 'inspections.inspection.webAction:readStats');
        $controllers->get('/{id}', 'inspections.inspection.webAction:readById');
        $controllers->get(
            '/{inspectionId}/comment',
            'inspections.inspection.webAction:readCommentsByInspectionId'
        );

        $controllers->post('/', 'inspections.inspection.webAction:create')
            ->before('inspections.inspection.middleware.requestValidator:validateCreateRequest');
        $controllers->post(
            '/{inspectionId}/comment',
            'inspections.inspection.webAction:createCommentToInspectionById'
        )->before('inspections.inspection.middleware.requestValidator:createCommentToInspectionById');

        $controllers->put('/{id}', 'inspections.inspection.webAction:updateById')
            ->before(
                'inspections.inspection.middleware.requestValidator:validateUpdateRequest'
            );

        $controllers->delete('/{id}', 'inspections.inspection.webAction:deleteById');

        return $controllers;
    }
}
