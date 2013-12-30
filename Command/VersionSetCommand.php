<?php

namespace NS\CoreBundle\Command;

use NS\CoreBundle\Service\VersionService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VersionSetCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('ns:core:version:set')
			->addArgument('version', InputArgument::REQUIRED)
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

		$versionService->setVersion($input->getArgument('version'));

		$output->writeln(sprintf(
			"Current version is <info>%s</info>",
			$versionService->getVersion()
		));
	}
}