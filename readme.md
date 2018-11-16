[![Maintainability](https://api.codeclimate.com/v1/badges/9a4ffde9b9a385ce152b/maintainability)](https://codeclimate.com/github/vkzhuk/Difference-Generator/maintainability)
[![Build Status](https://travis-ci.org/vkzhuk/Difference-Generator.svg?branch=master)](https://travis-ci.org/vkzhuk/Difference-Generator)

#Difference Generator

## Installation
#### - As a Tool
````bash
composer create-project vkzhuk/diff_calc
````
#### - As a Library
````bash
composer require vkzhuk/diff_calc
````

## Usage Pattern
````bash
Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  --format <fmt>                Report format [default: pretty]
````

## Supported formats
### 1. Formats of input files
* json
* yml
### 2. Formats of report
* changeTree
* plain
* json

## Examples output
### Input files

**- before.json**
```json
{
  "common": {
    "setting1": "Value 1",
    "setting2": "200",
    "setting3": true,
    "setting6": {
      "key": "value"
    }
  },
  "group1": {
    "baz": "bas",
    "foo": "bar"
  },
  "group2": {
    "abc": "12345"
  }
}
```
**- after.json**
```json
{
  "common": {
    "setting1": "Value 1",
    "setting3": true,
    "setting4": "blah blah",
    "setting5": {
      "key5": "value5"
    }
  },

  "group1": {
    "foo": "bar",
    "baz": "bars"
  },

  "group3": {
    "fee": "100500"
  }
}
```
### Change Tree
```
{
    common: {
        setting1: Value 1
      - setting2: 200
        setting3: true
      - setting6: {
            key: value
        }
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
    }
    group1: {
      - baz: bas
      + baz: bars
        foo: bar
    }
  - group2: {
        abc: 12345
    }
  + group3: {
        fee: 100500
    }
}
```

### Plain
```
Setting "common.setting2" deleted.
Setting "common.setting4" added with value "blah blah".
Setting "group1.baz" changed from "bas" to "bars".
Section "group2" deleted.
```
###Example of work
[![asciicast](https://asciinema.org/a/198476.png)](https://asciinema.org/a/198476)
