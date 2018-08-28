<?php

namespace Differ;

function render($astTree, $format)
{
    $renderMethods = [
        'changeTree' => 'Differ\renders\renderChangeTree',
        'plain' => 'Differ\renders\renderPlain',
        'json' => 'Differ\renders\renderJson',
    ];
    return $renderMethods[$format]($astTree);
}
