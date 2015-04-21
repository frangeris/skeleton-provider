<?php

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