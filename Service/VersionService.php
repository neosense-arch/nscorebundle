<?php

namespace NS\CoreBundle\Service;

use Symfony\Component\Process\Process;

class VersionService
{
	/**
	 * @var string
	 */
	private $versionFileName;

	/**
	 * @param string $versionFileName
	 */
	public function __construct($versionFileName)
	{
		$this->versionFileName = $versionFileName;
	}

	/**
	 * Retrieves current version
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function getVersion()
	{
		$fileName = realpath(__DIR__ . '/../Resources') . '/' . $this->versionFileName;

		if (!file_exists($fileName)) {
			throw new \Exception("Version file '{$fileName}' wasn't found");
		}
		if (!is_readable($fileName)) {
			throw new \Exception("Version file '{$fileName}' is not readable");
		}

		$version = file_get_contents($fileName);

		if ($version === '') {
			throw new \Exception("Version file '{$fileName}' is empty");
		}

		return $version;
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function getGitVersion()
	{
		$process = new Process('git describe', __DIR__ . '/../');
		$process->run();

		if (!$process->isSuccessful()) {
			throw new \Exception("Git process error: " . $process->getErrorOutput());
		}

		$version = $process->getOutput();
		$version = trim($version);

		// removing start 'v'
		$version = substr($version, 1);

		return $version;
	}

	/**
	 * @return bool
	 */
	public function isVersionCorrect()
	{
		return $this->getVersion() === $this->getGitVersion();
	}

	/**
	 * @throws \Exception
	 */
	public function syncVersion()
	{
		$this->setVersion($this->getGitVersion());
	}

	/**
	 * @param $version
	 * @throws \Exception
	 */
	public function setVersion($version)
	{
		$fileName = realpath(__DIR__ . '/../Resources') . '/' . $this->versionFileName;

		if (!file_exists($fileName)) {
			throw new \Exception("Version file '{$fileName}' wasn't found");
		}
		if (!is_writable($fileName)) {
			throw new \Exception("Version file '{$fileName}' is not writable");
		}

		file_put_contents($fileName, $version);
	}
}
