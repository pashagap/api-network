<?php
namespace Hatch\Inspections\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * Class AnswerResourceControllerProvider
 *
 * @package Hatch\Inspections\Provider
 */
class AnswerResourceControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get(
            '/inspection/{inspectionId}/answer',
            'inspections.answer.webAction:readByInspectionId'
        );
        return $controllers;
    }
}
