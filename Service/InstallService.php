<?php

namespace NS\CoreBundle\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

/**
 * Class InstallService
 *
 * @package NS\CoreBundle\Service
 */
class InstallService
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var string
     */
    private $parametersFileName;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $restoreFileName;

    /**
     * @param string        $rootDir
     * @param Connection    $connection
     * @param EntityManager $entityManager
     * @param string        $restoreFileName
     */
    public function __construct($rootDir, Connection $connection, EntityManager $entityManager, $restoreFileName)
    {
        $this->rootDir            = $rootDir;
        $this->parametersFileName = $rootDir . '/config/parameters.yml';
        $this->connection         = $connection;
        $this->entityManager      = $entityManager;
        $this->restoreFileName    = $restoreFileName;
    }

    /**
     * @return bool
     */
    public function isInstalled()
    {
        return file_exists($this->rootDir . '/.installed');
    }

    public function setInstalled()
    {
        touch($this->rootDir . '/.installed');
    }

    /**
     * @param array $parameters
     * @throws \Exception
     */
    public function updateParameters(array $parameters)
    {
        if (!file_exists($this->parametersFileName)) {
            throw new \Exception("Parameters.yml file wasn't found in {$this->parametersFileName}");
        }
        if (!is_writable($this->parametersFileName)) {
            throw new \Exception("File {$this->parametersFileName} is not writable");
        }

        // merging
        $yml = Yaml::parse(file_get_contents($this->parametersFileName));
        $yml['parameters'] = array_merge($yml['parameters'], $parameters);

        // saving
        file_put_contents($this->parametersFileName, Yaml::dump($yml, 99));
    }

    /**
     * @return bool
     */
    public function databaseExists()
    {
        $params = $this->connection->getParams();
        $name = $params['dbname'];

        unset($params['dbname']);
        $tmpConnection = DriverManager::getConnection($params);

        $databases = $tmpConnection->getSchemaManager()->listDatabases();
        return in_array($name, $databases);
    }

    public function createDatabase()
    {
        $params = $this->connection->getParams();
        $name = isset($params['path']) ? $params['path'] : $params['dbname'];

        unset($params['dbname']);
        $tmpConnection = DriverManager::getConnection($params);

        // Only quote if we don't have a path
        if (!isset($params['path'])) {
            $name = $tmpConnection->getDatabasePlatform()->quoteSingleIdentifier($name);
        }

        $tmpConnection->getSchemaManager()->createDatabase($name);
        $tmpConnection->close();
    }

    public function updateSchema()
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->updateSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    /**
     * @return bool
     */
    public function hasRestoreDump()
    {
        return file_exists($this->restoreFileName);
    }

    /**
     * @throws \Exception
     */
    public function restoreDump()
    {
        if (!file_exists($this->restoreFileName)) {
            throw new \Exception("SQL dump file name {$this->restoreFileName} wasn't found");
        }

        if (!is_readable($this->restoreFileName)) {
            throw new \Exception("File {$this->restoreFileName} is not readable");
        }

        $params = $this->connection->getParams();

        // checking MySQL
        if ($params['driver'] !== 'pdo_mysql') {
            throw new \Exception("This backup implementation supports only pdo_mysql database driver");
        }

        $cmd = sprintf("mysql -u%s -p%s -h%s %s < %s",
            $params['user'],
            $params['password'],
            $params['host'],
            $params['dbname'],
            $this->restoreFileName);
        $this->exec($cmd);

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function clearDump()
    {
        if (!is_writable($this->restoreFileName)) {
            throw new \Exception("File {$this->restoreFileName} is not writable");
        }
        unlink($this->restoreFileName);

        return $this;
    }

    /**
     * @param string $cmd
     * @throws \Exception
     */
    private function exec($cmd)
    {
        // mac os x mysql path
        $path = 'PATH=$PATH:/usr/local/mysql/bin';

        $process = new Process("{$path} && {$cmd}", $this->rootDir, null, null, 300);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \Exception("Process failed: " . $process->getErrorOutput());
        }
    }
}
