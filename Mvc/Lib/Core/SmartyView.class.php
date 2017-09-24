<?php

/**
 * @Author: houzhanzhan
 * @Last Modified by:   houzhanzhan
 */
class SmartyView
{
    private static $smarty = null;
    public function __construct()
    {
        if (!is_null(self::$smarty)) {
            return;
        }

        $smarty = new Smarty();
        //模板目录
        $smarty->template_dir = APP_TPL_PATH . '/' . CONTROLLER . '/';
        //编译
        $smarty->compile_dir = APP_COMPILE_PATH;
        //缓存
        $smarty->cache_dir       = APP_CACHE_PATH;
        $smarty->left_delimiter  = C('LEFT_DELIMITER');
        $smarty->right_delimiter = C('RIGHT_DELIMITER');
        $smarty->caching         = C('CACHING_ON');
        $smarty->cache_lifetime  = C('CACHE_TIME');
        //p($smarty);
        self::$smarty = $smarty;
    }
    /**
     * [display 模板渲染]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $tpl [description]
     * @return  [type]       [description]
     */
    protected function display($tpl)
    {
        self::$smarty->display($tpl, $_SERVER['REQUEST_URI']);
    }
    /**
     * [assign 渲染参数]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $var   [description]
     * @param   [type]  $value [description]
     * @return  [type]         [description]
     */
    protected function assign($var, $value)
    {
        self::$smarty->assign($var, $value);
    }
    protected function is_cached($tpl = null)
    {
        if (!C('SMARTY_ON')) {
            halt('请先开启Smarty!');
        }
        
        $tpl = $this->get_tpl($tpl);
        return self::$smarty->isCached($tpl, $_SERVER['REQUEST_URI']);
    }
}
?>