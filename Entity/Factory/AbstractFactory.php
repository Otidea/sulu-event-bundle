<?php

declare(strict_types = 1);

namespace Otd\SuluEventBundle\Entity\Factory;

class AbstractFactory
{
    /**
     * Return the value of a key in a given data-array.
     * If the given key does not exist, the given default value is returned.
     *
     * @param array $data
     * @param string $key
     * @param string|null $default
     *
     * @return string|null
     */
    protected function getProperty(array $data, string $key, string $default = null): mixed
    {
        return (\array_key_exists($key, $data) && null !== $data[$key]) ? $data[$key] : $default;
    }
}
