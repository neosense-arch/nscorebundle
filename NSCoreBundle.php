<?php

namespace NS\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NSCoreBundle extends Bundle
{
	public static function getCoreBundles()
	{
		return array(
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
	}
}
