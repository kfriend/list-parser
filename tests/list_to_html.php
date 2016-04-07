<?php

use Raka\Sitearch\ListParser;

require __DIR__ . '/../src/Sitearch/ListParser.php';

$source = require(__DIR__ . '/list_ast.php');

$parser = new ListParser($source['source']);

echo $parser->toHtml();