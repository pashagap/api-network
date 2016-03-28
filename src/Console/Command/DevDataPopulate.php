<?php

namespace Hatch\Inspections\Console\Command;

use Hatch\Core\Console\Command\AbstractCommand;
use Hatch\Core\Model\Entities\User\UserRepositoryInterface;
use Hatch\Inspections\Model\Entities\Answer\AnswerRepositoryInterface;
use Hatch\Inspections\Model\Entities\Inspection\InspectionRepositoryInterface;
use Hatch\Inspections\Model\Entities\Question\QuestionOutcomeType;
use Hatch\Inspections\Model\Entities\Question\QuestionType;
use Hatch\Inspections\Model\Entities\Template\TemplateRepositoryInterface;
use Hatch\Inspections\Model\Entities\TemplateVersion\TemplateVersionPublishState;
use Hatch\Inspections\Model\Entities\TemplateVersion\TemplateVersionRepositoryInterface;
use Hatch\Inspections\Model\Entities\WorkOrder\WorkOrderRepositoryInterface;
use Hatch\Inspections\Model\Entities\WorkOrder\WorkOrderState;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SignIn
 *
 * @package Hatch\Core\Console\Command
 */
class DevDataPopulate extends AbstractCommand
{
    protected $currentUser;
    protected $currentLocation;

    protected function configure()
    {
        $this->setDescription('Populate inspections data');
    }

    public function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->initialize($input, $output);

        $this->bindCurrentUser();
        $this->bindCurrentLocation();

        $templateArrayData = $this->createTemplate();
        $this->writeAction(
            sprintf(
                'Template with name %s has been created',
                $templateArrayData['name']
            )
        );

        /** @var TemplateVersionPublishState $templateVersionState */
        $templateVersionState = $this->sysApp['inspections.templateVersion.state'];

        $unpublishedVersion = $this->createTemplateVersion(
            $templateArrayData['id'],
            $templateVersionState::PUBLISHED,
            $this->generateQuestions(0, 10)
        );
        $this->writeAction(
            sprintf(
                'Unpublished template version with version %s has been created',
                $unpublishedVersion['name']
            )
        );

        $publishedVersion = $this->createTemplateVersion(
            $templateArrayData['id'],
            $templateVersionState::PUBLISHED,
            $this->generateQuestions(5, 15)
        );
        $this->writeAction(
            sprintf(
                'Published template version with version %s has been created',
                $publishedVersion['name']
            )
        );

        $draftVersion = $this->createTemplateVersion(
            $templateArrayData['id'],
            $templateVersionState::DRAFT,
            $this->generateQuestions(10, 15)
        );
        $this->writeAction(
            sprintf(
                'Draft template version with version %s has been created',
                $draftVersion['name']
            )
        );

        $this->writeAction('Creation inspections for published version...');

