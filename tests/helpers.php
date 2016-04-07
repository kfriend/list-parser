<?php

// Used to sort the tree, to provide better output for diffing
function tree_sort(&$tree)
{
    foreach ($tree as $i => &$item) {
        ksort($item);
        if (!empty($item['children'])) {
            tree_sort($item['children']);
        }

        unset($item);
    }
}

// Used to prep the tree for comparison, stripping out values we don't care to compare
function tree_prep_for_comparison(&$tree)
{
    foreach ($tree as $i => &$item) {
        unset(
            $item['raw'],
            $item['path']
        );

        if (!empty($item['children'])) {
            tree_prep_for_comparison($item['children']);
        }

        unset($item);
    }
}
