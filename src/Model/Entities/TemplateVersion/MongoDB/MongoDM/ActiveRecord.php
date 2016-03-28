<?php

namespace Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM;

use Hatch\Core\Exception\ExpectedException;
use Hatch\Core\Exception\Model\DataNotFoundException;
use Hatch\Core\Model\Common\MongoDB\MongoDM\AbstractActiveRecord;
use Hatch\Core\Model\Entities\User\MongoDB\MongoDM\Model as UserModel;
use Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM\Model as TemplateModel;
use Hatch\Inspections\Model\Entities\TemplateVersion\TemplateVersionPublishState;
use Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM\Model as TemplateVersionModel;
use Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM\ActiveRecord as TemplateActiveRecord;
use Purekid\Mongodm\Collection;
use Hatch\Core\Model\Entities\User\MongoDB\MongoDM\ActiveRecord as UserActiveRecord;

/**
 * Class ActiveRecord
 *
 * @package Hatch\Inspections\Model\Entities\TemplateVersion\MongoDB\MongoDM
 */
class ActiveRecord extends AbstractActiveRecord
{
    protected $lastModifiedBy = null;
    protected $lastModifiedDate = null;

    protected $primaryModel = 'inspections.templateVersion.model';
    public static $collection = 'InspectionsTemplateVersion';

    protected $lastModifiedState = null;

    public static $attrs
        = [
            // draft, published, unpublished
            'state' => [
                'type' => 'string',
                'default' => 'draft'
            ],

            'version' => [
                'type' => 'int'
            ],
            'name' => [
                'type' => 'string'
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

            'template' => [
                'model' => 'Hatch\Inspections\Model\Entities\Template\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ],
            'location' => [
                'model' => 'Hatch\Core\Model\Entities\Location\MongoDB\MongoDM\ActiveRecord',
                'type' => 'reference'
            ]
        ];

    protected function __preUpdate()
    {
        if($this->isGoingToBePublished()) {
            $this->unpublishPreviousTemplateVersion();
        }

        $this->updateToCurrentDate('modifiedDate');
        $this->updateToCurrentUser('modifiedBy');

        /** @var TemplateVersionModel $versionModel */
        $versionModel = $this->sysApp['inspections.templateVersion.model'];
        /** @var TemplateVersionPublishState $state */
        $state = $this->sysApp['inspections.templateVersion.state'];

        $lastModifiedState = isset($this->lastModifiedState)
            ? $this->lastModifiedState : $this->getState();

        // If to change status from published on unpublished
        if($state::PUBLISHED === $this->lastModifiedState
            && $state::UNPUBLISHED === $this->getState())
        {
            $versionModel->allowLockedVersionUpdate();
        }

        // If we try to modify version which is not draft
        // Or not trying to change status from published on unpublished
        if ($state::DRAFT !== $lastModifiedState
                && !$versionModel->lockedVersionUpdateAllowed()
        ) {
            throw new ExpectedException(
                'This template version is locked for update', 400
            );
        }

        return parent::__preUpdate();
    }

    protected function __preInsert()
    {
        $this->updateToCurrentDate('createdDate');
        $this->updateToCurrentUser('createdBy');

        $this->modifiedDate = $this->createdDate;
        $this->modifiedBy = $this->createdBy;

        /** @var AbstractActiveRecord $template */
        $template = $this->template;
        /** @var TemplateVersionModel $versionModel */
        $versionModel = $this->sysApp['inspections.templateVersion.model'];
        $versionCount = $versionModel->count(
            [
                'template.$id' => $template->getId()
            ]
        );
        $this->version = $versionCount + 1;
        $this->name = sprintf(
            '%s %d',
            $template->name,
            $this->version
        );

        /** @var TemplateVersionPublishState $state */
        $state = $this->sysApp['inspections.templateVersion.state'];
        if($state::PUBLISHED === $this->getState()) {
            $this->unpublishPreviousTemplateVersion();
        }

        return parent::__preInsert();
    }

    private function unpublishPreviousTemplateVersion()
    {
        /** @var TemplateModel $templateModel */
        $templateModel = $this->sysApp['inspections.template.model'];
        /** @var AbstractActiveRecord $template */
        $template = $this->template;

        try {
            $templateModel->unsetPublishedVersionById($template->getId());
        } catch (DataNotFoundException $e) {
        }
    }

    private function isGoingToBePublished()
    {
        /** @var TemplateVersionPublishState $state */
        $state = $this->sysApp['inspections.templateVersion.state'];

        return $state::PUBLISHED === $this->getState() && $state::DRAFT === $this->lastModifiedState;
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

    public function setState($state)
    {
        $this->lastModifiedState = $this->getState();
        $this->__setter('state', $state);
    }

    public function getState()
    {
        return $this->__getter('state');
    }

    /**
     * @return TemplateActiveRecord
     */
    public function getTemplate()
    {
        return $this->__getter('template');
    }

    public function setTemplate($template)
    {
        $this->__setter('template', $template);
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

    public function setModifiedDate($modifiedDate)
    {
        $this->lastModifiedDate = $this->getModifiedDate();

        $this->__setter('modifiedDate', $modifiedDate);
    }

    /**
     * @return \MongoDate
     */
    public function getModifiedDate()
    {
        return $this->__getter('modifiedDate');
    }

    public function setModifiedBy($modifiedBy)
    {
        $this->lastModifiedBy = $this->getModifiedBy();

        $this->__setter('modifiedBy', $modifiedBy);
    }

    /**
     * @return UserActiveRecord
     */
    public function getModifiedBy()
    {
        return $this->__getter('modifiedBy');
    }
}
