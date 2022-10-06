<?php

namespace Max\JsonRpc;

use InvalidArgumentException;
use Max\Aop\Collector\AbstractCollector;
use Max\Di\Reflection;
use Max\JsonRpc\Attribute\RpcService;
use Max\Utils\Arr;
use ReflectionException;
use ReflectionMethod;

class RpcServiceCollector extends AbstractCollector
{
    protected static array $container = [];

    /**
     * @throws ReflectionException
     */
    public static function collectClass(string $class, object $attribute): void
    {
        if ($attribute instanceof RpcService) {
            $service = $attribute->name;
            if (isset(static::$container[$service])) {
                throw new InvalidArgumentException('Service \'' . $service . '\' has been registered');
            }
            foreach (Reflection::methods($class, ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
                static::$container[$service][$reflectionMethod->getName()] = [$class, $reflectionMethod->getName()];
            }
        }
    }

    public static function getService(string $name)
    {
        return Arr::get(static::$container, $name);
    }
}
