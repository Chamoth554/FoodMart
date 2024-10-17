<?php
return [

	/*
	|--------------------------------------------------------------------------
	| Default Database Connection
	|--------------------------------------------------------------------------
	|
	| This option defines the default database driver that is used when a query
	| or model is instantiated. See the list of drivers below the available
	| options. You can add your own to the list.
	|
	*/
	'default' => 'wp',

	/*
	|--------------------------------------------------------------------------
	| Connections
	|--------------------------------------------------------------------------
	|
	| Here you may configure the database drivers for your application.
	|
	| Available Drivers: "wp"
	*/
	'drivers' => [
		'wp' => [
			'driver' => '\TypeRocket\Database\Connectors\WordPressCoreDatabaseConnector',
		],

		'alt' => [
			'driver' => '\TypeRocket\Database\Connectors\CoreDatabaseConnector',
			'username' => 'TYPEROCKET_ALT_DATABASE_USER',
			'password' => 'TYPEROCKET_ALT_DATABASE_PASSWORD',
			'database' => 'TYPEROCKET_ALT_DATABASE_DATABASE',
			'host' => 'TYPEROCKET_ALT_DATABASE_HOST',
		],
	]
];
