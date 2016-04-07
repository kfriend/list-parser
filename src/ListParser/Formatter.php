<?php

namespace Kfriend\ListParser;

abstract class Formatter
{
    protected $parser;
    protected $cache = null;

    abstract protected function formatTree();

    public function __construct(ListParser $parser)
    {
        $this->parser = $parser;
    }

    public function format()
    {
        if ($this->cache === null) {
            $this->cache = trim($this->formatTree($this->parser->getTree()));
        }

        return $this->cache;
    }
}
