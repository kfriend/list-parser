<?php

// @todo
// - Add tree node paths to testing. Currently removed by tree_prep_for_comparison()
// - Add "toMarkdown" and "toHtml" tests, for list parser

use Kfriend\ListParser\ListParser;

require __DIR__.'/helpers.php';
require __DIR__.'/../src/ListParser/ListParser.php';

$source = require(__DIR__.'/list_ast.php');

assert_options(ASSERT_ACTIVE, true);
assert_options(ASSERT_WARNING, false);
assert_options(ASSERT_BAIL, true);
assert_options(ASSERT_CALLBACK, function($file, $line, $code, $desc) {
    echo "
Assertion Failed:
Desc: {$desc}
File: {$file}
Line: {$line}
Code: {$code}
";

});

$parser = new ListParser($source['source']);
$treeParsed = $parser->getTree();
$tree = $source['array'];

// We'll strip out values we don't want to use for comparison, such as the "source" value.
// It's currently normalized within the list parser, so it will never match the raw source
tree_prep_for_comparison($tree);
tree_prep_for_comparison($treeParsed);

// Sort the trees, which produces better output for diffing
tree_sort($tree);
tree_sort($treeParsed);

// Dump the trees, to allow running `diff` on them
file_put_contents(__DIR__.'/tmp/dump_tree', print_r($tree, true));
file_put_contents(__DIR__.'/tmp/dump_tree_parsed', print_r($treeParsed, true));

assert(
    $treeParsed === $tree,
    'Does the parsed tree match the expected tree?'
);

echo " \033[0;32m✓ All tests passed!\033[0m\n";
