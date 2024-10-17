<?php

namespace Averta\WordPress\Database\ORM;

use TypeRocket\Core\Container;

class Config extends \TypeRocket\Core\Config
{
	public const ALIAS = 'averta-wordpress-config';

	/**
     * @return static
     */
    public static function getFromContainer()
    {
        return Container::resolve(static::ALIAS);
    }
}
