<?php

namespace Differ;

function generateDiff($fistFileName, $secondFileName, $fileFormat): string
{
    $firstParsedArray = stringParse(file_get_contents($fistFileName), $fileFormat);
    $secondParsedArray = stringParse(file_get_contents($secondFileName), $fileFormat);

    $astTree = genAST($firstParsedArray, $secondParsedArray);

    $resultDiff = render($astTree);

    return $resultDiff;
}
