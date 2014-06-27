<?php
namespace NS\CoreBundle\Twig\Extension;

class FileSizeExtension extends \Twig_Extension
{
    /**
     * @return array An array of functions
     */
    public function getFilters()
    {
        return array(
			'ns_filesize' => new \Twig_SimpleFilter('ns_filesize', array($this, 'getFileSize')),
		);
    }

    /**
     * @param int $bytes
     * @return string
     */
	public function getFileSize($bytes)
	{
        $kb = 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        $tb = $gb * 1024;

        if ($bytes < $kb) {
            return round($bytes) . ' Б';
        }
        if ($bytes < $mb) {
            return round($bytes/$kb) . ' КБ';
        }
        if ($bytes < $gb) {
            return round($bytes/$mb) . ' МБ';
        }
        if ($bytes < $tb) {
            return round($bytes/$gb) . ' ГБ';
        }
        return round($bytes/$tb) . ' ТБ';
	}

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
		return 'ns_filesize';
    }
}