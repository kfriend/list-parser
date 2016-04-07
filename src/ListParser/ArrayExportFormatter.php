<?php

namespace Kfriend\ListParser;

class ArrayExportFormatter extends Formatter
{
    protected function formatTree()
    {
        return var_export($this->parser->getTree(), true);
    }
}
