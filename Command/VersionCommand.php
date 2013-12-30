<?php

namespace NS\CoreBundle\Command;

use NS\CoreBundle\Service\VersionService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class VersionCommand
 *
 * @package NS\CoreBundle\Command
 */
class VersionCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('ns:core:version')
			->setDescription('Displays core version')
		;
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 * @return int|null|void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		/** @var VersionService $versionService */
		$versionService = $this->getContainer()->get('ns_core.service.version');

		$output->writeln(sprintf(
			"Current version: <info>%s</info>",
			$versionService->getVersion()
		));

		$output->writeln(sprintf(
			"Git version: <info>%s</info>",
			$versionService->getGitVersion()
		));

		if (!$versionService->isVersionCorrect()) {
			$output->writeln("<error>Local version is not correct! Try to sync versions (ns:core:version:sync)</error>");
		}
	}
}