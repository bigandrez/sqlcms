<?php
global $core;
    $parent = 0 + $_POST['parent'];
    $position = 0 + $_POST['position'];
    $node_type = 0 + $_POST['$node_type'];
    print $core->db->node_create($parent, $node_type='', $position);
    exit(0);
