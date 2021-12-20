<?php
/**
 * This can be used as default
 */
return [
    'type' => 'dfe',
    'config' => [
        [
            'name' => 'cte',
            'paths' => require 'dfe/cte_paths.php',
            '300' => require 'dfe/cte_300.php'
        ],
        [
            'name' => 'mdfe',
            'paths' => require 'dfe/mdfe_paths.php',
            '300' => require 'dfe/mdfe_300.php'
        ]
    ]
];