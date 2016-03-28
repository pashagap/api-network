<?php

namespace Hatch\Inspections\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

/**
 * Class WorkOrderControllerProvider
 *
 * @package Hatch\Inspections\Provider
 */
class WorkOrderControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'inspections.workOrder.webAction:read');
        $controllers->get('/info/dashboard', 'inspections.workOrder.webAction:readDashboard');
        $controllers->get('/{id}', 'inspections.workOrder.webAction:readById');
        $controllers->get(
            '/{workOrderId}/comment',
            'inspections.workOrder.webAction:readCommentsByWorkOrderId'
        );

        $controllers->post('/', 'inspections.workOrder.webAction:create')
            ->before('inspections.workOrder.middleware.requestValidator:validateCreateRequest');
        $controllers->post(
            '/{workOrderId}/comment',
            'inspections.workOrder.webAction:createCommentToWorkOrderById'
        )->before('inspections.workOrder.middleware.requestValidator:createCommentToWorkOrderById');

        $controllers->put('/{id}', 'inspections.workOrder.webAction:updateById')
            ->before('inspections.workOrder.middleware.requestValidator:validateUpdateRequest');

        return $controllers;
    }
}
