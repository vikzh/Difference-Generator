<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\generateDiff;

class CalcDiffTest extends TestCase
{
    public function testCalcDiff()
    {
        $correctData1 = file_get_contents("tests/data/correctData1");
        $this->assertEquals(
            $correctData1,
            generateDiff(
                'tests/data/before.json',
                'tests/data/after.json',
                'json'
            )
        );

        $this->assertEquals(
            $correctData1,
            generateDiff(
                'tests/data/before.yml',
                'tests/data/after.yml',
                'yml'
            )
        );

        $correctData2 = file_get_contents("tests/data/correctData2");
        $this->assertEquals(
            $correctData2,
            generateDiff(
                'tests/data/before2.json',
                'tests/data/after2.json',
                'json'
            )
        );
    }
}
