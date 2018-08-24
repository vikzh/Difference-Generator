<?php

namespace Differ;

function renderPlain($astTree): string
{
    $strResult = renderPlainBody($astTree);

    return $strResult;
}

function renderPlainBody($astTree, $path = ''): string
{
    $renderedArray = array_reduce($astTree, function ($carry, $arr) use ($path) {
        $carry[] = doRenderMethodForPlain($arr['type'], $arr, $path);
        return $carry;
    }, []);

    $strBodyResult = implode('', $renderedArray);

    return $strBodyResult;
}

function doRenderMethodForPlain($method, $firstParam, $secondParam)
{
    $notUpdate = function ($item) {
        return '';
    };

    $update = function ($item, $parent) {
        $line = "Property '$parent{$item['key']}' was changed. From '" . castValuePlain($item['oldValue']) . "' to '"
            . castValuePlain($item['value']) . "'" . PHP_EOL;
        return $line;
    };

    $delUpdate = function ($item, $parent) {
        $line = "Property '$parent{$item['key']}' was removed" . PHP_EOL;
        return $line;
    };

    $addUpdate = function ($item, $parent) {
        $line = "Property '$parent{$item['key']}' was added with value: '" . castValuePlain($item['value']) . "'" . PHP_EOL;
        return $line;
    };

    $nestedTree = function ($item, $parent) {
        $line = renderPlainBody($item['value'], "$parent{$item['key']}.");
        return $line;
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

function checkNewLineSymbol($strResult)
{
    $lengthOfResult = strlen($strResult);
    if ($lengthOfResult >= 2) {
        if (($strResult[$lengthOfResult - 2] === '\\') && ($strResult[$lengthOfResult - 2] === 'n')) {
            array_pop($strResult);
            array_pop($strResult);
        }
    }
    return $strResult;
}