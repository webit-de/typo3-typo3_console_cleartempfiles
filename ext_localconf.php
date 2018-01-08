<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if ((TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_CLI) || (TYPO3_MODE === 'BE' && isset($_GET['M']) && 'tools_ExtensionmanagerExtensionmanager' === $_GET['M'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][$_EXTKEY] = WebitDe\Typo3ConsoleCleartempfiles\Command\TempFilesCommandController::class;
}
