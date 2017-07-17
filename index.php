<?php
/**
 * Created by PhpStorm.
 * User: 78742
 * Date: 2016/11/30 0030
 * Time: 23:07
 */
namespace moe;
//\moe\proStartTime();
//echo "<script>console.log('开始".memory_get_usage()."')</script>";
//echo "<script>console.log('开始峰值".memory_get_peak_usage()."')</script>";
define('BASEPATH','moe_');
header("Content-type: text/html; charset=utf-8");
error_reporting(E_ALL ^ E_NOTICE); 
require_once 'config/config.php';
$m = $_GET['m']?$_GET['m']:DEFALUT_MODEL;
$v = $_GET['v']?$_GET['v']:'index';
if (!is_file('moe_controller/'.$m.'.php')){
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
    include 'errpage/404.html';
    exit;
}
require 'moe_controller/'.$m.'.php';
$cname = 'moe\\controller\\'.$m;
$c = new $cname();
$c->$v();

//print_r(get_included_files());
//echo "<script>console.log('结束".memory_get_usage()."')</script>";
//echo "<script>console.log('结束峰值".memory_get_peak_usage()."')</script>";
//echo "<script>console.log('运行时间".\moe\proEndTime()."')</script>";
//function proStartTime() {
//    global $startTime;
//    $mtime1 = explode(" ", microtime());
//    $startTime = $mtime1[1] + $mtime1[0];
//}
//function proEndTime() {
//    global $startTime,$set;
//    $mtime2 = explode(" ", microtime());
//    $endtime = $mtime2[1] + $mtime2[0];
//    $totaltime = ($endtime - $startTime);
//    $totaltime = number_format($totaltime, 7);
//    return $totaltime;
//}