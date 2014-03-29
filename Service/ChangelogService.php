<?php

namespace NS\CoreBundle\Service;

use Symfony\Component\Yaml\Parser;

/**
 * Class ChangelogService
 *
 * @package NS\CoreBundle\Service
 */
class ChangelogService
{
    private $changeLogFileName;

    /**
     * @param string $changelogFileName
     */
    function __construct($changelogFileName)
    {
        $this->changeLogFileName = $changelogFileName;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getChangelog()
    {
        if (!file_exists($this->changeLogFileName)) {
            throw new \Exception("Changelog file '{$this->changeLogFileName}' wasn't found");
        }

        $parser = new Parser();
        return $parser->parse(file_get_contents($this->changeLogFileName));
    }
}
