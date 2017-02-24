<?php

// Convert images on processing files
$signalSlotDispatcher->connect(
    'TYPO3\\CMS\\Core\\Resource\\ResourceStorage',
    \TYPO3\CMS\Core\Resource\Service\FileProcessingService::SIGNAL_PostFileProcess,
    'Clickstorm\\CsWebp\\FileAspects',
    'processFile'
);

/*
 * add hooks to clear cache
 */
// The Backend-MenuItem in ClearCache-Pulldown
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions']['tx_cswebp'] =
    'EXT:cs_webp/Classes/Hook/ClearImages.php:Clickstorm\\CsWebp\\Hook\\ClearImages';

// The AjaxCall to clear the cache
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][] =
    'EXT:cs_webp/Classes/Hook/ClearImages.php:Clickstorm\\CsWebp\\Hook\\ClearImages->clear';