<?php

namespace App\Console;


use Psr\Container\ContainerInterface;

/**
 * Class AbstractController
 * @package App\Controller
 *
 * @property \Monolog\Logger logger
 * @property \Slim\Views\PhpRenderer view
 * @property \Slim\Collection settings
 */
abstract class AbstractCliRequest
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AbstractController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }

        return null;
    }
}