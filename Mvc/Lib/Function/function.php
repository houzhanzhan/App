<?php
/**
 * [halt 错误说明]
 * @Author  HouZhan
 * @version [1.0]
 * @param   [type]  $error [description]
 * @param   string  $level [description]
 * @param   integer $type  [description]
 * @param   [type]  $dest  [description]
 * @return  [type]         [description]
 */
function halt($error, $level = 'ERROR', $type = 3, $dest = null)
{
    if (is_array($error)) {
        Log::write($error['message'], $level, $type, $dest);
    } else {
        Log::write($error, $level, $type, $dest);
    }

    $e = array();
    if (DEBUG) {
        if (!is_array($error)) {
            $trace         = debug_backtrace();
            $e['message']  = $error;
            $e['file']     = $trace[0]['file'];
            $e['line']     = $trace[0]['line'];
            $e['class']    = isset($trace[0]['class']) ? $trace[0]['class'] : '';
            $e['function'] = isset($trace[0]['function']) ? $trace[0]['function'] : '';
            ob_start(); //放入缓存中区
            debug_print_backtrace();
            $e['trace'] = htmlspecialchars(ob_get_clean());
        } else {
            $e = $error;
        }
    } else {
        if ($url = C('ERROR_URL')) {
            go($url);
        } else {
            $e['message'] = C('ERROR_MSG');
        }
    }
    include DATA_PATH . '/Tpl/halt.html';die;
}
/**
 * [p 打印数据]
 * @Author   HouZhan
 * @version  [1.0]
 * @param    [type]     $arr [description]
 * @return   [type]          [description]
 */
function p($var)
{
    if (is_bool($var)) {
        var_dump($var);
    } else if (is_null($var)) {
        var_dump(null);
    } else {
        echo "<pre style='position:relative;z-index: 1000;padding: 10px;border-radius: 5px;background: #F5F5F5;border: 1px solid #aaa;font-size: 14px;line-height: 18px;opacity: 0.9;'>" . print_r($var, true) . "</pre>";
    }
}
/**
 * [go 跳转函数]
 * @Author  HouZhan
 * @version [1.0]
 * @param   [type]  $url  [description]
 * @param   integer $time [description]
 * @param   string  $msg  [description]
 * @return  [type]        [description]
 */
function go($url, $time = 0, $msg = '')
{
    if (!headers_sent()) {
        $time = 0 ? header('Location:' . $url) : header("refresh:{$time};url={$url}");
        die($msg);
    } else {
        echo "<meta http-equiv='Refresh' content='{$time};URL={$url}'/>";
        if ($time) {
            die($msg);
        }

    }
}
/**
 * [C 系统配置  用户配置]
 * @Author   HouZhan
 * @version  [1.0]
 * 1.加载配置项
 * 2.读取配置项
 * 3.临时动态改变配置项
 * 4:读取配置项(两个参数)
 */
function C($var = null, $value = null)
{
    static $config = array();
    //加载配置项
    if (is_array($var)) {
        $config = array_merge($config, array_change_key_case($var, CASE_UPPER));
        return;
    }
    //读取或者动态改变配置项
    if (is_string($var)) {
        $var = strtoupper($var);
        //两个参数传递
        if (!is_null($value)) {
            $config[$var] = $value;
            return;
        }
        return isset($config[$var]) ? $config[$var] : null;
    }
    //返回所有配置项
    if (is_null($var) && is_null($value)) {
        return $config;
    }
}
/**
 * [print_const 系统常量]
 * @Author  HouZhan
 * @version [1.0]
 * @return  [type]  [description]
 */
function print_const()
{
    $const = get_defined_constants(true);
    p($const['user']);
}
/**
 * [M 数据库M查询]
 * @Author  HouZhan
 * @version [1.0]
 * @param   [type]  $table [description]
 */
function M($table)
{
    $obj = new Model($table);
    return $obj;
}
/**
 * [D 模型查询所有]
 * @Author  HouZhan
 * @version [1.0]
 * @param   [type]  $model [description]
 */
function D($model)
{
    $model.= 'Model';
    return new $model;
}
?>