<?php
/**
 * @Author: houzhanzhan
 * @Last Modified by:   houzhanzhan
 */
return array(
    //验证码位数
    'CODE_LEN'           => 4,
    //默认时区
    'DEFAULT_TIME_ZONE'  => 'PRC',
    //seession自动开启
    'SESSION_AUTO_START' => true,
    //控制器
    'VAR_CONTROLLER'     => 'c',
    //方法
    'VAR_ACTION'         => 'a',
    //是否开启日志
    'SAVE_LOG'           => true,
    //错误跳转地址
    'ERROR_URL'          => '',
    //错误提示信息
    'ERROR_MSG'          => '网站出错了,请稍后再试.....',
    //自动加载Common/Lib目录下的文件，可以载入多个....
    'AUTO_LOAD_FILE'     => array(),

    //数据库配置
    'DB_CHARSET'         => 'UTF8',

    'DB_HOST'            => '127.0.0.1',

    'DB_PORT'            => 3306,

    'DB_USER'            => 'root',

    'DB_PASSWORD'        => '123456',

    'DB_DATABASE'        => 'test',

    //'DB_PREFIX'          => '',

    //Smarty配置项
    'SMARTY_ON'          => true,

    'LEFT_DELIMITER'     => '{',

    'RIGHT_DELIMITER'    => '}',

    'CACHING_ON'         => true,

    'CACHE_TIME'         => 5,
);
?>