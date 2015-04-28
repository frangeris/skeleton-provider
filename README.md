Skeleton provider
==============

This vendor is the complement of a wrapper for creating fast providers using standard REST SDK, which is the common place where the logic of each given resource is determined through of RESTful API.

Features to be taken into consideration to define the logic of work:

 - You can implement psr you want that is more adaptable to your
   project, configure it with composer (default psr-4).
 - The whole logic of communication with REST is included in an outer
   package skeleton-sdk focused solely on the interaction of transfer
   with the api, this logic is not included in this package.
 - Within the source exists a class of super-basic example that shows
   how to start the flow implements the logic you want following this
   pattern.

***Let's rock and roll...***

**1) Installation**

The best way to start a clean project is to delegate this task to composer, just run the following:

```sh
$ composer create-project frangeris/skeleton-provider /path/to/install/ 0.0.2
```

After this, a clean folder has been created (now a folder without repo), initialize your repository and haste charge... all dependencies have been installed, if you are at this point, you can already start creating your respective providers to consume your RESTful api.

**2) Setting up**

The first thing to consider is what related to namespaces by default is using psr-4 under the namespace `Providers`, if you want change, you can do...

Default configuration(autoload inside composer.json):

```
"autoload": {
	"classmap": [
		"src/"
	],		
	"psr-4": {
		"Providers\\": "src/providers/"
	}
}
```

**3) Configuration**

For the skeleton-sdk Client to work's properly, it needs variables to manage the requests, these variables are domain (base_url, public & private key (signatures) when using hmac authentication, an example of the structure of the configuration is as follows:

```php
$config = [
	'method' => 'hmac', // Signature to use
	'public_key' => 'asdasdasd',
	'private_key' => 'zxzxzxzxzx',
	'base_url' => ['http://api.{domain}.{extension}/{version}/', ['version' => 'v1', 'extension' => 'io', 'domain' => 'somedomain']],
];
```
Where `method` is the signature to use for authenticate if is "hmac" then public & private key are required, the base url for creating the path to request. This is the configuration that the skeleton-sdk customer uses to make consequential request.

**4) Lets start coding**

Each provider must extend the abstract class `Skeleton\SDK\Providers\AbstractProvider` belonging to the skeleton-sdk package(we are extending an sdk) and implement `Skeleton\SDK\Common\Supplier\ISupplier` interface to overwrite inherited methods (CRUD) leaving your provider like this:

```php

namespace Providers\Test;

use Skeleton\SDK\Providers\AbstractProvider,
	Skeleton\SDK\Common\Supplier\ISupplier
	;

/**
 * Mock provider test class
 */
class TestProvider extends AbstractProvider implements ISupplier
{
	public function create($provider)
	{}

	public function read()
	{}

	public function update($provider)
	{}

	public function delete($id)
	{}

	public function getById($id)
	{}
}

```

In this class of example, show the basic operations of a resource through an api, if carefully note some methods require a parameter to run, we will start sending values and customize our provider..

Inside the package, there is a tool for create providers with all the default structure(enum, exceptions) and folders, from / run:

```sh
$ ./skeleton
```

And it'll show a list of available commads, for create new provider use:

```sh
$ ./skeleton provider:new
```

***create($provider)***

Normally used for the creation of a resource, in this case a post request to the address specified with the data you want to be sent, let's show an example where you want to create a test user using hmac authentificar signature for the request:

```php
// test/run.php

include(__DIR__ . '/../vendor/autoload.php');

use Skeleton\SDK\Common\Client,
	Providers\Test\TestProvider
	;

// Setting up the client with the configuration and credentials
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
print $test->create($data);

```

The `TestProvider` class contains a property inherited from `AbstractProvider` called skeleton, this property allows the request through guzzle, is a broker for each call using the signatures provided in settings travez of skeleton-sdk, methods available (signature) are:

 1. **Hmac(public & private) key**
 2. Oauth2 (todo) 
 3. ...

The configuration is composed of two main parts: the method to be used for authentication and base_url parameter containing the url base to form the request.

**5) Providers:**

For a provider may have the ability to request, this needs an instance of a client, above noticed as he passed as parameter when creating the same, this is required for all providers, then simply you pass parameters (data) to send the request either under or object and create the method would be responsible for making the request and the response retornarte.

```php
// src/provider/test/TestProvider.php

class TestProvider extends AbstractProvider implements ISupplier
{

	public function create($provider)
	{
		if (!is_array($provider))
			$provider = $this->skeleton->fragment($provider);

		return $this->skeleton->post('/users', $provider);
	}

	// ...
}

```

The first step in the above method is to verify the provider received is not an array, the post method of skeleton obligation instance receives an associative array where index is the field name with its respective value.

If the value is not an array, skeleton provides the ability to map the values of the properties of an object to an associative array with all their assigned values, this is done by the fragment method that receives an object and returns an array associative.

Then simply called the post content within the instance method of skeleton, using as first parameter the resource to consume and as second parameter the asosiative of data to send, from the side of test / run.php print output and have something like:


```
HTTP/1.1 200 OK
Content-Type: application/json; charset=UTF-8
access-control-allow-origin: *
Vary: Accept-Encoding
Date: Wed, 22 Apr 2015 13:42:39 GMT
Server: Google Frontend
Cache-Control: private
Alternate-Protocol: 80:quic,p=1,80:quic,p=1
Accept-Ranges: none
Transfer-Encoding: chunked

{
	"msg":"user created successfully"
}
```

Onsite returned by the post skeleton instance method object is of type [GuzzleHttp\Message\Response](http://api.guzzlephp.org/class-Guzzle.Http.Message.Response.html) providing all native methods for manejor guzzle response: D