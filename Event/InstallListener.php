<?php

namespace NS\CoreBundle\Event;

use NS\CoreBundle\Service\InstallService;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class InstallListener
 *
 * @package NS\DeployBundle\Event
 */
class InstallListener
{
    /**
     * @var InstallService
     */
    private $installService;

    /**
     * @param InstallService $installService
     */
    public function __construct(InstallService $installService)
    {
        $this->installService = $installService;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->installService->isInstalled()) {
            return;
        }

        $skip = array(
            'web_profiler.controller.profiler:toolbarAction',
            'twig.controller.exception:showAction',
        );
        if (in_array($event->getRequest()->attributes->get('_controller'), $skip)) {
            return;
        }

        $event->getRequest()->attributes->set('_controller', 'NSCoreBundle:Install:install');
    }
} 