        $this->createInspections($publishedVersion);
    }

    public function bindCurrentUser()
    {
        /** @var UserRepositoryInterface $userRepo */
        $userRepo = $this->sysApp['core.user.repository'];

        $userResponseData = $userRepo->readOne();
        $this->sysApp->bindCurrentUser($userResponseData['response']['data']);
    }

    public function bindCurrentLocation()
    {
        $currentUser = $this->sysApp->getCurrentUser();

        if (empty($currentUser['locations'])) {
            $this->writeAction('Cannot bind any user location');
            die;
        }

        $this->sysApp->bindCurrentLocation($currentUser['locations'][0]);
    }

    public function createTemplate()
    {
        /** @var TemplateRepositoryInterface $templateRepo */
        $templateRepo = $this->sysApp['inspections.template.repository'];
        $currentLocation = $this->sysApp->getCurrentLocation();

        $templateResponseData = $templateRepo->create(
            [
                'name' => 'Template1',
                'description' => 'This is a test template',
                'tags' => ['test', 'tags', 'feature'],
                'location' => $currentLocation['id']
            ]
        );

        return $templateResponseData['response']['data'];
    }

    public function createTemplateVersion($templateVersionId, $state, $questions)
    {
        /** @var TemplateVersionRepositoryInterface $templateVersionRepo */
        $templateVersionRepo = $this->sysApp['inspections.templateVersion.repository'];
        $currentLocation = $this->sysApp->getCurrentLocation();

        $templateVersion = $templateVersionRepo->create([
            'template' => $templateVersionId,
            'state' => $state,
            'questions' => $questions,
            'location' => $currentLocation['id']
        ]);

        return $templateVersion['response']['data'];
    }

    public function generateQuestions($from, $count)
    {
        /** @var QuestionType $questionType */
        $questionType = $this->sysApp['inspections.question.type'];
        $questions = [];

        for ($i = $from; $i < $count; $i++) {
            $question = [
                'question' => sprintf('question #%s', $i),
                'type' => $questionType::EMPTY_CONTENT
            ];

            array_push($questions, $question);
        }

        return $questions;
    }

    public function createInspections($publishedVersion)
    {
        $this->writeAction('Creating InProgress inspection...');
        $inspection = $this->createInProgressInspection($publishedVersion);
        $this->writeAction(
            sprintf(
                'InProgress inspection %s has been created',
                $inspection['id']
            )
        );

        $this->writeAction('Creating Completed inspection...');
        $inspection = $this->createCompletedInspection($publishedVersion);
        $this->writeAction(
            sprintf(
                'Completed inspection %s has been created',
                $inspection['id']
            )
        );

        $this->writeAction('Creating Failed inspection...');
        $inspection = $this->createFailedInspection($publishedVersion);
        $this->writeAction(
            sprintf(
                'Failed inspection %s has been created',
                $inspection['id']
            )
        );
    }

    public function createInProgressInspection($version)
    {
        /** @var InspectionRepositoryInterface $inspectionRepo */
        $inspectionRepo = $this->sysApp['inspections.inspection.repository'];

        $currentLocation = $this->sysApp->getCurrentLocation();
        $inspectionResponse = $inspectionRepo->create([
            'templateVersion' => $version['id'],
            'location' => $currentLocation['id']
        ]);

        $questions = $inspectionResponse['response']['data']['templateVersion']['questions'];

        /** @var AnswerRepositoryInterface $answersRepo */
        $answersRepo = $this->sysApp['inspections.answer.repository'];
        /** @var QuestionOutcomeType $questionOutcome */
        $questionOutcome = $this->sysApp['inspections.question.outcomeType'];

        $i = 0;
        $max = 5;
        foreach($questions as $questionId) {
            $rand = rand(0, 1);
            switch ($rand) {
                case 0: {
                    $outcome = $questionOutcome::NOT_APPLICABLE;
                    break;
                }
                default: {
                    $outcome = $questionOutcome::PASS;
                    break;
                }
            }
            $answersRepo->create(
                [
                    'question' => $questionId,
                    'inspection' => $inspectionResponse['response']['data']['id'],
                    'outcome' => $outcome,
                    'location' => $currentLocation['id']
                ]
            );


            $i++;
            if($i > $max) {
                break;
            }
        }
    }

    public function createCompletedInspection($version)
    {
        /** @var InspectionRepositoryInterface $inspectionRepo */
        $inspectionRepo = $this->sysApp['inspections.inspection.repository'];

        $currentLocation = $this->sysApp->getCurrentLocation();
        $inspectionResponse = $inspectionRepo->create([
            'templateVersion' => $version['id'],
            'location' => $currentLocation['id']
        ]);

        $questions = $inspectionResponse['response']['data']['templateVersion']['questions'];

        /** @var AnswerRepositoryInterface $answersRepo */
        $answersRepo = $this->sysApp['inspections.answer.repository'];
        /** @var QuestionOutcomeType $questionOutcome */
        $questionOutcome = $this->sysApp['inspections.question.outcomeType'];

        foreach($questions as $questionId) {
            $rand = rand(0, 1);
            switch ($rand) {
                case 0: {
                    $outcome = $questionOutcome::NOT_APPLICABLE;
                    break;
                }
                default: {
                    $outcome = $questionOutcome::PASS;
                    break;
                }
            }
            $answersRepo->create(
                [
                    'question' => $questionId,
                    'inspection' => $inspectionResponse['response']['data']['id'],
                    'outcome' => $outcome,
                    'location' => $currentLocation['id']
                ]
            );
        }
    }

    public function createFailedInspection($version)
    {
        /** @var InspectionRepositoryInterface $inspectionRepo */
        $inspectionRepo = $this->sysApp['inspections.inspection.repository'];

        $currentLocation = $this->sysApp->getCurrentLocation();
        $inspectionResponse = $inspectionRepo->create([
            'templateVersion' => $version['id'],
            'location' => $currentLocation['id']
        ]);

        $questions = $inspectionResponse['response']['data']['templateVersion']['questions'];

        /** @var AnswerRepositoryInterface $answersRepo */
        $answersRepo = $this->sysApp['inspections.answer.repository'];
        /** @var QuestionOutcomeType $questionOutcome */
        $questionOutcome = $this->sysApp['inspections.question.outcomeType'];
        /** @var WorkOrderRepositoryInterface $workOrderRepo */
        $workOrderRepo = $this->sysApp['inspections.workOrder.repository'];
        /** @var WorkOrderState $workOrderState */
        $workOrderState = $this->sysApp['inspections.workOrder.state'];

        $i = 0;
        $max = 3;
        foreach($questions as $questionId) {

            $answerResponse = $answersRepo->create(
                [
                    'question' => $questionId,
                    'inspection' => $inspectionResponse['response']['data']['id'],
                    'outcome' => $questionOutcome::FAIL,
                    'location' => $currentLocation['id']
                ]
            );

            $currentLocation = $this->sysApp->getCurrentLocation();
            $currentUser = $this->sysApp->getCurrentUser();

            switch($i) {
                case 0: {
                    $state = $workOrderState::INCOMPLETE;
                    break;
                }
                case 1: {
                    $state = $workOrderState::STARTED;
                    break;
                }
                default: {
                    $state = $workOrderState::COMPLETED;
                    break;
                }

            }
            $workOrderRepo->create([
                'question' => $questionId,
                'inspection' => $inspectionResponse['response']['data']['id'],
                'answer' => $answerResponse['response']['data']['id'],
                'location' => $currentLocation['id'],
                'issue' => "issue #$i",
                'state' => $state,
                'assigned' => $currentUser['id']
            ]);


            $i++;
            if($i >= $max)
            {
                break;
            }
        }
    }
}
