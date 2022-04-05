<?php
/**
 * This can be used as default
 */
return [
    'type' => 'dfe', // Main type. Fixed "dfe"
    'config' => [
        [
            'name' => 'cte', // DFe type. For a while, it can be only cte, nfe, mdfe or gnre
            'paths' => require 'dfe/cte_paths.php', // Here we can configure paths to save in files messages sent or received from webservices. Including a file. Could be set as an array or import file.
            '300' => require 'dfe/cte_300.php', // Here we put a list of webservices configuration. Including a file. Could be set as an array or import file
            '100' => require 'dfe/cte_100.php' // DFe distibuition service is availibe only in version 1.00
        ],
        [
            'name' => 'mdfe', // DFe type. For a while, it can be only cte, nfe, mdfe or gnre
            'paths' => require 'dfe/mdfe_paths.php', // Including a file. Could be set as an array or import file
            '300' => require 'dfe/mdfe_300.php', // Including a file. Could be set as an array or import file
            '100' => require 'dfe/mdfe_100.php' // DFe distibuition service is availibe only in version 1.00
        ],
        [
            'name' => 'nfe', // DFe type. For a while, it can be only cte, nfe, mdfe or gnre
            'paths' => require 'dfe/nfe_paths.php', // Including a file. Could be set as an array or import file
            '400' => require 'dfe/nfe_400.php', // Including a file. Could be set as an array or import file
            '101' => require 'dfe/nfe_101.php', // DFe distibuition service is availibe only in version 1.01
            '100' => require 'dfe/nfe_100.php' // DFe distibuition service is availibe only in version 1.01
        ]
    ]
];