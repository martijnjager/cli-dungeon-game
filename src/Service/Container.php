<?php

namespace CliGame\Service;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use RuntimeException;

/**
 * Very small singleton container with lazy autowiring.
 *
 * Usage:
 * $c = Container::getInstance();
 * $c->bind(Foo::class, fn(Container $c) => new Foo($c->get(Bar::class)));
 * $foo = $c->get(Foo::class); // cached singleton
 */
class Container
{
    private static ?self $instance = null;

    /** @var array<string, callable> */
    private array $bindings = [];

    /** @var array<string, mixed> */
    private array $instances = [];

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Bind a factory closure for an abstract/class name.
     */
    public function bind(string $abstract, callable $factory): void
    {
        $this->bindings[$abstract] = $factory;
    }

    /**
     * Register a concrete instance for an abstract/class name.
     */
    public function instance(string $abstract, mixed $object): void
    {
        $this->instances[$abstract] = $object;
    }

    /**
     * Resolve (and cache) a class/abstract name.
     */
    public function get(string $abstract): mixed
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (isset($this->bindings[$abstract])) {
            $resolved = ($this->bindings[$abstract])($this);
            $this->instances[$abstract] = $resolved;
            return $resolved;
        }

        $resolved = $this->autoResolve($abstract);
        $this->instances[$abstract] = $resolved;

        return $resolved;
    }

    /**
     * Attempt to autowire a concrete class by reflecting its constructor.
     *
     * @throws RuntimeException
     */
    private function autoResolve(string $class): mixed
    {
        try {
            $reflection = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw new RuntimeException("Cannot resolve {$class}: " . $e->getMessage(), 0, $e);
        }

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException("Class {$class} is not instantiable.");
        }

        $constructor = $reflection->getConstructor();
        if ($constructor === null || $constructor->getNumberOfParameters() === 0) {
            return new $class();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $dependencies[] = $this->get($type->getName());
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
                continue;
            }

            if ($parameter->allowsNull()) {
                $dependencies[] = null;
                continue;
            }

            throw new RuntimeException("Cannot resolve parameter \${$parameter->getName()} for {$class}");
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
