<?php

namespace Differ;

function render($astTree) : string
{
    $resultArray[] = "{" . PHP_EOL;
    $resultArray[] = renderBody($astTree);
    $resultArray[] = "}";

    $strResult = implode('', $resultArray);

    return $strResult;
}

function renderBody($astTree, $level = 1) : string
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
        $internalArray[] = renderBody($item['value'], ($level + 1));
        $internalArray[] = generateDepth($level) . "}" . PHP_EOL;
        $str = implode('', $internalArray);
        return $str;
    };

    $actionTypes = [
        'notUpdate' => $notUpdate,
        'update' => $update,
        'delete' => $delUpdate,
        'add' => $addUpdate,
        'nestedTree' => $nestedTree
    ];

    $renderedArray = array_reduce($astTree, function ($carry, $arr) use ($actionTypes, $level) {
        $carry[] = $actionTypes[$arr['type']]($arr, $level);
        return $carry;
    }, []);


    $strResult = implode('', $renderedArray);

    return $strResult;
}

function castValue($value, $level = 1) : string
{
    if (is_bool($value)) {
        if ($value == true) {
            $result = 'true';
        } else {
            $result = 'false';
        }
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

function boolAsString($value) : string
{
    return $value ? 'true' : 'false';
}

function generateDepth($level) : string
{
    return $level > 0 ? '    ' . generateDepth($level - 1) : '';
}
