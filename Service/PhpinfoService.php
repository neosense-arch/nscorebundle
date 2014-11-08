<?php

namespace NS\CoreBundle\Service;

/**
 * Class PhpInfoService
 *
 * @package NS\CoreBundle\Service
 */
class PhpinfoService
{
    /**
     * @return array
     */
    public function getInfo()
    {
        $user  = posix_getpwuid(posix_geteuid());
        $group = posix_getgrgid($user['gid']);

        $info = array(
            // general
            'general' => array(
                'version' => phpversion(),
                'user'    => $user,
                'group'   => $group,
            ),

            // php.ini info
            'phpIni' => array(
                'loaded'  => php_ini_loaded_file(),
                'scanned' => php_ini_scanned_files(),
            ),

            // file upload info
            'fileUpload' => $this->iniValues(array(
                'file_uploads',
                'upload_max_filesize',
                'post_max_size',
                'max_input_time',
                'upload_tmp_dir',
                'max_file_uploads',
            )),
        );
        return $info;
    }

    private function iniValues(array $names)
    {
        $values = array();
        foreach ($names as $name) {
            $values[$name] = ini_get($name)?:'n/a';
        }
        return $values;
    }
} 