<?php
/**
 * Created by PhpStorm.
 * User: 78742
 * Date: 2016/12/2 0002
 * Time: 16:32
 */
namespace moe\core;

/**
 * Class Conn
 * 数据库操作类
 * @package moe\moe_core
 */
class Conn
{
    private $drivers;
    private $host;
    private $dbname;
    private $charset;
    private $dbn;
    private $user;
    private $pass;
    public $conn;
    private $stm;
    private $flag = false;
    /**
     * 构造函数，接受连接所需参数，打开数据库连接。
     */
    public function  __construct($drivers, $host, $dbname, $user, $pass, $charset = 'utf8', $port = '3306')
    {
        $this->drivers = $drivers;
        $this->host = $host;
        $this->dbname = $dbname;
        $this->charset = $charset;
        $this->dbn = sprintf('%s:host=%s;dbname=%s;port=%s;charset=%s', $drivers, $host, $dbname, $port, $charset);
        $this->user = $user;
        $this->pass = $pass;
        $this->connect();
    }
    /**
     * 连接数据库，禁用预处理语句的模拟，设定字符集(持久连接)
     */
    private function connect()
    {
        try {
            $this->conn = new \PDO($this->dbn, $this->user, $this->pass, array(\PDO::ATTR_PERSISTENT => true));
            $this->conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->exec('set names' . $this->charset);
        } catch (\PDOException $e) {
            die('数据路连接失败：' . $e->getMessage());
        }
    }
    /**
     * 定义空函数，防止被克隆
     */
    private function __clone(){}
    /**
     * 通用处理DML语句，附带错误检测回滚操作
     * @param $sql
     * @param $bing
     * @return bool
     */
    public function queryChange($sql, $bing = null)
    {
//        echo $sql."<br>";
//        print_r($bing);
        try {
            $this->conn->beginTransaction();
            $this->stm = $this->conn->prepare($sql);
            if ($bing!=null){
                $this->bindArrChange($bing);
                $this->bingArr($bing);
            }
            $this->flag = $this->stm->execute();
            $this->conn->commit();
        }catch (\Exception $e){
            $this->conn->rollBack();
            echo 'Error：'.$e->getMessage();
        }
        return $this->flag;
    }
    /**
     * 用来执行查询语句，并返回所有结果集数组
     * @param $sql sql预处理语句
     * @param $bing 绑定的变量数组
     * @param $type 返回单个/一行/多行数据(0/1/2)
     * @return mixed
     */
    public function selectAll($sql, $bing = null, $type = 2)
    {
//        echo $sql."<br>";
//        print_r($bing);
        $this->stm = $this->conn->prepare($sql);
        if ($bing!=null) {
            $this->bindArrChange($bing);
            $this->bingArr($bing);
        }
        if (!$this->stm->execute()){
            die('查询失败：'.print_r($this->stm->errorInfo()));
        }
        switch($type){
            case 0:return $this->stm->fetchColumn();break;
            case 1:return $this->stm->fetch();break;
            default:return $this->stm->fetchAll();
        }
    }
    /**
     * 转换需要bindParam的格式数组数据，
     * @param $bind
     */
    public function bindArrChange(&$bind)
    {
        foreach ($bind as $k => $v) {
            switch ($v['1']){
                case 'bool': $bind[$k]['1'] = \PDO::PARAM_BOOL; break;
                case 'null': $bind[$k]['1'] = \PDO::PARAM_NULL; break;
                case 'int': $bind[$k]['1'] = \PDO::PARAM_INT; break;
                case 'str': $bind[$k]['1'] = \PDO::PARAM_STR; break;
                case 'lob': $bind[$k]['1'] = \PDO::PARAM_LOB; break;
                default: $bind[$k]['1'] = null;
            }
        }
    }
    /**
     * 格式化批量绑定参数
     * @param $bind 数组
     * 对应包含bingParam参数的四个部分:参数标识符 =>（参数变量名,指定类型,类型长度）
     */
    public function bingArr(&$bind){
        foreach ($bind as $k => $v) {
            if ($v['1']==null){
                $this->stm->bindParam($k, $bind[$k]['0']);
            }elseif ($v['2']==null){
                $this->stm->bindParam($k, $bind[$k]['0'], $bind[$k]['1']);
            }else {
                $this->stm->bindParam($k, $bind[$k]['0'], $bind[$k]['1'], $bind[$k]['2']);
            }
        }
    }
    /**
     * 关闭数据库连接
     */
    public function close()
    {
        $this->conn = null;
        $this->stm = null;
    }
    /**
     * 在对象销毁时，手动关闭数据库连接
     */
    public function __destruct()
    {
        $this->close();
    }
}
