<?php

/**
 * Created by PhpStorm.
 * User: 78742
 * Date: 2016/12/2 0002
 * Time: 17:01
 */
namespace moe\core;

class base{
    public $gset;
    public function __construct(){
        $this->gset = &$GLOBALS['gset'];
    }

    /**
     * 分页函数
     * @param $all  数据总数
     * @param $pflag  分页标示
     * @param int $now  当前页
     * @param int $row  单页数据量
     * @param int $num  页码按钮数量
     * @return string
     */
    public function paging($all, $pflag, $now = 1, $row = 10, $num = 7)
    {
        if ($all <= $row){return '';}
        $max = ceil($all/$row);
        $url = '?'.$_SERVER['QUERY_STRING'];
        $urlhome = $this->urlget_replace($url, $pflag, 1);
        $urlprev = ($now == 1)? 0 : $this->urlget_replace($url, $pflag, $now-1);
        $urlnext = ($now == $max)? 0 : $this->urlget_replace($url, $pflag, $now+1);
        $urlend = $this->urlget_replace($url, $pflag, $max);
        $urlnow = array();
        $n=$a=$b=0;
        if ($max > $num){
            if ($num%2){
                $n = (int)($num/2);
                if ($now-$n <= 1){
                    $a = 1;
                    $b = ($num < $max) ? $num : $max;
                }elseif ($now+$n >= $max){
                    $a = $max-$num+1;
                    $b = $max;
                }else{
                    $a = $now - $n;
                    $b = $now + $n;
                }
            }else{
                $n = $num/2;
                if ($now-$n+1 <= 1){
                    $a = 1;
                    $b = ($num < $max) ? $num : $max;
                }elseif ($now+$n >= $max){
                    $a = $max-$num+1;
                    $b = $max;
                }else{
                    $a = $now-$n+1;
                    $b = $now+$n;
                }
            }
        }else{
            $a = 1;
            $b = $max;
        }
        for ($a;$a<=$b;$a++){
            $urlnow[$a] = $this->urlget_replace($url, $pflag, $a);
        }
        ksort($urlnow);
        $str = '';
        $str .= '<span><a href="'.$urlhome.'">首页</a></span>';
        $str .= $urlprev ? '<span><a href="'.$urlprev.'">上一页</a></span>' : '';
        foreach ($urlnow as $k => $v){
            if ($k == $now){
                $str .= '<span class="now"><a href="'.$v.'">'.$k.'</a></span>';
            }else {
                $str .= '<span><a href="'.$v.'">'.$k.'</a></span>';
            }
        }
        $str .= $urlnext ? '<span><a href="'.$urlnext.'">下一页</a></span>': '';
        $str .= '<span><a href="'.$urlend.'">尾页</a></span>';
        $str .= '<span>&nbsp;跳转：<select name="pagenumlist">';
        for ($i=1;$i<=$max;$i++){
            $selected = ($now==$i)?' selected':'';
            $str .= '<option value="'.$this->self_urlget_replace($pflag,$i).'"'.$selected.'>第'.$i.'页</option>';
        }
        $str .= '</select></span>';
        return $str;
    }

    /**
     * url get参数替换函数
     * @param $url  链接字符串
     * @param $find 查找的参数字段
     * @param $data 需要改变的数据
     * @return mixed
     */
    public function urlget_replace($url, $find, $data)
    {
        $urlstart = $urlnum = 0;
        if (strpos($url, '?'.$find.'=')!==false){
            $urlstart = strpos($url, '?'.$find.'=')+strlen($find)+2;
        }elseif (strpos($url, '&'.$find.'=')!==false){
            $urlstart = strpos($url, '&'.$find.'=')+strlen($find)+2;
        }else{
            $url .= (strpos($url, '?')!==false) ? '&'.$find.'=' : '?'.$find.'=';
            $urlstart = strlen($url);
        }
        $urlnum = (strpos($url, '&', $urlstart)!==false) ? strpos($url, '&', $urlstart)-$urlstart : strlen($url)-$urlstart;
        $url = substr_replace($url, $data, $urlstart, $urlnum);
        return $url;
    }

    /**
     * url get参数删除函数
     * @param $url  链接字符串
     * @param $find 需要删除的参数字段
     * @return mixed
     */
    public function urlget_del($url, $find)
    {
        $urlstart = $urlnum = 0;
        if (strpos($url, '?'.$find.'=')!==false){
            $urlstart = strpos($url, '?'.$find.'=')+1;
        }elseif (strpos($url, '&'.$find.'=')!==false){
            $urlstart = strpos($url, '&'.$find.'=');
        }else{
            return $url;
        }
        $urlnum = (strpos($url, '&', $urlstart+1)!==false) ? strpos($url, '&', $urlstart+1)-$urlstart : strlen($url)-$urlstart;
        $url = substr_replace($url, '', $urlstart, $urlnum);
        return $url;
    }

    /**
     * url get参数当前连接替换
     * @param $find 查找的参数字段
     * @param $data 需要改变的数据
     * array ...$delete 需要删除的字段
     * @return mixed
     */
    public function self_urlget_replace($find, $data, ...$delete)
    {
        $url = '?'.$_SERVER['QUERY_STRING'];
        $url = $this->urlget_replace($url, $find, $data);
        foreach ($delete as $k=>$v){
            $url = $this->urlget_del($url, $v);
        }
        return $url;
    }

    /**
     * url get参数当前链接删除
     * @param array ...$find 需要删除的字段
     * @return mixed
     */
    public function self_urlget_del(...$find)
    {
        $url = '?'.$_SERVER['QUERY_STRING'];
        foreach ($find as $k=>$v){
            $url = $this->urlget_del($url, $v);
        }
        return $url;
    }

