<?php

namespace Kfriend\ListParser;

class MarkdownFormatter extends Formatter
{
    public $indentationSize = 4;

    protected function formatTree()
    {
        $output = '';
        $indent = str_repeat(' ', $this->indentationSize);

        foreach ($this->parser->getFlatTree() as $node) {
            $output .= str_repeat($indent, $node['indent'])."- {$node['label']}";
            $output .= "\n";
        }

        return $output;
    }
}
