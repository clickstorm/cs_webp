<?php
namespace Clickstorm\CsWebp;

use Clickstorm\CsWebp\Service\OptimizeImageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FileAspects {

	/**
	 * @var OptimizeImageService
	 */
	protected $service;

	public function __construct() {
		$this->service = GeneralUtility::makeInstance(OptimizeImageService::class);
	}

	/**
	 * Called when a file was processed
	 *
	 * @param \TYPO3\CMS\Core\Resource\Service\FileProcessingService $fileProcessingService
	 * @param \TYPO3\CMS\Core\Resource\Driver\DriverInterface $driver
	 * @param \TYPO3\CMS\Core\Resource\ProcessedFile $processedFile
	 */
	public function processFile($fileProcessingService, $driver, $processedFile) {
		if ($processedFile->isUpdated() === TRUE) {
			// ToDo: Find better possibility for getPublicUrl()
			$this->service->process(PATH_site . $processedFile->getPublicUrl(), $processedFile->getExtension());
		}
	}
}