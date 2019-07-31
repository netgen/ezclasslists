<?php

$FunctionList = array();

$FunctionList['subtree_class_list'] = array(
    'name' => 'subtree_class_list',
    'call_method' => array(
        'class' => 'classlistsFunctionCollection',
        'method' => 'fetchClassListInSubtree'
    ),
    'parameter_type' => 'standard',
    'parameters' => array(
        array(
            'name' => 'parent_id',
            'type' => 'integer',
            'required' => true,
            'default' => 0
        )
    )
);
