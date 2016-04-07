<?php

namespace Kfriend\ListParser;

class JsonFormatter extends Formatter
{
    public $prettyPrint = true;

    protected function formatTree()
    {
        $prettyPrint = $this->prettyPrint;
        
        if ($prettyPrint && version_compare(PHP_VERSION, '5.3', 'lt') < 1) {
            $prettyPrint = false;
        }

        return json_encode($this->parser->getTree(), ($prettyPrint ? JSON_PRETTY_PRINT : null));
    }
}
