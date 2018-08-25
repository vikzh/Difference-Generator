<?php

namespace Differ;

function render($astTree, $format)
{
    $renderMethods = [
        'changeTree' => 'Differ\renderChangeTree',
        'plain' => 'Differ\renderPlain',
        'json' => 'Differ\renderJson',
    ];
    return $renderMethods[$format]($astTree);
}
