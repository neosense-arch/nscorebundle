<?php
namespace NS\CoreBundle\Twig\Extension;

/**
 * Class CdnExtension
 *
 * @package NS\CoreBundle\Twig\Extension
 */
class CdnExtension extends \Twig_Extension
{
    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
			'ns_cdn' => new \Twig_Function_Method($this, 'getCdn', array('is_safe' => array('html'))),
		);
    }

    /**
     * @param string $library
     * @throws \Exception
     * @return string
     */
	public function getCdn($library)
	{
		$libraries = array(
            // Twitter bootstrap
            'bootstrap-2-css'      => '<link rel="stylesheet" href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" />',
            'bootstrap-2-js'       => '<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>',

            // jQuery
            'jquery'               => 'jquery-1.11-js',
            'jquery-1'             => 'jquery-1.11-js',
            'jquery-1.11'          => 'jquery-1.11-js',
            'jquery-1.11-js'       => '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>',

            // jQuery UI
            'jqueryui-css'         => 'jqueryui-1.10-css',
            'jqueryui-js'          => 'jqueryui-1.10-js',
            'jqueryui-1.10-css'    => '<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />',
            'jqueryui-1.10-js'     => '<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>',

            // jQuery Plugins
            'jquery-cookie-js'     => 'jquery-cookie-1.4-js',
            'jquery-cookie-1.4-js' => '<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.0/jquery.cookie.min.js"></script>',
        );

        if (!isset($libraries[$library])) {
            $keys = join("', '", array_keys($libraries));
            throw new \Exception("CDN library '{$library}' wasn't found among '{$keys}'");
        }

        // aliases
        $value = $libraries[$library];
        if (isset($libraries[$value])) {
            $value = $libraries[$value];
        }

        return $value;
	}

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
		return 'ns_cdn';
    }
}