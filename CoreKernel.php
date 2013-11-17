<?php

namespace NS\CoreBundle;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class CoreKernel
 *
 * @package NS\CoreBundle
 */
abstract class CoreKernel extends Kernel
{
	/**
	 * Registers bundles
	 *
	 * @return Bundle[]
	 */
	public function registerBundles()
	{
		$bundles = array(
			new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
			new \Symfony\Bundle\TwigBundle\TwigBundle(),
			new \Symfony\Bundle\MonologBundle\MonologBundle(),
			new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
			new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
			new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
			new \JMS\AopBundle\JMSAopBundle(),
			new \JMS\DiExtraBundle\JMSDiExtraBundle($this),
			new \JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

			new \Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
			new \FOS\UserBundle\FOSUserBundle(),
			new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
			new \Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
			new \Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
			new \Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
			new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
			new \Liuggio\ExcelBundle\LiuggioExcelBundle(),

			new \NS\AdminBundle\NSAdminBundle(),
			new \NS\CatalogBundle\NSCatalogBundle(),
			new \NS\CoreBundle\NSCoreBundle(),
			new \NS\CmsBundle\NSCmsBundle(),
			new \NS\DeployBundle\NSDeployBundle(),
			new \NS\DocumentsBundle\NSDocumentsBundle(),
			new \NS\FeedbackBundle\NSFeedbackBundle(),
			new \NS\GalleryBundle\NSGalleryBundle(),
			new \NS\NewsBundle\NSNewsBundle(),
			new \NS\PropertiesBundle\NSPropertiesBundle(),
			new \NS\RoutingBundle\NSRoutingBundle(),
			new \NS\SearchBundle\NSSearchBundle(),
			new \NS\ShopBundle\NSShopBundle(),
			new \NS\UserBundle\NSUserBundle(),
		);

		if (in_array($this->getEnvironment(), array('dev', 'test'))) {
			$bundles[] = new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle();
			$bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
			$bundles[] = new \Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
			$bundles[] = new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
		}

		$bundles = array_merge($bundles, $this->getUserBundles());

		return $bundles;
	}

	/**
	 * Registers container configuration
	 *
	 * @param LoaderInterface $loader
	 * @return void
	 */
	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
	}

	/**
	 * @return Bundle[]
	 */
	abstract protected function getUserBundles();
}