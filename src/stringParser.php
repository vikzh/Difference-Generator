<?php

namespace Differ;

use Symfony\Component\Yaml\Yaml;

const VALID_EXTENSIONS = ['json', 'yml'];

function stringParse($fileContent, $fileExtension)
{
    $jsonParse = function ($fileContent) {
        return json_decode($fileContent, true);
    };

    $yamlParse = function ($fileContent) {
        return Yaml::parse($fileContent, Yaml::PARSE_OBJECT);
    };

    $extensionHendler = ['json' => $jsonParse, 'yml' => $yamlParse];

    return $extensionHendler[$fileExtension]($fileContent);
}
