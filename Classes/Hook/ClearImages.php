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
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\CommandUtility;

class ClearImages implements \TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface {
    /**
     * Add an entry to the CacheMenuItems array
     *
     * @param array $cacheActions Array of CacheMenuItems
     * @param array $optionValues Array of AccessConfigurations-identifiers (typically  used by userTS with options.clearCache.identifier)
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues) {
        if ($GLOBALS['BE_USER']->getTSConfigVal('options.clearCache.tx_cswebp') == NULL || $GLOBALS['BE_USER']->getTSConfigVal('options.clearCache.tx_cswebp')) {
            $title = LocalizationUtility::translate('cache_action.title', 'cs_webp');

            $identifier = 'clear_processed_images_icon';
            $iconRegistry = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
            $iconRegistry->registerIcon($identifier, \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class, ['source' => 'EXT:cs_webp/Resources/Public/Images/clear_cache_icon.png']);

            $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
            $icon = $iconFactory->getIcon($identifier, \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);

            // Clearing of processed images
            $cacheActions[] = array(
                'id'    => 'tx_cswebp',
                'title' => $title,
                'href'  => BackendUtility::getModuleUrl('tce_db', [
                    'vC' => $GLOBALS['BE_USER']->veriCode(),
                    'cacheCmd' => 'tx_cswebp',
                    'ajaxCall' => 1
                ]),
                'icon'  => $icon
            );
        }
    }

    /**
     * This method is called by the CacheMenuItem in the Backend
     * @param \array $_params
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
     */
    public static function clear($_params, $dataHandler) {
        if ($_params['cacheCmd'] == 'tx_cswebp') {
            $repository = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ProcessedFileRepository');
            $cacheManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');

            // remove all processed files
            $repository->removeAll();

            $command = sprintf('rm -rf %sfileadmin/_processed_/*', PATH_site);
            CommandUtility::exec($command);

            // clear page caches
            $cacheManager->flushCachesInGroup('pages');
        }
    }
}