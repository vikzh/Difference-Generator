<?php

namespace Differ;

function generateDiff($fistFileName, $secondFileName, $format): string
{
    $firstParsedArray = stringParse(file_get_contents($fistFileName), getExtension($fistFileName));
    $secondParsedArray = stringParse(file_get_contents($secondFileName), getExtension($secondFileName));
    $astTree = genAST($firstParsedArray, $secondParsedArray);
    return render($astTree, $format);
}

function getExtension($filename)
{
    return pathinfo($filename)['extension'];
}
