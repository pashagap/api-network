<?php

namespace Hatch\Inspections\Provider;

use Hatch\Inspections\Controller\AnswerController;
use Hatch\Inspections\Controller\InspectionController;
use Hatch\Inspections\Controller\TemplateController;
use Hatch\Inspections\Controller\WorkOrderController;
use Hatch\Inspections\Middleware\AnswerRequestValidator;
use Hatch\Inspections\Middleware\InspectionRequestValidator;
use Hatch\Inspections\Middleware\TemplateRequestValidator;
use Hatch\Inspections\Middleware\WorkOrderRequestValidator;
use Hatch\Inspections\Model\Entities\Inspection\InspectionState;
use Hatch\Inspections\Model\Entities\Question\QuestionOutcomeType;
use Hatch\Inspections\Model\Entities\Question\QuestionType;
use Hatch\Inspections\Model\Entities\TemplateVersion\TemplateVersionPublishState;
use Hatch\Inspections\Controller\TemplateVersionController;
use Hatch\Inspections\Middleware\TemplateVersionRequestValidator;
use Hatch\Inspections\Model\Entities\WorkOrder\WorkOrderState;
use Hatch\Inspections\Model\Services\StatisticService;
use Hatch\Inspections\WebAction\AnswerWebAction;
use Hatch\Inspections\WebAction\InspectionWebAction;
use Hatch\Inspections\WebAction\TemplateVersionWebAction;
use Hatch\Inspections\WebAction\TemplateWebAction;
use Hatch\Inspections\WebAction\WorkOrderWebAction;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class InspectionsServiceProvider
 *
 * @package Hatch\Inspections\Provider
 */
class InspectionsServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $this->registerCommon($app);
        $this->registerMiddlewares($app);
        $this->registerWebActions($app);
        $this->registerControllers($app);
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }

    public function registerCommon(Application $app)
    {
        $app['inspections.templateVersion.state'] = $app->share(
            function () {
                return new TemplateVersionPublishState();
            }
        );
        $app['inspections.question.outcomeType'] = $app->share(
            function () {
                return new QuestionOutcomeType();
            }
        );
        $app['inspections.question.type'] = $app->share(
            function () {
                return new QuestionType();
            }
        );
        $app['inspections.workOrder.state'] = $app->share(
            function () {
                return new WorkOrderState();
            }
        );
        $app['inspections.inspection.state'] = $app->share(
            function () {
                return new InspectionState();
            }
        );

        $app['inspections.service.statisticService'] = $app->share(
            function () {
                return new StatisticService();
            }
        );
    }

    public function registerMiddlewares(Application $app)
    {
        $app['inspections.workOrder.middleware.requestValidator'] = $app->share(
            function () {
                return new WorkOrderRequestValidator();
            }
        );

        $app['inspections.answer.middleware.requestValidator'] = $app->share(
            function () {
                return new AnswerRequestValidator();
            }
        );

        $app['inspections.inspection.middleware.requestValidator'] = $app->share(
            function () {
                return new InspectionRequestValidator();
            }
        );

        $app['inspections.templateVersion.middleware.requestValidator'] = $app->share(
            function () {
                return new TemplateVersionRequestValidator();
            }
        );

        $app['inspections.template.middleware.requestValidator'] = $app->share(
            function () {
                return new TemplateRequestValidator();
            }
        );
    }

    public function registerWebActions(Application $app)
    {
        $app['inspections.workOrder.webAction'] = $app->share(
            function () {
                return new WorkOrderWebAction();
            }
        );

        $app['inspections.answer.webAction'] = $app->share(
            function () {
                return new AnswerWebAction();
            }
        );

        $app['inspections.inspection.webAction'] = $app->share(
            function () {
                return new InspectionWebAction();
            }
        );

        $app['inspections.templateVersion.webAction'] = $app->share(
            function () {
                return new TemplateVersionWebAction();
            }
        );

        $app['inspections.template.webAction'] = $app->share(
            function () {
                return new TemplateWebAction();
            }
        );
    }

    public function registerControllers(Application $app)
    {
        $app['inspections.workOrder.controller'] = $app->share(
            function () {
                return new WorkOrderController();
            }
        );

        $app['inspections.answer.controller'] = $app->share(
            function () {
                return new AnswerController();
            }
        );

        $app['inspections.inspection.controller'] = $app->share(
            function () {
                return new InspectionController();
            }
        );

        $app['inspections.templateVersion.controller'] = $app->share(
            function () {
                return new TemplateVersionController();
            }
        );

        $app['inspections.template.controller'] = $app->share(
            function () {
                return new TemplateController();
            }
        );
    }
}
