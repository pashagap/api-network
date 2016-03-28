<?php

namespace Hatch\Inspections\Console\Command;

use Hatch\Core\Console\Command\AbstractCommand;
use Hatch\Inspections\Model\Entities\TemplateVersion\TemplateVersionPublishState;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SignIn
 *
 * @package Hatch\Core\Console\Command
 */
class DevDataWipe extends AbstractCommand
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

        $repositories = [
            'inspections.template.repository',
            'inspections.templateVersion.repository',
            'inspections.inspection.repository',
            'inspections.question.repository',
            'inspections.answer.repository',
            'inspections.workOrder.repository'
        ];

        foreach($repositories as $repoName)
        {
            $this->writeAction("Cleaning $repoName");
            $repo = $this->sysApp[$repoName];
            $repo->erase();
        }
    }

    public function bindCurrentUser()
    {
        /** @var UserRepositoryInterface $userRepo */
        $userRepo = $this->sysApp['core.user.repository'];

        $userResponseData = $userRepo->readOne();
        $this->sysApp->bindCurrentUser($userResponseData['response']['data']);
    }
}
