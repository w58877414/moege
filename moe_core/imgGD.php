<?php
/**
 * Created by PhpStorm.
 * User: 78742
 * Date: 2017/1/26 0026
 * Time: 16:24
 */

namespace moe\core;

require_once 'moe_core/base.php';

class imgGD extends \moe\core\base
{
    private $filename;
    private $type;
    private $size;
    private $tmp_name;
    private $typesuffix;
    private $imgdata;
    private $height;
    private $width;

    /**
     * imgGD constructor.
     * @param $filename 上传的图片文件名
     * @param $type     上传的图片类型
     * @param $size     上传的图片尺寸
     * @param $tmp_name 上传的图片的临时缓存
     * @param bool $setdata 图片是否需要进行处理
     */
    public function __construct($filename, $type, $size, $tmp_name)
    {
        $this->filename = $filename;
        $this->size = ($size <= DEFALUT_UPSIZE*1024) ? $size : $this->error_back($this->gset['img_sizeerr']);
        $this->tmp_name = is_uploaded_file($tmp_name)? $tmp_name : $this->error_back('非法访问');
        $this->checkImgdir_Defalut()?:$this->error_back($this->gset['img_direrr']);
        $this->setImgtype()?:$this->error_back($this->gset['img_typeerr']);
        $this->imgdata = $this->setImgdata()?:$this->error_back($this->gset['img_dataerr']);
    }

    /**
     * 检测默认上传目录是否存在，不存在则创建
     * @return bool
     */
    public function checkImgdir_Defalut()
    {
        if ($this->checkdir(DEFALUT_UPLOAD) && $this->checkdir(DEFALUT_UPHEAD) && $this->checkdir(DEFALUT_UPPIC)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 解析图片类型以及尺寸信息
     * @return bool
     */
    public function setImgtype()
    {
        $size = getimagesize($this->tmp_name);
        $this->type = $size['mime'];
        $this->width = $size[0];
        $this->height = $size[1];
        switch ($size['mime']){
            case 'image/jpeg':
            case 'image/pjpeg':$this->typesuffix = 'jpg';break;
            case 'image/png':
            case 'image/x-png':$this->typesuffix = 'png';break;
            case 'image/gif':$this->typesuffix = 'gif';break;
            default:return false;
        }
        return true;
    }

    /**
     * 转化tmp图片为数据格式
     * @return resource
     */
    public function setImgdata()
    {
        ini_set('gd.jpeg_ignore_warning', 1);
        switch ($this->type){
            case 'image/jpeg':
            case 'image/pjpeg':return imagecreatefromjpeg($this->tmp_name);break;
            case 'image/png':
            case 'image/x-png':return imagecreatefrompng($this->tmp_name);break;
            case 'image/gif':return imagecreatefromgif($this->tmp_name);break;
            default:return false;
        }
    }

    /**
     * 修改图片尺寸（按长宽）
     * @param $nwidth
     * @param $nheight
     * @return resource
     */
    public function alterImgsize($nwidth, $nheight)
    {
        $dsc_img = imagecreatetruecolor($nwidth, $nheight);
        imagecopyresampled($dsc_img, $this->imgdata, 0, 0, 0, 0, $nwidth, $nheight, $this->width, $this->height);
        return $dsc_img;
    }

    /**
     * 修改图片尺寸（最大长度）
     * @param $max
     * @return resource
     */
    public function alterImgsize_max($max)
    {
        if($this->width > $this->height){
            $nwidth = $max;
            $scale = $nwidth/$this->width;
            $nheight = $this->height*$scale;
        }else{
            $nheight = $max;
            $scale = $nheight/$this->height;
            $nwidth = $this->width*$scale;
        }
        $dsc_img = imagecreatetruecolor($nwidth, $nheight);
        imagecopyresampled($dsc_img, $this->imgdata, 0, 0, 0, 0, $nwidth, $nheight, $this->width, $this->height);
        return $dsc_img;
    }

    /**
     * 修改图片尺寸（按比例）
     * @param $scale
     * @return resource
     */
    public function alterImgsize_scale($scale)
    {
        $nwidth = $this->width*$scale;
        $nheight = $this->height*$scale;
        $dsc_img = imagecreatetruecolor($nwidth, $nheight);
        imagecopyresampled($dsc_img, $this->imgdata, 0, 0, 0, 0, $nwidth, $nheight, $this->width, $this->height);
        return $dsc_img;
    }

    /**
     * 裁剪图片
     * @param $x    裁剪x起点
     * @param $y    裁剪y起点
     * @param $w    裁剪x尺寸
     * @param $h    裁剪y尺寸
     * @param resource $imgdata
     * @return resource
     */
    public function clippingImg($x, $y, $w, $h, $imgdata = null)
    {
        $imgdata = empty($imgdata)? $this->imgdata : $imgdata;
        $dsc_img = imagecreatetruecolor($w, $h);
        imagecopyresampled($dsc_img, $imgdata, 0, 0, $x, $y, $w, $h, $w, $h);
        return $dsc_img;
    }

    /**
     * 创建一个利用resource数据生成的jpg图像(成功则返回文件位置)
     * @param $imgdata
     * @param $path
     * @param null $name    文件名
     * @param int $level
     * @return bool|string  成功则返回文件位置，失败false
     */
    public function moveImgdataforjpeg($path, $imgdata = null, $name = null, $isinsert = false, $level = 80)
    {
        $imgdata = empty($imgdata)? $this->imgdata : $imgdata;
        if($name===null){
            $pathname = $this->get_imgFilename();
        }else{
            $pathname = $isinsert? $this->get_imgFilename().$name : $name;
        }
        if ($this->checkdir($path)){
            if (imagejpeg($imgdata, $path.'/'.$pathname.'.jpg', $level)){
                return $path.'/'.$pathname.'.jpg';
            }else{
                return false;
            }
        }
        return false;
    }

    /**
     * 生成一个基于时间的文件名字符串（YmdHis+3位随机数）
     * @return string
     */
    public function get_imgFilename()
    {
        return date('YmdHis').rand(100, 999);
    }

}