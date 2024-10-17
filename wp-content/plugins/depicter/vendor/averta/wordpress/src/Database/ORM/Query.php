<?php

namespace Averta\WordPress\Database\ORM;

class Query extends \TypeRocket\Database\Query
{
	/**
	 * @return \wpdb
	 */
	protected function establishConnection()
	{
		$connection = Connection::getFromContainer();

		if(!$name = $this->connection) {
			return $connection->default();
		}

		return $connection->get($name);
	}
}
