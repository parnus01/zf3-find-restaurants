<?php

namespace RestaurantsGuide\Controller;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class RestaurantsApiFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|RestaurantsApiController
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("Config");
        if (!isset($config['google-place-api-key']) || !is_string($config['google-place-api-key'])) {
            throw new \Exception('No "service_name_api" found in the configuration');
        }
        return new RestaurantsApiController($config['google-place-api-key']);
    }
}
