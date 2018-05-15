<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

$signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);

// Convert images on processing files
$signalSlotDispatcher->connect(
    \TYPO3\CMS\Core\Resource\ResourceStorage::class,
    \TYPO3\CMS\Core\Resource\Service\FileProcessingService::SIGNAL_PostFileProcess,
    \Clickstorm\CsWebp\FileAspects::class,
    'processFile'
);

$iconRegistry = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'ext-cswebp-clear-processed-images',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:cs_webp/Resources/Public/Images/clear_cache_icon.png']
);

/*
 * add hooks to clear cache
 */
// The Backend-MenuItem in ClearCache-Pulldown

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions']['tx_cswebp'] =
    Clickstorm\CsWebp\Hook\ClearImages::class;

// The AjaxCall to clear the cache
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][] =
    'Clickstorm\\CsWebp\\Hook\\ClearImages->clear';