<?php

namespace Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM;

use Hatch\Core\Exception\ExpectedException;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractActiveRecord;
use Hatch\Core\Model\Entities\User\MongoDB\MongoDM\Model as UserModel;
use Hatch\Inspections\Model\Entities\Inspection\InspectionState;
use Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\ActiveRecord as TemplateVersionActiveRecord;
use Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM\ActiveRecord as TemplateActiveRecord;
use Hatch\Inspections\Model\Entities\TemplateVersion\TemplateVersionPublishState;
use Purekid\Mongodm\Collection;

class ActiveRecord extends AbstractActiveRecord
{
    protected $primaryModel = 'inspections.inspection.model';
    public static $collection = 'InspectionsInspection';

    public static $attrs
        = [
            'templateVersion' => [
                'model' => 'Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],

            'template' => [
                'model' => 'Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],

            'questions' => [
                'type' => 'references',
                'model' => 'Hatch\Inspections\Model\Entities\Question\MongoDB\MongoDM\ActiveRecord'
            ],

            'createdBy' => [
                'model' => 'Hatch\Core\Model\Entities\User\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],
            'createdDate' => ['type' => 'date'],

            'modifiedBy' => [
                'model' => 'Hatch\Core\Model\Entities\User\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],
            'modifiedDate' => ['type' => 'date'],

            'location' => [
                'model' => 'Hatch\Core\Model\Entities\Location\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],

            //inProgress, completed, failed
            'state' => ['type' => 'string', 'default' => 'inProgress'],

            'images' => [
                'model' => 'Hatch\Core\Model\Common\MongoDB\MongoDM\Image\ImageActiveRecord',
                'type' => 'embeds'
            ],
            'comments' => [
                'model' => 'Hatch\Inspections\Model\Entities\Inspection\MongoDB\MongoDM\CommentActiveRecord',
                'type' => 'embeds'
            ],

            'totalQuestions' => ['type' => 'int'],
            'totalAnswers' => ['type' => 'int'],
            'totalFailedWO' => ['type' => 'int'],
            'totalWO' => ['type' => 'int']
        ];

    protected function __preInsert()
    {
        if (!$this->templateVersionCorrect()) {
            throw new ExpectedException('Template version is not correct to create inspection');
        }

        $this->updateToCurrentDate('createdDate');
        $this->updateToCurrentUser('createdBy');

        $this->modifiedDate = $this->createdDate;
        $this->modifiedBy = $this->createdBy;

        $templateVersion = $this->getTemplateVersion();
        $questions = $templateVersion->getQuestions();

        $this->setTotalQuestions($questions->count());
        $this->setQuestions($templateVersion->getQuestions());
        $this->setTotalAnswers(0);
        $this->setTotalFailedWO(0);
        $this->setTotalWO(0);

        $this->setState(null);

        return parent::__preInsert();
    }

    protected function __preUpdate()
    {
        $this->updateToCurrentDate('modifiedDate');
        $this->updateToCurrentUser('modifiedBy');

        $this->setState(null);

        return parent::__preUpdate();
    }

    private function templateVersionCorrect()
    {
        $templateVersion = $this->getTemplateVersion();
        /** @var TemplateVersionPublishState $templateVersionState */
        $templateVersionState = $this->sysApp['inspections.templateVersion.state'];

        return $templateVersionState::PUBLISHED === $templateVersion->getState();
    }

    private function updateToCurrentDate($fieldName)
    {
        $currentDatetime = new \DateTime();
        $currentMongoDate = new \MongoDate($currentDatetime->getTimestamp());
        $this->{$fieldName} = $currentMongoDate;
    }

    private function updateToCurrentUser($fieldName)
    {
        /** @var UserModel $userModel */
        $userModel = $this->sysApp['core.user.model'];
        $currentUser = $this->sysApp->getCurrentUser();

        $user = $userModel->readOne(
            [
                '_id' => new \MongoId($currentUser['id'])
            ]
        );

        $this->{$fieldName} = $user;
    }

    private function incTotal($total)
    {
        $totalValue = $this->{$total};
        $totalValue = $totalValue ?: 0;
        $this->{$total} = ++$totalValue;
    }

    private function decTotal($total)
    {
        $totalValue = $this->{$total};
        $totalValue = $totalValue ?: 0;
        $this->{$total} = --$totalValue;
    }

    public function defineState()
    {
        /** @var InspectionState $inspectionState */
        $inspectionState = $this->sysApp['inspections.inspection.state'];

        if ($this->getTotalFailedWO() !== 0) {
            return $inspectionState::FAILED;
        }

        if ($this->getTotalQuestions() == $this->getTotalAnswers()) {
            return $inspectionState::COMPLETED;
        }

        return $inspectionState::IN_PROGRESS;
    }

    public function setState($state)
    {
        $state = empty($state) ? $this->defineState() : $state;

        $this->__setter('state', $state);
    }

    public function getState()
    {
        return $this->__getter('state');
    }

    public function setTotalQuestions($totalQuestions)
    {
        $this->__setter('totalQuestions', $totalQuestions);
    }

    public function incTotalQuestions()
    {
        $this->incTotal('totalQuestions');
    }

    public function decTotalQuestions()
    {
        $this->decTotal('totalQuestions');
    }

    public function getTotalQuestions()
    {
        return $this->__getter('totalQuestions');
    }

    public function setTotalAnswers($totalAnswers)
    {
        $this->__setter('totalAnswers', $totalAnswers);
    }

    public function getTotalAnswers()
    {
        return $this->__getter('totalAnswers');
    }

    public function incTotalAnswers()
    {
        $this->incTotal('totalAnswers');
    }

    public function decTotalAnswers()
    {
        $this->decTotal('totalAnswers');
    }

    public function setTotalFailedWO($totalFailedWO)
    {
        $this->__setter('totalFailedWO', $totalFailedWO);
    }

    public function getTotalFailedWO()
    {
        return $this->__getter('totalFailedWO');
    }

    public function getTotalWO()
    {
        return $this->__getter('totalWO');
    }

    public function setTotalWO($totalWO)
    {
        $this->__setter('totalWO', $totalWO);
    }

    public function incTotalFailedWO()
    {
        $this->incTotal('totalFailedWO');
    }

    public function decTotalFailedWO()
    {
        $this->decTotal('totalFailedWO');
    }

    public function incTotalWO()
    {
        $this->incTotal('totalWO');
    }

    public function decTotalWO()
    {
        $this->decTotal('totalWO');
    }

    public function setComments($comments)
    {
        if (is_array($comments)) {
            $commentActiveRecords = [];

            foreach ($comments as $comment) {
                $commentActiveRecord =
                    $this->sysApp['inspections.inspection.comment.activeRecord'];
                $commentActiveRecord->update($comment);
                array_push($commentActiveRecords, $commentActiveRecord);
            }
            $comments = $commentActiveRecords;
        }

        $this->__setter('comments', $comments);
    }

    public function getComments()
    {
        return $this->__getter('comments');
    }

    public function setImages($images)
    {
        if (is_array($images)) {
            $imageActiveRecords = [];

            foreach ($images as $image) {
                $imageActiveRecord = $this->sysApp['core.model.image.activeRecord'];
                $imageActiveRecord->update($image);
                array_push($imageActiveRecords, $imageActiveRecord);
            }
            $images = $imageActiveRecords;
        }

        $this->__setter('images', $images);
    }

    public function getImages()
    {
        return $this->__getter('images');
    }

    /**
     * @param TemplateVersionActiveRecord $templateVersion
     */
    public function setTemplateVersion($templateVersion)
    {
        $template = $templateVersion->getTemplate();
        $this->__setter('templateVersion', $templateVersion);
        $this->__setter('template', $template);
    }

    /**
     * @return TemplateVersionActiveRecord
     */
    public function getTemplateVersion()
    {
        return $this->__getter('templateVersion');
    }

    /**
     * @param TemplateActiveRecord $template
     */
    public function setTemplate($template)
    {
        $this->__setter('template', $template);
    }

    /**
     * @return TemplateActiveRecord
     */
    public function getTemplate()
    {
        return $this->__getter('template');
    }

    /**
     * @return Collection
     */
    public function getQuestions()
    {
        return $this->__getter('questions');
    }

    /**
     * @param Collection $questions
     */
    public function setQuestions($questions)
    {
        $this->__setter('questions', $questions);
    }
}
