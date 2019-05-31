<?php

// 初期設定関数の呼び出し(地域・言語の設定)
date_default_timezone_set('Asia/Tokyo');
mb_language('Japanese');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');


// PHPモジュールの格納ディレクトリ
define('MODULE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/modules');

/**
 * @param $__FILENAME
 * @param $__VARS
 * @param bool|true $KILL_WHITESPACE
 * @return string
 */
function call_html_template($__FILENAME, $__VARS = array(), $KILL_WHITESPACE = true)
{
    static $__DIRNAME = 'View';

    $file_path = $__DIRNAME . '/' . $__FILENAME . '.php';

    if (!file_exists($file_path))
        throw new Exception("file or directory is not exist");


    ob_start();
    try {
        extract($__VARS);
        require $file_path;
        $content = ob_get_contents();
    } catch (Exception $error) {
        $content = $error->getMessage();
    }

    ob_end_clean();

    return $KILL_WHITESPACE ? preg_replace('/\r\n|\r|\n|\t/', '', $content) : $content;
}
