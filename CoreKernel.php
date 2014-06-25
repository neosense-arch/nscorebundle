<?php

namespace NS\CoreBundle;

use FM\ElfinderBundle\FMElfinderBundle;
use Genemu\Bundle\FormBundle\GenemuFormBundle;
use NS\AdminBundle\NSAdminBundle;
use NS\CacheBundle\NSCacheBundle;
use NS\CatalogBundle\NSCatalogBundle;
use NS\CmsBundle\NSCmsBundle;
use NS\DeployBundle\NSDeployBundle;
use NS\DocumentsBundle\NSDocumentsBundle;
use NS\FeedbackBundle\NSFeedbackBundle;
use NS\GalleryBundle\NSGalleryBundle;
use NS\NewsBundle\NSNewsBundle;
use NS\PropertiesBundle\NSPropertiesBundle;
use NS\RoutingBundle\NSRoutingBundle;
use NS\SearchBundle\NSSearchBundle;
use NS\SeoBundle\NSSeoBundle;
use NS\ShopBundle\NSShopBundle;
use NS\UserBundle\NSUserBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Trsteel\CkeditorBundle\TrsteelCkeditorBundle;

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
			new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
			new \Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new TrsteelCkeditorBundle(),
            new FMElfinderBundle(),
            new GenemuFormBundle(),

			new NSAdminBundle(),
			new NSCacheBundle(),
			new NSCatalogBundle(),
			new NSCoreBundle(),
			new NSCmsBundle(),
			new NSDeployBundle(),
			new NSFeedbackBundle(),
			new NSRoutingBundle(),
			new NSSearchBundle(),
            new NSSeoBundle(),
			new NSShopBundle(),
			new NSUserBundle(),
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