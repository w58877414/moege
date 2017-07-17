<?php
/**
 * Created by PhpStorm.
 * User: 78742
 * Date: 2017/1/5 0005
 * Time: 23:37
 */

namespace moe\core;

require_once 'moe_core/base.php';

class Model extends \moe\core\base
{
    protected $conn;
    public function __construct()
    {
        parent::__construct();
        $this->conn = $this->loadDatebase();
    }
    public function loadDatebase()
    {
        require_once 'moe_core/Conn.php';
        return new \moe\core\Conn(DB_DRIVERS, DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }
}