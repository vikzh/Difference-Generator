<?php

namespace Differ;

function render($astTree)
{
    echo var_dump($astTree);
    $notUpdate = function ($item) {
        $line = "  {$item['key']}: " . castValue($item['value']);
        return $line;
    };

    $oldUpdate = function ($item) {
        $line = "- {$item['key']}: " . castValue($item['oldValue']);
        return $line;
    };

    $newUpdate = function ($item) {
        $line = "+ {$item['key']}: " . castValue($item['value']);
        return $line;
    };

    $delUpdate = function ($item) {
        $line = "- {$item['key']}: " . castValue($item['value']);
        return $line;
    };

    $addUpdate = function ($item) {
        $line = "+ {$item['key']}: " . castValue($item['value']);
        return $line;
    };

    $nestedTree = function ($array) {
        return json_encode($array);
    };

    $actionTypes = [
        'notUpdate' => $notUpdate,
        'oldUpdate' => $oldUpdate,
        'newUpdate' => $newUpdate,
        'delete' => $delUpdate,
        'add' => $addUpdate,
        'nestedTree' => $nestedTree
    ];

    $renderedArray = array_reduce($astTree, function ($carry, $arr) use ($actionTypes) {
        if ($arr['type'] !== 'update') {
            $carry[] = $actionTypes[$arr['type']]($arr);
            return $carry;
        } else {
            $carry[] = $actionTypes['newUpdate']($arr);
            $carry[] = $actionTypes['oldUpdate']($arr);
            return $carry;
        }
    }, []);

    return $renderedArray;
}

function castValue($value)
{
    return is_bool($value) ? boolAsString($value) : $value;
}

function boolAsString($value)
{
    return $value ? 'true' : 'false';
}
