<?php

namespace Kfriend\ListParser;

class HtmlFormatter extends Formatter
{
    protected function formatTree()
    {
        // The wrapper function, which turns an array w/ children into an unordered list
        $wrapper = function($node) use (&$wrapper) {
            $markup = "<li>{$node['label']}";

            if (!empty($node['children'])) {
                $markup .= '<ul>';
                foreach ($node['children'] as $child) {
                    $markup .= $wrapper($child);
                }
                $markup .= '</ul>';
            }

            return $markup .'</li>';
        };

        $output = '<ul>';

        foreach ($this->parser->getTree() as $node) {
            // Wrap children into <ul>s
            $output .= $wrapper($node);
        }

        $output .= '</ul>';

        return $output;
    }
}
