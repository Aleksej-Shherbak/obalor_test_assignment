<?php

namespace App\Infrastructure\Helpers;

use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionProperty;

class ReflectionHelper
{
    /**
     * Returns a collection of all public properties from the given class;
     *
     * @param string $fullNameSpace
     * @return Collection<string>
     * @throws \ReflectionException
     */
    public static function getClassPublicPropertiesNamesList(string $fullNameSpace): Collection
    {
        return collect((new ReflectionClass(new $fullNameSpace()))->getProperties(ReflectionProperty::IS_PUBLIC))
            ->map(function (ReflectionProperty $x) {
                return $x->name;
            });
    }
}
