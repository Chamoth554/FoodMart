<?php

namespace Averta\WordPress\Database\ORM;

use TypeRocket\Database\Connection as TypeRockerConnection;
use TypeRocket\Database\Connectors\DatabaseConnector;
use TypeRocket\Database\Connectors\WordPressCoreDatabaseConnector;

class Connection extends TypeRockerConnection
{
	const ALIAS = 'averta-db-database';

	/**
     * @return static
     */
    public static function initDefault()
    {
        $default = Config::getFromContainer()->locate('database.default');
        $connection = new static;

        if(!$connection->exists($default)) {
            $config = null;

            if(is_null($default)) {
                $default = 'wp';
                $config = [
                    'driver' => WordPressCoreDatabaseConnector::class
                ];
            }

            $connection->addFromConfig($default, $config);
        }

        return $connection;
    }

	/**
	 * @param null|string $name
	 * @param array|null  $config
	 *
	 * @return $this
	 */
    public function addFromConfig(?string $name, ?array $config = null)
    {
        if(is_null($config) || is_null($name)) {
            $drivers = Config::getFromContainer()->locate('database.drivers');
            $config = $drivers[$name] ?? Config::getFromContainer()->locate('database.default');

            if($name && !$drivers && !$config) {
                throw new \Error(__("TypeRocket database connection configuration not found for \"{$name}\"", 'depicter'));
            }
        }

        /** @var DatabaseConnector $connector */
        $connector = new $config['driver'];
        $connector->connect($name, $config);
        return $this->add($connector->getName(), $connector->getConnection());
    }

	/**
	 * @return \wpdb
	 */
	public function default()
	{
		$default = Config::getFromContainer()->locate('database.default', 'wp');
		return $this->get($default);
	}



}
