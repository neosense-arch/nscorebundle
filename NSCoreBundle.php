<?php

namespace NS\CoreBundle;

use NS\CoreBundle\Bundle\CoreBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NSCoreBundle extends Bundle implements CoreBundle
{
    /**
     * Retrieves human-readable bundle title
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Ядро';
    }
}
