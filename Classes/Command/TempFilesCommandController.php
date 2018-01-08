<?php

namespace WebitDe\Typo3ConsoleCleartempfiles\Command;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Lidia Demin <demin@webit.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

use TYPO3\CMS\Core\Resource\ProcessedFileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * Adds commands that can be used by typo3_console
 *
 * @author    Lidia Demin <demin@webit.de>
 */
class TempFilesCommandController extends CommandController
{
    /**
     * Path to the 'processed' folder
     * @var string
     */
    protected $processedFolderPath = PATH_site . 'fileadmin/_processed_';

    /**
     * Path to the 'typo3temp' folder
     * @var string
     */
    protected $typo3TempFolderPath = PATH_site . 'typo3temp/';

    /**
     * Truncates the table sys_file_processedfile and
     * then empties the _processed_ folder if successful
     */
    public function emptyProcessedCommand()
    {
        if (is_dir($this->processedFolderPath)) {
            $this->outputLine(sprintf('Truncating table sys_file_processedfile ...'));
            $repository = GeneralUtility::makeInstance(ProcessedFileRepository::class);
            $failedDeletions = $repository->removeAll();

            if (0 === $failedDeletions) {
                $this->outputLine(sprintf('Successfully truncated table sys_file_processedfile.'));

                $this->outputLine(sprintf('Removing files and folders from: %s ...', $this->processedFolderPath));
                // No need to check the return value, because $this->processedFolderPath was checked before
                // If any files or directories can't be deleted an exception will occur
                $this->recursiveRemoveDirectory($this->processedFolderPath, false);
                $this->outputLine(sprintf('Successfully removed files and folders from: %s', $this->processedFolderPath));
            } else {
                $this->outputLine(sprintf('ERROR: Failed to truncate table sys_file_processedfile. %s errors found.', $failedDeletions));
                $this->outputLine(sprintf('Please execute this process in the install tool for more information.'));
            }

        } else {
            $this->outputLine(sprintf('Invalid path to _processed_ folder. Please check this command.'));
        }
    }

    /**
     * Empties the typo3temp folder
     */
    public function emptyTypo3TempCommand()
    {
        if (is_dir($this->typo3TempFolderPath)) {
            $this->outputLine(sprintf('Removing files and folders from: %s ...', $this->typo3TempFolderPath));
            // No need to check the return value, because $this->typo3TempFolderPath was checked before
            // If any files or directories can't be deleted an exception will occur
            $this->recursiveRemoveDirectory($this->typo3TempFolderPath, false);
            $this->outputLine(sprintf('Successfully removed files and folders from: %s', $this->typo3TempFolderPath));
        } else {
            $this->outputLine(sprintf('Invalid path to typo3temp folder. Please check this command.'));
        }
    }

    /**
     * Recursively removes all files and subdirectories of a given directory
     *
     * @param string    $parentDirectoryPath    Directory where files and folders should be removed
     * @param bool      $removeParentDirectory  Flag whether the parent directory should be removed as well
     * @return boolean
     */
    public function recursiveRemoveDirectory($parentDirectoryPath, $removeParentDirectory = false)
    {
        if (is_dir($parentDirectoryPath)) {
            $directoryContents = array_diff(scandir($parentDirectoryPath), array('.', '..'));

            foreach ($directoryContents as $directoryContent) {
                $directoryContentPath = $parentDirectoryPath . '/' . $directoryContent;
                if (is_dir($directoryContentPath)) {
                    $this->recursiveRemoveDirectory($directoryContentPath, true);
                } elseif (is_file($directoryContentPath)) {
                    unlink($directoryContentPath);
                }
            }
            if ($removeParentDirectory) {
                rmdir($parentDirectoryPath);
            }

            return true;
        }

        return false;
    }
}
