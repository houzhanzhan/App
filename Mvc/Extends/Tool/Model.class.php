<?php
/**
 * @Author: houzhanzhan
 * @Last Modified by:   houzhanzhan
 */
class Model
{
    //保存连接信息
    public static $link = null;
    //保存表名
    protected $table = null;
    //初始化表信息
    private $opt;
    //记录发送的sql
    public static $sqls = array();
    /**
     * [__construct 构造数据库]
     * @Author  HouZhan
     * @version [1.0]
     */
    public function __construct($table = null)
    {
        if (is_null(C('DB_PREFIX'))) {
            $this->table = is_null($table) ? $this->table : $table;
        } else {
            $this->table = is_null($table) ? C('DB_PREFIX') . $this->table : C('DB_PREFIX') . $table;
        }
        //连接数据库
        $this->_connect();
        //初始化sql信息
        $this->_opt();
    }
    /**
     * [query 查询结果集]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $sql [description]
     * @return  [type]       [description]
     */
    public function query($sql)
    {
        self::$sqls[] = $sql;
        $link         = self::$link;
        $result       = $link->query($sql);
        if ($link->errno) {
            halt('mysql错误:' . $link->error . '<br/>SQL:' . $sql);
        }
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $result->free();
        $this->_opt();
        return $rows;
    }
    /**
     * [find 查询一条]
     * @Author  HouZhan
     * @version [1.0]
     * @return  [type]  [description]
     */
    public function find()
    {
        $data = $this->limit(1)->all();
        $data = current($data);
        return $data;
    }
    //查询一条
    public function one()
    {
        return $this->find();
    }
    //查询全部
    public function finAll()
    {
        return $this->all();
    }
    /**
     * [field 链式多字段查找]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $field [description]
     * @return  [type]         [description]
     */
    public function field($field)
    {
        $this->opt['field'] = $field;
        return $this;
    }
    /**
     * [where 条件查询]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $where [description]
     * @return  [type]         [description]
     */
    public function where($where)
    {
        $this->opt['where'] = " WHERE " . $where;
        return $this;
    }
    /**
     * [limit 限制]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $limit [description]
     * @return  [type]         [description]
     */
    public function limit($limit)
    {
        $this->opt['limit'] = " LIMIT " . $limit;
        return $this;
    }
    /**
     * [order 排序]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $order [description]
     * @return  [type]         [description]
     */
    public function order($order)
    {
        $this->opt['order'] = " ORDER BY " . $order;
        return $this;
    }
    /**
     * [having 结果集]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $having [description]
     * @return  [type]          [description]
     */
    public function having($having)
    {
        $this->opt['having'] = " HAVING " . $having;
        return $this;
    }
    /**
     * [group 分组]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $group [description]
     * @return  [type]         [description]
     */
    public function group($group)
    {
        $this->opt['group'] = " GROUP BY " . $group;
        return $this;
    }
    /**
     * [all 查出所有]
     * @Author  HouZhan
     * @version [1.0]
     * @return  [type]  [description]
     */
    public function all()
    {
        $sql = "SELECT " . $this->opt['field'] . " FROM " . $this->table . $this->opt['where'] . $this->opt['group'] . $this->opt['having'] . $this->opt['order'] . $this->opt['limit'];
        return $this->query($sql);
    }
    /**
     * [_opt 记录sql]
     * @Author  HouZhan
     * @version [1.0]
     * @return  [type]  [description]
     */
    private function _opt()
    {
        $this->opt = array(
            'field'  => '*',
            'where'  => '',
            'group'  => '',
            'having' => '',
            'order'  => '',
            'limit'  => '',
        );
    }
    /**
     * [_connect 连接]
     * @Author  HouZhan
     * @version [1.0]
     * @return  [type]  [description]
     */
    private function _connect()
    {
        if (is_null(self::$link)) {
            if (empty(C('DB_DATABASE'))) {
                halt('请先配置数据库');
            }

            $link = new mysqli(C('DB_HOST'), C('DB_USER'), C('DB_PASSWORD'), C('DB_DATABASE'), C('DB_PORT'));
            if ($link->connect_error) {
                halt('数据库连接失败,请检查配置项');
            }

            $link->set_charset(C('DB_CHARSET'));
            self::$link = $link;
        }
    }
    /**
     * [add 添加数据]
     * @Author  HouZhan
     * @version [1.0]
     */
    public function add($data = null)
    {
        if (is_null($data)) {
            $data = $_POST;
        }

        $fields = '';
        $values = '';

        foreach ($data as $k => $v) {
            $fields .= "`" . $this->_safe_str($k) . "`,";
            $values .= "'" . $this->_safe_str($v) . "',";
        }
        $fields = trim($fields, ',');
        $values = trim($values, ',');

        $sql = "INSERT INTO " . $this->table . '(' . $fields . ') VALUES (' . $values . ')';
        return $this->exe($sql);
    }
    /**
     * [del 删除数据]
     * @Author  HouZhan
     * @version [1.0]
     * @return  [type]  [description]
     */
    public function del()
    {
        if (empty($this->opt['where'])) {
            halt('删除语句必须有where条件');
        }

        $sql = "DELETE FROM " . $this->table . $this->opt['where'];
        return $this->exe($sql);
    }
    public function save($data = null)
    {
        if (empty($this->opt['where'])) {
            halt('更新语句需要where条件');
        }

        if (is_null($data)) {
            $data = $_POST;
        }

        $values = '';
        foreach ($data as $k => $v) {
            $values .= "`" . $this->_safe_str($k) . "`='" . $this->_safe_str($v) . "',";
        }
        $values = trim($values, ',');
        $sql    = "UPDATE " . $this->table . " SET " . $values . $this->opt['where'];
        return $this->exe($sql);
    }
    /**
     * [exe 无结果集查询]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $sql [description]
     * @return  [type]       [description]
     */
    public function exe($sql)
    {
        self::$sqls[] = $sql;
        $link         = self::$link;
        $bool         = $link->query($sql);
        $this->_opt();
        if (is_object($bool)) {
            halt('请用query方法发送查询sql');
        }

        if ($bool) {
            return $link->insert_id ? $link->insert_id : $link->affected_rows;
        } else {
            halt('mysql错误:' . $link->error . '<br/>SQL:' . $sql);
        }
    }
    /**
     * [_safe_str 安全处理字符串]
     * @Author  HouZhan
     * @version [1.0]
     * @param   [type]  $str [description]
     * @return  [type]       [description]
     */
    public function _safe_str($str)
    {
        if (get_magic_quotes_gpc()) {
            $str = stripcslashes($str);
        }
        return self::$link->real_escape_string($str);
    }
}
