<?php
namespace Averta\WordPress\Database\ORM;

use TypeRocket\Core\Container;

class ORM {

    public static function init() {
        Container::singleton(Config::class, function() {
			return new Config(__DIR__ . '/Config');
		}, Config::ALIAS);

		Container::singleton( Connection::class, function() {
			return Connection::initDefault();
		},Connection::ALIAS);
    }
}