<?php

    function createTree($data){
        $list = array();
        foreach ($data as $row) {
            $list[(int) $row['parent_id']][] = $row;
        }
        return $list;
    }

    function printTree($tree, $parent_id=0) {
        if (empty($tree[$parent_id]))
            return;
        echo '<ul>';
        foreach ($tree[$parent_id] as $k => $row) {
            echo '<li>' . $row['name'];
            if (isset($tree[$row['id']]))
                printTree($tree, $row['id']);
            echo '</li>';
        }
        echo '</ul>';
    }