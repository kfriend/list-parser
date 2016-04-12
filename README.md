# List Parser

A simple PHP library for parsing a tab or space indented list into a multidimensional array. Also includes formatters to convert a list into another format, such as HTML, Markdown, or JSON.

## Example
It turns a list like this:

```
Morbi in sem quis dui placerat ornare
    Pellentesque odio nisi
    Euismod in pharetra ad
        Ultricies in
        Diam
    Sed arcu Cras consequat
Praesent dapibus
    Neque id cursus faucibus
    Tortor neque egestas augue
Eu vulputate
```

Into this:
```
[
    [
        'label' => 'Morbi in sem quis dui placerat ornare',
        'indent' => 0,
        'children' => [
            [
                'label' => 'Pellentesque odio nisi',
                'indent' => 1,
                'children' => [
                ],
                'raw' => '  Pellentesque odio nisi',
                'index_global' => 1,
                'index_relative' => 0,
                'path' => '/Morbi in sem quis dui placerat ornare/Pellentesque odio nisi',
            ],
            [
                'label' => 'Euismod in pharetra ad',
                'indent' => 1,
                'children' => [
                    [
                        'label' => 'Ultricies in',
                        'indent' => 2,
                        'children' => [
                        ],
                        'raw' => '      Ultricies in',
                        'index_global' => 3,
                        'index_relative' => 0,
                        'path' => '/Morbi in sem quis dui placerat ornare/Euismod in pharetra ad/Ultricies in',
                    ],
                    [
                        'label' => 'Diam',
                        'indent' => 2,
                        'children' => [
                        ],
                        'raw' => '      Diam',
                        'index_global' => 4,
                        'index_relative' => 1,
                        'path' => '/Morbi in sem quis dui placerat ornare/Euismod in pharetra ad/Diam',
                    ],
                ],
                'raw' => '  Euismod in pharetra ad',
                'index_global' => 2,
                'index_relative' => 1,
                'path' => '/Morbi in sem quis dui placerat ornare/Euismod in pharetra ad',
            ],
            [
                'label' => 'Sed arcu Cras consequat',
                'indent' => 1,
                'children' => [
                ],
                'raw' => '  Sed arcu Cras consequat',
                'index_global' => 5,
                'index_relative' => 2,
                'path' => '/Morbi in sem quis dui placerat ornare/Sed arcu Cras consequat',
            ],
        ],
        'raw' => 'Morbi in sem quis dui placerat ornare',
        'index_global' => 0,
        'index_relative' => 0,
        'path' => '/Morbi in sem quis dui placerat ornare',
    ],
    [
        'label' => 'Praesent dapibus',
        'indent' => 0,
        'children' => [
            [
                'label' => 'Neque id cursus faucibus',
                'indent' => 1,
                'children' => [
                ],
                'raw' => '  Neque id cursus faucibus',
                'index_global' => 7,
                'index_relative' => 0,
                'path' => '/Praesent dapibus/Neque id cursus faucibus',
            ],
            [
                'label' => 'Tortor neque egestas augue',
                'indent' => 1,
                'children' => [
                ],
                'raw' => '  Tortor neque egestas augue',
                'index_global' => 8,
                'index_relative' => 1,
                'path' => '/Praesent dapibus/Tortor neque egestas augue',
            ],
        ],
        'raw' => 'Praesent dapibus',
        'index_global' => 6,
        'index_relative' => 1,
        'path' => '/Praesent dapibus',
    ],
    [
        'label' => 'Eu vulputate',
        'indent' => 0,
        'children' => [
        ],
        'raw' => 'Eu vulputate',
        'index_global' => 9,
        'index_relative' => 2,
        'path' => '/Eu vulputate',
    ],
]
```
