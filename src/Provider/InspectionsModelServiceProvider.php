<?php

namespace Hatch\Inspections\Provider;

use Hatch\Core\Exception\Application\InvalidConfigException;
use Hatch\Core\Provider\AbstractModelServiceProvider;
use Silex\Application;

/**
 * Class InspectionsModelServiceProvider
 *
 * @package Hatch\Inspections\Provider
 */
class InspectionsModelServiceProvider extends AbstractModelServiceProvider
{
    protected function getInjectionList(Application $app)
    {
        $databaseConfig = $app['application.configLoader']->getResource(
            'DATABASE'
        );

        switch ($databaseConfig['db_type']) {
            case'mongodb':
                switch ($databaseConfig['tool_type']) {
                    case 'mongodm' :
                        return $this->getInjectionListForMongoDBMongoDM();
                }
        }

        throw new InvalidConfigException(
            'Model hasn\'t been loaded in singleInvoice module'
        );
    }

    private function getInjectionListForMongoDBMongoDM()
    {
        $injectionList = [
            [
                'name' => 'inspections.template.activeRecord',
                'value' => 'Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM\ActiveRecord',
                'method' => 'ReCreate'
            ],
            [
                'name' => 'inspections.template.model',
                'value' => 'Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM\Model',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.template.repository',
                'value' => 'Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM\Repository',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.template.filter',
                'value' => 'Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM\Filter',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.template.normalizer',
                'value' => 'Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM\Normalizer',
                'method' => 'Single'
            ],


            [
                'name' => 'inspections.inspection.activeRecord',
                'value' => 'Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\ActiveRecord',
                'method' => 'ReCreate'
            ],
            [
                'name' => 'inspections.inspection.model',
                'value' => 'Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\Model',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.inspection.repository',
                'value' => 'Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\Repository',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.inspection.filter',
                'value' => 'Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\Filter',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.inspection.normalizer',
                'value' => 'Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\Normalizer',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.inspection.comment.activeRecord',
                'value' => 'Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\CommentActiveRecord',
                'method' => 'ReCreate'
            ],
            [
                'name' => 'inspections.inspection.comment.normalizer',
                'value' => 'Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\CommentNormalizer',
                'method' => 'Single'
            ],


            [
                'name' => 'inspections.answer.activeRecord',
                'value' => 'Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM\ActiveRecord',
                'method' => 'ReCreate'
            ],
            [
                'name' => 'inspections.answer.model',
                'value' => 'Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM\Model',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.answer.repository',
                'value' => 'Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM\Repository',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.answer.filter',
                'value' => 'Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM\Filter',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.answer.normalizer',
                'value' => 'Hatch\Inspections\Model\Entities\Answer\MongoDB\MongoDM\Normalizer',
                'method' => 'Single'
            ],


            [
                'name' => 'inspections.templateVersion.activeRecord',
                'value' => 'Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\ActiveRecord',
                'method' => 'ReCreate'
            ],
            [
                'name' => 'inspections.templateVersion.model',
                'value' => 'Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\Model',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.templateVersion.repository',
                'value' => 'Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\Repository',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.templateVersion.filter',
                'value' => 'Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\Filter',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.templateVersion.normalizer',
                'value' => 'Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\Normalizer',
                'method' => 'Single'
            ],


            [
                'name' => 'inspections.workOrder.activeRecord',
                'value' => 'Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\ActiveRecord',
                'method' => 'ReCreate'
            ],
            [
                'name' => 'inspections.workOrder.model',
                'value' => 'Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\Model',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.workOrder.repository',
                'value' => 'Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\Repository',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.workOrder.filter',
                'value' => 'Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\Filter',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.workOrder.normalizer',
                'value' => 'Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\Normalizer',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.workOrder.comment.activeRecord',
                'value' => 'Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\CommentActiveRecord',
                'method' => 'ReCreate'
            ],
            [
                'name' => 'inspections.workOrder.comment.normalizer',
                'value' => 'Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\CommentNormalizer',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.workOrder.stateHistory.activeRecord',
                'value' => 'Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\StateHistoryActiveRecord',
                'method' => 'ReCreate'
            ],
            [
                'name' => 'inspections.workOrder.stateHistory.normalizer',
                'value' => 'Hatch\Inspections\Model\Entities\WorkOrder\MongoDB\MongoDM\StateHistoryNormalizer',
                'method' => 'Single'
            ],


            [
                'name' => 'inspections.question.activeRecord',
                'value' => 'Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\ActiveRecord',
                'method' => 'ReCreate'
            ],
            [
                'name' => 'inspections.question.model',
                'value' => 'Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\Model',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.question.repository',
                'value' => 'Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\Repository',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.question.filter',
                'value' => 'Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\Filter',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.question.normalizer',
                'value' => 'Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\Normalizer',
                'method' => 'Single'
            ],
            [
                'name' => 'inspections.question.response.activeRecord',
                'value' => 'Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\QuestionResponseActiveRecord',
                'method' => 'ReCreate'
            ],
            [
                'name' => 'inspections.question.response.normalizer',
                'value' => 'Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\QuestionResponseNormalizer',
                'method' => 'Single'
            ]
        ];

        return $injectionList;
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

    }
}
