<?php

namespace GlobalPrcf\Controllers\Admin;

use GlobalPrcf\Controllers\AbstractController;

class Logger extends AbstractController
{
    public function logToFile($logLine): void
    {
        $log  = date("F j, Y, g:i a")."    " . $logLine;
        //Save string to log, use FILE_APPEND to append.
        file_put_contents(GLOBAL_DRINKS_PLUGIN_FOLDER . '/logCF.log', $log, FILE_APPEND);
    }
}