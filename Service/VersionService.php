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
	 * @var string
	 */
	private $version;

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
		if (is_null($this->version)) {
			$fileName = realpath(__DIR__ . '/../Resources') . '/' . $this->versionFileName;

			if (!file_exists($fileName)) {
				throw new \Exception("Version file '{$fileName}' wasn't found");
			}
			if (!is_readable($fileName)) {
				throw new \Exception("Version file '{$fileName}' is not readable");
			}

			$this->version = file_get_contents($fileName);

			if ($this->version == '') {
				throw new \Exception("Version file '{$fileName}' is empty");
			}
		}

		return $this->version;
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
}
