<?php
namespace Clickstorm\CsWebp\Service;

use TYPO3\CMS\Core\Utility\CommandUtility;

class OptimizeImageService {

    /**
     * Initialize
     */
    public function __construct() {
        $this->configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cs_webp']);
    }

	/**
	 * Perform image optimization
	 *
	 * @param string $file
	 * @param string $extension
	 */
	public function process($file, $extension = NULL) {
		if ($extension === NULL) {
			$pathinfo = pathinfo($file);
			if ($pathinfo['extension'] !== NULL) {
				$extension = $pathinfo['extension'];
			}
		}
		$extension = strtolower($extension);

		if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
            $webpfile = str_replace("." . $extension, ".webp", $file);
            $command = sprintf('convert %s -define webp:lossless=true %s', $file, $webpfile);
		}

        if (isset($command)) {
            $output = [];
            $returnValue = 0;
            CommandUtility::exec($command, $output, $returnValue);
            if ((bool)$this->configuration['debug'] === TRUE && is_object($GLOBALS['BE_USER'])) {
                $GLOBALS['BE_USER']->simplelog($command . ' exited with ' . $returnValue . '. Output was: ' . implode(' ', $output), 'cs_webp', 0);
            }
        }
	}
}