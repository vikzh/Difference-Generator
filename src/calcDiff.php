<?php

namespace Differ;

const VALID_FORMATS = ['json', 'yml'];

function generateDiff($fistFileName, $secondFileName, $fileFormat): array
{
    $resultDiff = [];
    if (in_array($fileFormat, VALID_FORMATS)) {
        switch ($fileFormat) {
            case 'json':
                $firstFile = new JsonFile($fistFileName);
                $secondFile = new JsonFile($secondFileName);
                $resultDiff = calculateDiff($firstFile, $secondFile);
                break;
            case 'yml':
                $firstFile = new YamlFile($fistFileName);
                $secondFile = new YamlFile($secondFileName);
                $resultDiff = calculateDiff($firstFile, $secondFile);
                break;
        }
    }
    return $resultDiff;
}

function calculateDiff(File $firstFile, File $secondFile): array
{
    $firstFileArrCont = $firstFile->fileParse();
    $secondFileArrCont = $secondFile->fileParse();
    $uniqValFirstFile = array_reduce(
        array_keys($firstFileArrCont),
        function ($carry, $key) use ($firstFileArrCont, $secondFileArrCont) {
            if (array_key_exists($key, $secondFileArrCont)) {
                if ($firstFileArrCont[$key] === $secondFileArrCont[$key]) {
                    $carry[] = "  $key: " . changeIfBool($firstFileArrCont[$key]);
                } else {
                    $carry[] = "+ $key: " . changeIfBool($secondFileArrCont[$key]);
                    $carry[] = "- $key: " . changeIfBool($firstFileArrCont[$key]);
                }
            } else {
                $carry[] = "- $key: " . changeIfBool($firstFileArrCont[$key]);
            }
            return $carry;
        },
        []
    );

    $diffArr = array_diff_key($secondFileArrCont, $firstFileArrCont);

    $uniqValSecondFile = array_map(function ($key) use ($diffArr) {
        return "+ $key: " . changeIfBool($diffArr[$key]);
    }, array_keys($diffArr));

    return array_merge($uniqValFirstFile, $uniqValSecondFile);
}

function changeIfBool($value)
{
    return is_bool($value) ? boolAsString($value) : $value;
}

function boolAsString($value)
{
    return $value ? 'true' : 'false';
}
