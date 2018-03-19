<?php

namespace Tests\Concerns;

use ReflectionClass;
use ReflectionProperty;

trait ExtractProperties {

    /**
     * Extract "a" property from a class.
     *
     * @param  stdClass $class
     * @param  string $name  Property name.
     * @return array        Extracted name and value of property.
     */
    function extractProperty($class, string $name) {

        $reflector = new ReflectionClass($class);
        $property = $reflector->getProperty($name);
        $property->setAccessible(true);

        return [
            "name" => $property->getName(),
            "value" => $property->getValue($class),
        ];
    }

    // function extractProperties($class, ...$names) {

    //     $reflector = new ReflectionClass($class);
    //     $property = $reflector->getProperties(ReflectionProperty::IS_PROTECTED);

    //     $values = array_map(function ($value) use ($class, $property){
    //         $reflected = array_filter($property, function(&$obj) use ($value) {
    //             return $obj->getName() === $value;
    //         });

    //         $reflected = array_values($reflected);

    //         $reflected = array_pop($reflected);
    //         $reflected->setAccessible(true);
    //         die(gettype($reflected));

    //         return $reflected->getValue($class);
    //     }, $names);

    //     return $values;
    // }
}
