<?php

namespace Differ;

function renderChangeTree($astTree): string
{
    $resultArray[] = "{" . PHP_EOL;
    $resultArray[] = renderChangeTreeBody($astTree);
    $resultArray[] = "}";

    $strResult = implode('', $resultArray);

    return $strResult;
}

function renderChangeTreeBody($astTree, $level = 1): string
{
    $renderedArray = array_reduce($astTree, function ($carry, $arr) use ($level) {
        $carry[] = doRenderMethodForTree($arr['type'], $arr, $level);
        return $carry;
    }, []);

    $strBodyResult = implode('', $renderedArray);

    return $strBodyResult;
}

function doRenderMethodForTree($method, $firstParam, $secondParam)
{
    $notUpdate = function ($item, $level) {
        $line = generateDepth($level - 1) . "    {$item['key']}: " . castValue($item['value'], $level) . PHP_EOL;
        return $line;
    };

    $update = function ($item, $level) {
        $line = generateDepth($level - 1) . "  - {$item['key']}: " . castValue($item['oldValue'], $level) . PHP_EOL .
            generateDepth($level - 1) . "  + {$item['key']}: " . castValue($item['value'], $level) . PHP_EOL;

        return $line;
    };

    $delUpdate = function ($item, $level) {
        $line = generateDepth($level - 1) . "  - {$item['key']}: " . castValue($item['value'], $level) . PHP_EOL;
        return $line;
    };

    $addUpdate = function ($item, $level) {
        $line = generateDepth($level - 1) . "  + {$item['key']}: " . castValue($item['value'], $level) . PHP_EOL;
        return $line;
    };

    $nestedTree = function ($item, $level) {
        $internalArray[] = generateDepth($level) . $item['key'] . ": {" . PHP_EOL;
        $internalArray[] = renderChangeTreeBody($item['value'], ($level + 1));
        $internalArray[] = generateDepth($level) . "}" . PHP_EOL;
        $treeAsString = implode('', $internalArray);
        return $treeAsString;
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

function castValue($value, $level = 1): string
{
    if (is_bool($value)) {
        $result = $value === true ? 'true' : 'false';
    } elseif (is_array($value)) {
        $jsonFromArr = json_encode($value, JSON_PRETTY_PRINT);
        $fixedJson = str_replace('"', '', $jsonFromArr);
        $depth = generateDepth($level);
        $result = str_replace("\n", "\n$depth", $fixedJson);
    } else {
        $result = $value;
    }
    return $result;
}

function generateDepth($level): string
{
    return $level > 0 ? '    ' . generateDepth($level - 1) : '';
}
