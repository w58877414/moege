<?php
/**
 *  萌格网 - 配置文件
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/** 站点名称 */
define('WEBSITE', '格萌网');

/** 数据库驱动 */
define('DB_DRIVERS', 'mysql');

/** 数据库主机 */
define('DB_HOST', 'localhost');

/** 数据库库名 */
define('DB_NAME', 'moege');

/** 数据库用户名 */
define('DB_USER', 'root');

/** 数据库密码 */
define('DB_PASS', 'root');


/** 数据库主机端口 */
define('DB_PORT', '3306');

/** 默认的数据库文字编码 */
define('DB_CHARSET', 'utf8');

/** 默认模块 注意：区分大小写 */
define('DEFALUT_MODEL', 'Moe');

/** 默认上传大小限制(Kb) */
define('DEFALUT_UPSIZE', 20480);

/** 默认上传目录 */
define('DEFALUT_UPLOAD', 'moe_upload');

/** 默认头像上传目录 */
define('DEFALUT_UPHEAD', DEFALUT_UPLOAD.'/head');

/** 默认作品上传目录 */
define('DEFALUT_UPPIC', DEFALUT_UPLOAD.'/pic');

/** 设置默认时区*/
date_default_timezone_set('Asia/Shanghai');

$GLOBALS['gset'] = [
    'user_rap' => '/^[a-zA-Z][a-zA-Z0-9_]+$/',   //用户注册正则表达式，字母开头，允许字母数字下划线
    'user_err' => '用户名必须由字母开头，由字母、数字、下划线组成的6~20位字符',
    'user_null' => '用户名不能为空',
    'user_same' => '用户名已存在',
    'user_nosearch' => '用户名不存在',
    'user_low' => 6,   //用户账号最低位
    'user_top' => 20,   //用户账号最高位
    'pass_rap' => '',   //只限制非纯字符串
    'pass_err' => '密码强度过低，不得为纯字符串，长度不得小于8位',
    'pass_diff' => '两次输入的密码不一致',
    'pass_null' => '密码不能为空',
    'pass_low' => 8,
    'nickname_rap' => '/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u',   //昵称正则
    'nickname_err' => '昵称只能由汉字、字母、数字、下划线组成的2~20位汉字',
    'nickname_null' => '昵称不能为空',
    'nickname_low' => 6,   //用户账号最低位
    'nickname_top' => 60,   //用户账号最高位
    'gender_arr' => ['男', '女', '未知'],
    'gender_err' => '性别非法',
    'birthday_err' => '请输入合法的生日格式',
    'job_len' => 60,
    'job_err' => '职业长度超限，最大字符数为20',
    'address_len' => 90,
    'address_err' => '区域/地址长度超限，最大字符数为30',
    'email_rap' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
    'email_err' => '请输入正确的邮箱格式',
    'qq_len' => 16,
    'qq_err' => '请输入正确的QQ号',
    'tool_len' => 60,
    'tool_err' => '工具字数超限，最大字符数为20',
    'usertag_len' => 360,
    'usertag_err' => '个性标签超限，最大字符数为120',
    'userinfo_len' => 720,
    'userinfo_err' => '个人简介字数超限，最大字符为240',
    'img_typeinfo' => '图片格式只支持jpg、jpeg、png、gif，并且不支持动态图片',
    'img_direrr' => '默认图片目录出错',
    'img_typeerr' => '图片格式错误',
    'img_sizeerr' => '图片大小超过限制',
    'img_dataerr' => '图片数据有损坏，不是有效的图片',
    'img_uploaderr' => '图片上传失败',
    'head_sizemax' => 1024,
    'head_sizeinfo' => '头像文件大小不得超过 1 MB',
    'head_sizeerr' => '头像文件大小超过限制',
    'head_sqlerr' => '头像更新失败，请重新尝试',
    'head_sqlsuc' => '头像更新成功',
    'tag_rap' => '/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u',
    'tag_err' => '标签只能由汉字、字母、数字、下划线组成的,最大为20位字符',
    'tag_len' => 60,
    'tag_null' => '标签不能为空',
    'tag_same' => '已经存在这个标签啦',
    'picname_rap' => '/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u',
    'picname_err' => '作品名只能由汉字、字母、数字、下划线组成的,最大为40位字符',
    'picname_len' => 120,
    'picname_null' => '作品名不能为空',
];