    /**
     * 校验日期格式是否正确
     * @param string $date 日期
     * @param string $formats 需要检验的格式数组
     * @return boolean
     */
    function date_format_check($date, $formats = array("Y-m-d", "Y/m/d", "Y-m-d H:i:s"))
    {
        $unixTime = strtotime($date);
        if (!$unixTime) {
            return false;
        }
        foreach ($formats as $format) {
            if (date($format, $unixTime) == $date) {
                return true;
            }
        }
        return false;
    }

    /**
     * 格式化时间（时间字符串必须是标准格式）
     * @param $datetime 时间字符串(必须是标准时间格式)
     * @param $format   时间格式
     * @return false|string
     */
    public function date_format_str($datetime, $format)
    {
        return date_format(date_create($datetime), $format);
    }

    /**
     * @param null $msg 用于显示成功信息并返回上一页
     * @param bool $url  跳转的链接地址
     */
    public function succeed_href($msg = null, $url = false)
    {
        if ($msg){
            echo '<script>alert("'.$msg.'");</script>';
        }
        if ($url){
            header("refresh:0.5;url=".$url);
            exit;
        }else{
            echo '<script>window.location.href=document.referrer;</script>';
        }
    }

    /**
     * 用于显示错误信息并返回上一页
     * @param null $msg  错误信息
     * @param bool $refresh 是否刷新
     */
    public function error_back($msg = null, $refresh = false)
    {
        $alert = $msg ? 'alert("'.$msg.'");' : '';
        if ($refresh === true){
            echo '<script>'.$alert.'window.location.href=document.referrer;</script>';
        }else if($refresh !== false){
            header("refresh:0.5;url=".$refresh);
        }else{
            echo '<script>'.$alert.'window.history.go(-1);</script>';
        }
        exit(0);
        return false;
    }

    /**
     * 过滤表单字符
     * @param $str
     * @return string
     */
    public function form_str_filter($str)
    {
        return htmlspecialchars(stripslashes(trim($str)));
    }

    /**
     * 过滤表单字符数组
     * @param $arr
     * @return array|bool
     */
    public function form_arr_filter($arr)
    {
        if(is_array($arr)){
            foreach ($arr as $k=>$v){
                $arr[$k] = $this->form_str_filter($v);
            }
        }else{
            return false;
        }
        return $arr;
    }

    /**
     * 过滤邮箱是否符合标准
     * @param $str
     * @return int
     */
    public function form_str_email($str)
    {
        return preg_match($this->gset['email_rap'], $str);
    }

    /**
     * 过滤用户名是否符合标准
     * @param $str
     * @return int
     */
    public function form_str_username($str)
    {
        $len = strlen($str);
        if ($len < $this->gset['user_low'] || $len > $this->gset['user_top']){
            return 0;
        }
        return preg_match($this->gset['user_rap'], $str);
    }

    /**
     * 过滤昵称是否符合标准
     * @param $str
     * @return int
     */
    public function form_str_nickname($str)
    {
        $len = strlen($str);
        if ($len < $this->gset['nickname_low'] || $len > $this->gset['nickname_top']){
            return 0;
        }
        return preg_match($this->gset['nickname_rap'], $str);
    }

    /**
     * 过滤密码是否符合标准
     * @param $str
     */
    public function form_str_password($str)
    {
        $len = strlen($str);
        if (is_numeric($str) || $len < $this->gset['pass_low']){
            return false;
        }
        return true;
    }

    /**
     * 过滤标签是否符合标准
     * @param $str
     * @return int
     */
    public function form_str_tagname($str)
    {
        $len = strlen($str);
        if ($len > $this->gset['tag_len']){
            return 0;
        }
        return preg_match($this->gset['tag_rap'], $str);
    }

    /**
     * 过滤作品名是否符合标准
     * @param $str
     * @return int
     */
    public function form_str_picname($str)
    {
        $len = strlen($str);
        if ($len > $this->gset['picname_len']){
            return 0;
        }
        return preg_match($this->gset['picname_rap'], $str);
    }

    /**
     * 生成salt字符串
     * @param $len  所要生成的字符串长度，默认32
     * @return null|string
     */
    function moe_password_salt($len = 32)
    {
        $str = null;
        $strlist = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()_+-*/=';
        $max = strlen($strlist)-1;
        for($i=0;$i<$len;$i++){
            $str.=$strlist[rand(0,$max)];
        }
        return $str;
    }

    /**
     * 系统加密方式
     * @param $str  所要加密的字符串
     * @param $salt 密钥
     * @return string
     */
    public function moe_password_hash($str,$salt = false)
    {
        if ($salt===false){
            $salt=$this->moe_password_salt();
        }
        return $pass = hash('sha512',md5($salt.$str).sha1(strrev($salt)));
    }

    /**
     * 检测目录是否存在，不存在则创建
     * @param $path 目录路径
     * @param string $mode  权限
     * @return bool
     */
    public function checkdir($path, $mode = 0777, $recursive = false)
    {
        if (!is_dir($path)){
            if (!@mkdir($path, $mode, $recursive)){
                return false;
            }
        }
        return true;
    }

    /**
     * 404页面
     */
    public function found404()
    {
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        include 'errpage/404.html';
        exit;
    }

    /**
     * 获取客户端IP地址
     * @return string
     */
    function getIP()
    {
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }else if(getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        }else{
            $ip = "Unknow";
        }
        return $ip;
    }
}
