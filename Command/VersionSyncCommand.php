<?php

namespace NS\CoreBundle\Command;

use NS\CoreBundle\Service\VersionService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VersionSyncCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('ns:core:version:sync')
		;
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 * @throws \Exception
	 * @return int|null|void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		/** @var VersionService $versionService */
		$versionService = $this->getContainer()->get('ns_core.service.version');

		$output->writeln(sprintf(
			"Current version is <info>%s</info>",
			$versionService->getVersion()
		));

		$output->writeln(sprintf(
			"Git version is <info>%s</info>",
			$versionService->getGitVersion()
		));

		if (!$versionService->isVersionCorrect()) {
			$versionService->syncVersion();

			$output->writeln(sprintf(
				"New current version is <info>%s</info>",
				$versionService->getVersion()
			));

			if (!$versionService->isVersionCorrect()) {
				throw new \Exception("Unknown post sync error occurred: new current version is not correct");
			}
		}
	}
}