<?php

namespace Differ;

use Symfony\Component\Yaml\Yaml;

function stringParse($content, $contentExtension)
{
    $jsonParse = function ($content) {
        return json_decode($content, true);
    };

    $yamlParse = function ($content) {
        return Yaml::parse($content, Yaml::PARSE_OBJECT);
    };

    $extensionHandler = ['json' => $jsonParse, 'yml' => $yamlParse];
    return $extensionHandler[$contentExtension]($content);
}
