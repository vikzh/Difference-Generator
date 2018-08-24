<?php

namespace Differ;

function render($astTree, $format)
{
    $renderMethods = [
        'changeTree' => 'Differ\renderChangeTree',
        'plain' => 'Differ\renderPlain',
    ];

    return $renderMethods[$format]($astTree);
}