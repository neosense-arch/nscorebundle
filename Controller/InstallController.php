<?php

namespace NS\CoreBundle\Controller;

use NS\CoreBundle\Event\CoreEvents;
use NS\CoreBundle\Event\InstallEvent;
use NS\CoreBundle\Form\Type\AdminType;
use NS\CoreBundle\Form\Type\DatabaseType;
use NS\CoreBundle\Service\InstallService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package NS\CoreBundle\Controller
 */
class InstallController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function installAction(Request $request)
    {
        set_time_limit(0);
        $step = $request->query->get('step', 0);
        $map = array(
            'updateParameters',
            'installEngine',
            'createAdmin',
        );
        return call_user_func(array($this, $map[$step]), $request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    private function updateParameters(Request $request)
    {
        $form = $this->createForm(new DatabaseType());
        $form->setData(array(
            'database_user'     => $this->container->getParameter('database_user'),
            'database_password' => $this->container->getParameter('database_password'),
            'database_name'     => $this->container->getParameter('database_name'),
            'database_host'     => $this->container->getParameter('database_host'),
            'database_port'     => $this->container->getParameter('database_port'),
        ));

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var InstallService $installService */
            $installService = $this->get('ns_core.service.install');
            $installService->updateParameters($form->getData());

            return $this->redirect($this->generateUrl('ns_cms_main', array('step' => 1)));
        }

        return $this->render('NSCoreBundle:Install:installParameters.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    private function installEngine(Request $request)
    {
        /** @var InstallService $installService */
        $installService = $this->get('ns_core.service.install');

        // creating database
        if (!$installService->databaseExists()) {
            $installService->createDatabase();
        }

        // updating schema
        $installService->updateSchema();

        // restoring dump
        if ($installService->hasRestoreDump()) {
            $installService->restoreDump()->clearDump();
            return $this->finishInstall();
        }

        // installing from scratch
        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->get('event_dispatcher');
        $eventDispatcher->dispatch(CoreEvents::INSTALL);

        return $this->redirect($this->generateUrl('ns_cms_main', array('step' => 2)));
    }

    /**
     * @param Request $request
     * @return Response
     */
    private function createAdmin(Request $request)
    {
        $form = $this->createForm(new AdminType());

        $form->handleRequest($request);
        if ($form->isValid()) {
            $user = $form->getData();

            // creating user
            $manipulator = $this->get('fos_user.util.user_manipulator');
            $manipulator->create($user['email'], $user['password'], $user['email'], true, false);

            // adding admin role
            $manipulator->addRole($user['email'], 'ROLE_ADMIN');

            return $this->finishInstall();
        }

        return $this->render('NSCoreBundle:Install:installAdmin.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @return Response
     */
    private function finishInstall()
    {
        /** @var InstallService $installService */
        $installService = $this->get('ns_core.service.install');
        $installService->setInstalled();
        return $this->render('NSCoreBundle:Install:installSuccess.html.twig');
    }
}
