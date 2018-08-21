<?php

namespace Differ;

const VALID_FORMATS = ['json'];

function calcDiff($fistFileName, $secondFileName, $fileFormat): array
{
    $resultDiff = [];
    if (isCorrectInput($fistFileName, $secondFileName, $fileFormat)) {
        switch ($fileFormat) {
            case 'json':
                $resultDiff = calcJsonDiff($fistFileName, $secondFileName);
                break;
        }
    }
    return $resultDiff;
}

function isCorrectInput($firstFileName, $secondFileName, $fileFormat): bool
{
    try {
        if (!file_exists($firstFileName)) {
            throw new \Exception('First File  does not exist');
        } elseif (!file_exists($secondFileName)) {
            throw new \Exception('First File  does not exist');
        } elseif (!in_array($fileFormat, VALID_FORMATS)) {
            throw new \Exception('First File  does not exist');
        }
    } catch (\Exception $e) {
        echo $e . PHP_EOL;
    }
    return true;
}

function calcJsonDiff($firstFileName, $secondFileName): array
{
    try {
        $firstFileArrCont = json_decode(file_get_contents($firstFileName), true);
        $secondFileArrCont = json_decode(file_get_contents($secondFileName), true);
        $resultDiff = [];

        foreach ($firstFileArrCont as $key => $value) {
            if (array_key_exists($key, $secondFileArrCont)) {
                if ($value === $secondFileArrCont[$key]) {
                    $resultDiff[] = "  $key: " . (is_bool($value) ? boolAsString($value) : $value);
                } else {
                    $resultDiff[] = "+ $key: " . (is_bool($secondFileArrCont[$key])
                            ? boolAsString($secondFileArrCont[$key]) : $secondFileArrCont[$key]);
                    $resultDiff[] = "- $key: " . (is_bool($value) ? boolAsString($value) : $value);
                }
            } else {
                $resultDiff[] = "- $key: " . (is_bool($value) ? boolAsString($value) : $value);
            }
        }

        $diffArr = array_diff_key($secondFileArrCont, $firstFileArrCont);
        foreach ($diffArr as $key => $value) {
            $resultDiff[] = "+ $key: " . (is_bool($value) ? boolAsString($value) : $value);
        }
        return $resultDiff;
    } catch (\Exception $e) {
        echo $e . PHP_EOL;
    }
    return [];
}

function boolAsString($value)
{
    if ($value) {
        return 'true';
    } else {
        return 'false';
    }
}
