<?php

namespace App\Controller;

use DI\Container;
use Psr\Container\ContainerInterface;

/**
 * Class AbstractController
 * @package App\Controller
 *
 * @property \Monolog\Logger logger
 * @property \Slim\Views\PhpRenderer view
 * @property \Slim\Collection settings
 */
abstract class AbstractController
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AbstractController constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $property
     * @return mixed
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __get($property)
    {
        if ($this->container->has($property)) {
            return $this->container->get($property);
        }

        return null;
    }
}