<?php
/**
 * @Author: houzhanzhan
 * @Last Modified by:   houzhanzhan
 */
class Controller extends SmartyView
{
    private $var = array();
    /**
     * [__construct 构造方法]
     * @Author  HouZhan
     * @version [1.0]
     */
    public function __construct()
    {
        if (C('SMARTY_ON')) {
            parent::__construct();
        }
        if (method_exists($this, '__init')) {
            $this->__init();
        }
        if (method_exists($this, '__auto')) {
            $this->__auto();
        }
    }
    /**
     * [get_tpl 获取模板路径]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $tpl [description]
     * @return  [type]       [description]
     */
    protected function get_tpl($tpl)
    {
        if (is_null($tpl)) {
            $path = APP_TPL_PATH . '/' . CONTROLLER . '/' . ACTION . '.html';
        } else {
            $suffix = strrchr($tpl, '.');
            $tpl    = empty($suffix) ? $tpl . '.html' : $tpl;
            $path   = APP_TPL_PATH . '/' . CONTROLLER . '/' . $tpl;
        }
        return $path;
    }
        /**
     * [assgin 传递模板参数]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $var   [description]
     * @param   [type]  $value [description]
     * @return  [type]         [description]
     */
    protected function assign($var, $value)
    {
        if (C('SMARTY_ON')) {
            parent::assign($var, $value);
        } else {
            $this->var[$var] = $value;
        }    
    }
    /**
     * [display 模板渲染]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $tpl [description]
     * @return  [type]       [description]
     */
    protected function display($tpl = null)
    {
        $path = $this->get_tpl($tpl);
        if (!is_file($path)) {
            halt($path . '模板文件不存在');
        }
        if (C('SMARTY_ON')) {
            parent::display($path);
        } else {
            extract($this->var);
            include $path;
        }
    }
    /**
     * [success 成功提示方法]
     * @Author  HouZhan
     * @version [1.0]
     * @return  [type]  [description]
     */
    protected function success($msg, $url = null, $time = 3)
    {
        $url = $url ? "window.location.href = '" . $url . "'" : 'window.history.back(-1)';
        include APP_TPL_PATH . '/success.html';
        die;
    }
    /**
     * [error 错误提示方法]
     * @Author  HouZhan
     * @version [1.0]
     * @return  [type]  [description]
     */
    protected function error($msg, $url = null, $time = 3)
    {
        $url = $url ? "window.location.href = '" . $url . "'" : 'window.history.back(-1)';
        include APP_TPL_PATH . '/error.html';
        die;
    }
}
?>