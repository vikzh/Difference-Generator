<?php

namespace Differ\renders;

function renderJson($astTree)
{
    return json_encode($astTree);
}
