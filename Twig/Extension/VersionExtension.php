<?php
namespace NS\CoreBundle\Twig\Extension;

use NS\CoreBundle\Service\VersionService;

class VersionExtension extends \Twig_Extension
{
	/**
	 * @var VersionService
	 */
	private $versionService;

	/**
	 * @param VersionService $versionService
	 */
	public function __construct(VersionService $versionService)
	{
		$this->versionService = $versionService;
	}

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
			'ns_core_version' => new \Twig_Function_Method($this, 'getVersion', array('is_safe' => array('html'))),
		);
    }

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return $this->versionService->getVersion();
	}

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
		return 'ns_core_version';
    }
}