<?php

namespace Differ;

function generateDiff($fistFileName, $secondFileName, $fileFormat): string
{
    $firstParsedArray = stringParse(file_get_contents($fistFileName), getExtension($fistFileName));
    $secondParsedArray = stringParse(file_get_contents($secondFileName), getExtension($secondFileName));

    $astTree = genAST($firstParsedArray, $secondParsedArray);

    $resultDiff = render($astTree, $fileFormat);

    return $resultDiff;
}

function getExtension($filename)
{
    $path_info = pathinfo($filename);
    return $path_info['extension'];
}
