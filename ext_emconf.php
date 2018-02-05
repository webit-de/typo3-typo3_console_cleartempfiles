<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'TYPO3 Console Clear Temp Files',
    'description' => 'Clear temporary files using TYPO3 Console',
    'category' => 'plugin',
    'author' => 'Lidia Demin',
    'author_email' => 'demin@webit.de',
    'author_company' => 'webit! GmbH',
    'state' => 'beta',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.1.0',
    'constraints' => array(
        'depends' => array(
            'php' => '7.0.0-7.1.99',
            'typo3' => '7.6.99-8.7.99',
            'cms' => ''
        ),
        'conflicts' => array(),
        'suggests' => array(),
    ),
);
