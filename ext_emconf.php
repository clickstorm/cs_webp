<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "cs_webp"
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => '[clickstorm] Convert images to webp',
    'description' => 'Store png and jpg images in webp format too (only fileadmin/_processed_ folder). Delete webp files with processed images. Increases the points of Google PageSpeed Insights.',
    'category' => 'plugin',
    'author' => 'Angela Dudtkowski',
    'author_email' => 'dudtkowski@clickstorm.de',
    'author_company' => 'clickstorm GmbH',
    'uploadfolder' => false,
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'version' => '1.1.2-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-8.7.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
