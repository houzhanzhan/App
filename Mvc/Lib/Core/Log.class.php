<?php

/**
 * 日志类
 * @Author: houzhanzhan
 * @Last Modified by:   houzhanzhan
 */
class Log
{
    public static function write($msg, $level = 'ERROR', $type = 3, $dest = null)
    {
        if (!C('SAVE_LOG')) {
            return;
        }

        if (is_null($dest)) {
            $dest = LOG_PATH . '/' . date('Y-m-d') . '.log';
        }
        if (is_dir(LOG_PATH)) {
            error_log("[TIME]:" . date('Y-m-d H:i:s') . "{$level}:{$msg}\r\n", $type, $dest);
        }

    }
}
?>