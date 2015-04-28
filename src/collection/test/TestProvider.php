<?php

namespace Provider\Collection\Test;

use Skeleton\SDK\Common\Supplier\ISupplier;
use Skeleton\SDK\Common\Client;
use Skeleton\SDK\Providers\AbstractProvider;

/**
 * Provider for resource /test
 */            
class TestProvider extends AbstractProvider implements ISupplier
{
    /**
     * Create new resource
     * 
     * @param object|array  New resource data to create
     * @return GuzzleHttp\Message\Response Response from guzzle
     */
    public function create($entity)
    {
        return $this->skeleton->post('/test', $entity);
    }

    /**
     * Get all from resource
     * 
     * @return GuzzleHttp\Message\Response Response from guzzle
     */
    public function read()
    {
        return $this->skeleton->get('/test');
    }

    /**
     * Update the resource
     *
     * @param string  Resource Id
     * @param object|array  Data to update
     * @return GuzzleHttp\Message\Response Response from guzzle
     */
    public function update($id, $entity)
    {
        return $this->skeleton->put('/test/'.$id, $entity);
    }

    /**
     * Delete a resource
     *
     * @param string  Resource id
     * @return GuzzleHttp\Message\Response Response from guzzle
     */
    public function delete($id)
    {
        return $this->skeleton->delete('/test/'.$id);
    }

    /**
     * Get a resource by id
     *
     * @param string  Resource id
     * @return GuzzleHttp\Message\Response  Response from guzzle
     */
    public function getById($id)
    {
        return $this->skeleton->get('/test/'.$id);
    }    
}