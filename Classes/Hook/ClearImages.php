<?php

namespace Clickstorm\CsWebp\Hook;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2017 Angela Dudtkowski
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Resource\ProcessedFileRepository;
use TYPO3\CMS\Core\Cache\CacheManager;

class ClearImages implements \TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface
{
    /**
     * Add an entry to the CacheMenuItems array
     *
     * @param array $cacheActions Array of CacheMenuItems
     * @param array $optionValues Array of AccessConfigurations-identifiers (typically  used by userTS with options.clearCache.identifier)
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues)
    {
        if ($GLOBALS['BE_USER']->getTSConfigVal('options.clearCache.tx_cswebp') === NULL || $GLOBALS['BE_USER']->getTSConfigVal('options.clearCache.tx_cswebp')) {

            // Clearing of processed images
            $cacheActions[] = [
                'id' => 'tx_cswebp',
                'title' => 'LLL:EXT:cs_webp/Resources/Private/Language/de.locallang.xlf:cache_action.title',
                'description' => 'LLL:EXT:cs_webp/Resources/Private/Language/de.locallang.xlf:cache_action.description',
                'href' => BackendUtility::getModuleUrl('tce_db', [
                    'vC' => $GLOBALS['BE_USER']->veriCode(),
                    'cacheCmd' => 'tx_cswebp',
                    'ajaxCall' => 1
                ]),
                'iconIdentifier' => 'ext-cswebp-clear-processed-images'
            ];
        }
        $optionValues[] = 'tx_cswebp';
    }

    /**
     * This method is called by the CacheMenuItem in the Backend
     * @param \array $_params
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheGroupException
     */
    public static function clear($_params, $dataHandler)
    {
        if ($_params['cacheCmd'] === 'tx_cswebp') {
            $repository = GeneralUtility::makeInstance(ProcessedFileRepository::class);
            $cacheManager = GeneralUtility::makeInstance(CacheManager::class);

            // remove all processed files
            $repository->removeAll();

            $command = sprintf('rm -rf %sfileadmin/_processed_/*', PATH_site);
            CommandUtility::exec($command);

            // clear page caches
            $cacheManager->flushCachesInGroup('pages');
        }
    }
}