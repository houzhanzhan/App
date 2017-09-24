<?php
final class MVC
{
    public static function run()
    {
        self::_set_const();
        defined('DEBUG') || define('DEBUG', false);
        if (DEBUG) {
            self::_create_dir();
            self::_import_file();
        } else {
            error_reporting(0);
            require TEMP_PATH . '/boot.php';
        }
        Application::run();
    }
    /**
     * @Author   HouZhan
     * @version  [1.0]
     * @return   [type]     [定义目录常量]
     */
    private static function _set_const()
    {
        //var_dump(__FILE__);魔术常量获取框架根路径
        $path = str_replace('\\', '/', __FILE__);
        define('MVC_PATH', dirname($path)); //MVC根路径
        define('CONFIG_PATH', MVC_PATH . '/Config'); //配置路径
        define('DATA_PATH', MVC_PATH . '/Data'); //资源路径
        define('LIB_PATH', MVC_PATH . '/Lib'); //核心类路径
        define('CORE_PATH', LIB_PATH . '/Core');
        define('FUNCTION_PATH', LIB_PATH . '/Function');

        //工具类
        define('EXTENDS_PATH', MVC_PATH . '/Extends');
        define('TOOL_PATH', EXTENDS_PATH . '/Tool');
        define('ORG_PATH', EXTENDS_PATH . '/Org');

        define('ROOT_PATH', dirname(MVC_PATH)); //根目录
        define('TEMP_PATH', ROOT_PATH . '/Temp'); //日志缓存目录
        define('LOG_PATH', TEMP_PATH . '/Log');

        //应用目录
        define('APP_PATH', ROOT_PATH . '/' . APP_NAME);
        define('APP_CONFIG_PATH', APP_PATH . '/Config');
        define('APP_CONTROLLER_PATH', APP_PATH . '/Controller');
        define('APP_TPL_PATH', APP_PATH . '/Tpl');
        define('APP_PUBLIC_PATH', APP_TPL_PATH . '/Public');

        //smarty模板缓存目录
        define('APP_COMPILE_PATH', TEMP_PATH . '/' . APP_NAME . '/Compile');
        define('APP_CACHE_PATH', TEMP_PATH . '/' . APP_NAME . '/Cache');
        //创建公共目录
        define('COMMON_PATH', ROOT_PATH . '/Common');
        //公共配置项文件夹
        define('COMMON_CONFIG_PATH', COMMON_PATH . '/Config');
        //公共模型文件
        define('COMMON_MODEL_PATH', COMMON_PATH . '/Model');
        //公共库文件夹
        define('COMMON_LIB_PATH', COMMON_PATH . '/Lib');
        //版本
        define('MXMPHP_VERSION', '1.0');
        //定义POST请求
        define('IS_POST', ($_SERVER['REQUEST_METHOD'] == 'POST') ? true : false);
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            define('IS_AJAX', true);
        } else {
            define('IS_AJAX', false);
        }

    }
    /**
     * @Author   HouZhan
     * @version  [1.0]
     * @return   [type]     [创建应用目录]
     */
    private static function _create_dir()
    {
        $arr = array(
            COMMON_CONFIG_PATH,
            COMMON_MODEL_PATH,
            COMMON_LIB_PATH,
            APP_PATH,
            APP_CONFIG_PATH,
            APP_CONTROLLER_PATH,
            APP_TPL_PATH,
            APP_PUBLIC_PATH,
            TEMP_PATH,
            APP_COMPILE_PATH,
            APP_CACHE_PATH,
            LOG_PATH,
        );
        foreach ($arr as $v) {
            is_dir($v) || mkdir($v, 0777, true);
        }
        is_file(APP_TPL_PATH . '/success.html') || copy(DATA_PATH . '/Tpl/success.html', APP_TPL_PATH . '/success.html');
        is_file(APP_TPL_PATH . '/error.html') || copy(DATA_PATH . '/Tpl/error.html', APP_TPL_PATH . '/error.html');
    }
    /**
     * @Author   HouZhan
     * @license  [license]
     * @version  [version]
     * @return   [type]     [载入框架所需文件]
     */
    private static function _import_file()
    {
        $fileArr = array(
            CORE_PATH . '/Log.class.php',
            FUNCTION_PATH . '/function.php',
            ORG_PATH . '/Smarty/Smarty.class.php',
            CORE_PATH . '/SmartyView.class.php',
            CORE_PATH . '/Controller.class.php',
            CORE_PATH . '/Application.class.php',
        );
        $str = '';
        foreach ($fileArr as $v) {
            $str .= trim(substr(file_get_contents($v), 5, -2));
            require_once $v;
        }
        $str = "<?php\r\n" . $str;
        file_put_contents(TEMP_PATH . '/boot.php', $str) || die('access not allow');
    }
}
MVC::run();
?>