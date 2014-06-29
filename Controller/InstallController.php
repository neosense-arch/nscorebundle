<?php

namespace NS\CoreBundle\Controller;

use NS\CoreBundle\Form\Type\DatabaseType;
use NS\CoreBundle\Service\InstallService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
    protected function installEngine(Request $request)
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
            $installService
                ->restoreDump()
                ->clearDump()
                ->setInstalled();
            return $this->render('NSCoreBundle:Install:installSuccess.html.twig');
        }

        throw new \Exception("Restore dump wasn't found. Full install-from-scratch functionality is not implemented yet");
    }
}
