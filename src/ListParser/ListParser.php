<?php

namespace Kfriend\ListParser;

class ListParser
{
    protected $spaceIndent = 4;

    protected $rawSource;

    protected $flatTree = array();
    protected $tree = array();

    protected $itemPathSeparater = '/';

    public function __construct($source)
    {
        $this->rawSource = $source;
        $this->parse();
    }

    protected function parse()
    {
        $source = trim($this->rawSource);

        // Our final tree
        $tree = array();

        // Remove empty lines
        // Credit: http://stackoverflow.com/a/709684
        $source = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $source);

        // Normalize line endings to \n
        $source = $this->normalizeEndings($source);

        // Convert leading spaces to tabs
        $source = $this->normalizeIndents($source, $this->spaceIndent);

        // Split source into an array
        $source = explode("\n", $source);

        // Regex which detects indentation level
        $indentRegex = "/(?:^|\G)\t/";

        // Convert source lines into arrays, so we can track additional info about each node
        array_walk($source, function(&$line, $index) {
            $raw = $line;

            $line = array(
                // The raw source
                'raw' => $raw,
                'label' => $this->normalizeLabelContent($raw),
                'indent' => null,
                'tree_node' => null,
                'index' => $index,
            );
        });

        // We'll keep track of the previous indentation level so know if an item is supposed to be
        // a child or not
        $prevIndent = 0;

        // Track the "global" index, which could be useful
        $globalIndex = 0;

        foreach ($source as $sourceIndex => &$line) {
            // What's the indentation level?
            $indent = preg_match_all($indentRegex, $line['raw']);

            // Normalize indentation
            if (!$indent) {
                $indent = 0;
            }

            // Check if the indentation is excessive (i.e greater than the prev node by more than 1)
            // and normalize it to prev + 1
            if ($indent !== 0 && $prevIndent !== 0 && $indent - $prevIndent > 1) {
                $indent = $prevIndent + 1;
            }

            $item = array(
                // Normalized content
                'label' => $line['label'],
                // Indentation level
                'indent' => $indent,
                // If there are any child nodes, they'll be associated here
                'children' => array(),
                // The raw source for this line
                'raw' => $line['raw'],
                // The "global" index value for this node.
                'index_global' => $globalIndex,
                // The index value, relative to the sibling values. Will be populated later.
                'index_relative' => null,

                // These are used to create relationships, allowing us to create the nested
                // structure from a flat list. They will be removed before returning the tree output.
                'parent' => null,
                'source_node' => &$line,
            );

            // Another relationship between the flat tree, pointing to the current item
            $line['tree_node'] = &$item;

            // Is this a top-level node?
            if ($indent === 0) {
                // Set the relative index value. Since the index is 0-based, and count() will return
                // 1-based, we can just use the count() value, before adding the item to the tree
                $item['index_relative'] = count($tree);
                $tree[] = &$item;
            }

            // Is this a sibling node to the previous node?
            elseif ($indent === $prevIndent) {
                // If have the same indentation as the previous node, then it's a sibling node,
                // so we'll just tack this on to its parent
                $item['parent'] = &$source[$sourceIndex - 1]['tree_node']['parent'];
                $item['parent']['children'][] = &$item;
            }

            // Is this a child of the previous node?
            elseif ($indent > $prevIndent) {
                // Yes, so we'll just tack this onto the previous node's children, and be on our way
                $item['parent'] = &$source[$sourceIndex - 1]['tree_node'];
                $item['parent']['children'][] = &$item;
            }

            // We'll need to climb up the source tree until we find a matching depth, and attach
            // this node to it
            else {
                for ($i = $sourceIndex - 1; $i >= 0; $i--) {
                    if ($source[$i]['tree_node']['indent'] === $indent) {
                        $item['parent'] = &$source[$i]['tree_node']['parent'];
                        $item['parent']['children'][] = &$item;

                        break;
                    }
                }
            }

            // Add the relative index value. Top-level items should already have theirs set
            if ($indent !== 0 && $item['index_relative'] === null && !empty($item['parent'])) {
                // Just use the parent's children count, minus 1, since we've already added this item
                // to the array, and index is 0-based, where count() is 1-based.
                $item['index_relative'] = count($item['parent']['children']) - 1;
            }

            $prevIndent = $indent;
            $item['source_node']['indent'] = $indent;

            // Create the path, which is useful for some applications
            $path = '';
            if (!empty($item['parent'])) {
                $path = $item['parent']['path'];
            }

            $item['path'] = $path.$this->itemPathSeparater.$item['label'];

            // Bump the global index
            $globalIndex++;

            // Prevent issues with references in PHP foreach
            unset($line, $item);
        }

        // Normalize tree by stripping unnecessary values
        foreach ($source as &$item) {
            unset(
                // Deref tree node from source node
                $item['tree_node']['parent'],
                $item['tree_node']['source_node'],

                // Deref source node from tree node
                $item['tree_node']
            );

            // Prevent issues with references in PHP foreach
            unset($item);
        }

        // Encode and decode to get rid of references
        //
        // @todo -- not sure if this is the best method for this? Probably not the fastest.
        // Also, combining both the two json_encode/decode into a single call MAY improve speed. We'll
        // need to benchmark. Alternative methods could be serialization, or iterating the tree, removing refs.
        $this->tree = json_decode(json_encode($tree), true);
        $this->flatTree = json_decode(json_encode($source), true);
    }

    public function getTree()
    {
        return $this->tree;
    }

    public function getFlatTree()
    {
        return $this->flatTree;
    }

    protected function normalizeLabelContent($line)
    {
        return trim($line);
    }

    protected function normalizeEndings($value, $to = "\n")
    {
        $opposite = ($to === "\n")
            ? "\r\n"
            : "\n";

        return str_replace($opposite, $to, $value);
    }

    protected function normalizeIndents($string, $spaceIndent = 4)
    {
        return preg_replace("/(?:^|\G)( {{$spaceIndent}}|\t)/m", "\t", $string);
    }
}
