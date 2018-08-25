<?php

namespace Differ;

use function Funct\Collection\union;

function genAST($firstArray, $secondArray): array
{
    $unitedArray = union(array_keys($firstArray), array_keys($secondArray));
    return array_map(function ($key) use ($firstArray, $secondArray, $unitedArray) {
        if (array_key_exists($key, $firstArray) && array_key_exists($key, $secondArray)) {
            if (!is_array($firstArray[$key]) && !is_array($secondArray[$key])) {
                if ($firstArray[$key] === $secondArray[$key]) {
                    return ['key' => $key, 'value' => $firstArray[$key], 'type' => 'notUpdate'];
                } else {
                    return ['key' => $key, 'value' => $secondArray[$key], 'oldValue' => $firstArray[$key],
                        'type' => 'update'];
                }
            } else {
                return ['key' => $key,
                    'value' => genAST($firstArray[$key], $secondArray[$key]),
                    'type' => 'nestedTree'];
            }
        } elseif (array_key_exists($key, $firstArray)) {
            return ['key' => $key, 'value' => $firstArray[$key], 'type' => 'delete'];
        } else {
            return ['key' => $key, 'value' => $secondArray[$key], 'type' => 'add'];
        }
    }, $unitedArray);
}
