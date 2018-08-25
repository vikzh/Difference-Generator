<?php

namespace Differ;

function renderPlain($astTree): string
{
    return renderPlainBody($astTree);
}

function renderPlainBody($astTree, $path = ''): string
{
    $renderedArray = array_reduce($astTree, function ($carry, $arr) use ($path) {
        $carry[] = doRenderMethodForPlain($arr['type'], $arr, $path);
        return $carry;
    }, []);
    return implode('', $renderedArray);
}

function doRenderMethodForPlain($method, $firstParam, $secondParam)
{
    $notUpdate = function ($item) {
        return '';
    };

    $update = function ($item, $parent) {
        return "Property '$parent{$item['key']}' was changed. From '" . castValuePlain($item['oldValue']) . "' to '"
            . castValuePlain($item['value']) . "'" . PHP_EOL;
    };

    $delUpdate = function ($item, $parent) {
        return "Property '$parent{$item['key']}' was removed" . PHP_EOL;
    };

    $addUpdate = function ($item, $parent) {
        return "Property '$parent{$item['key']}' was added with value: '" .
            castValuePlain($item['value']) . "'" . PHP_EOL;
    };

    $nestedTree = function ($item, $parent) {
        return renderPlainBody($item['value'], "$parent{$item['key']}.");
    };

    $actionTypes = [
        'notUpdate' => $notUpdate,
        'update' => $update,
        'delete' => $delUpdate,
        'add' => $addUpdate,
        'nestedTree' => $nestedTree
    ];
    return $actionTypes[$method]($firstParam, $secondParam);
}

function castValuePlain($value, $level = 1): string
{
    if (is_bool($value)) {
        $result = $value === true ? 'true' : 'false';
    } elseif (is_array($value)) {
        $result = 'complex value';
    } else {
        $result = $value;
    }
    return $result;
}
