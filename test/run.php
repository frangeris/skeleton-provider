<?php

include(__DIR__ . '/../vendor/autoload.php');

use Skeleton\SDK\Common\Client,
	Providers\Test\TestProvider
	;

// Setting up the client with the configuration
$config = [
	'method' => 'hmac', // Signature to use
	'public_key' => 'asdasdasd',
	'private_key' => 'zxzxzxzxzx',
	'base_url' => ['http://{identifier}.{domain}.io/{version}', ['version' => 'v1', 'identifier' => 'demo4354589', 'domain' => 'mockable']],
];
$client = Client::getInstance()->setConfig($config);

// Data to send
$data = [
	'email' => 'test@test.com',
	'first_name' => 'Jhon',
	'last_name' => 'Doe',
	'phone' => '000-000-0000'
];

// Lets create the new test
$test = new TestProvider($client);
var_dump($test->create($data));
