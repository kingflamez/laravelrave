<?php

namespace Tests\Concerns;

use ReflectionClass;
use ReflectionProperty;

trait ExtractProperties {

    /**
     * Extract "a" property from a class.
     *
     * @param \stdClass $class
     * @param string $name Property name.
     * @return array        Extracted name and value of property.
     * @throws \ReflectionException
     */
    function extractProperty($class, $name) {

        $reflector = new ReflectionClass($class);
        $property = $reflector->getProperty($name);
        $property->setAccessible(true);

        return [
            "name" => $property->getName(),
            "value" => $property->getValue($class),
        ];
    }

    /**
     * Set property of class.
     *
     * @param \stdClass $class
     * @param string $name Property name
     * @param mixed $value
     * @throws \ReflectionException
     */
    function setProperty($class, $name, $value = null) {

        $reflector = new ReflectionClass($class);
        $property = $reflector->getProperty($name);
        $property->setAccessible(true);

        $property->setValue($class, $value);
    }
}
