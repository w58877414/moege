<?php
namespace moe\core;

class imgCode
{
    private $code;
    private $img;
    private $w;
    private $h;
    private $size;
    private $font;
    private $tag;
    function __construct($w, $h, $size, $tag = 'imgcode', $font = './moe_core/font/yahei-bold.ttf')
    {
        $this->code = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $this->w = $w;
        $this->h = $h;
        $this->size = $size;
        $this->tag = $tag;
        $this->font = $font;
        $this->img = imagecreate($this->w,$this->h);
        imagecolorallocate($this->img,236,236,236);
    }
    private function createString()
    {
        $_SESSION[$this->tag] = '';
        $tcolor = array();
        for ($i = 0;$i < 4;$i++){
            $tcolor[$i] = imagecolorallocate($this->img,rand(0,200),rand(0,200),rand(0,200));
        }
        for ($i = 0;$i < 4;$i++){
            $onecode = $this->code[rand(0,strlen($this->code)-1)];
            $_SESSION[$this->tag] .= $onecode;
            $angle = rand(-36, 36);
            $x = ($this->w * 0.1) + $i * ($this->w * 0.9 / 4);
            $y = rand($this->h * 0.6, $this->h * 0.8);
            imagettftext ($this->img, $this->size, $angle, $x, $y, $tcolor[$i], $this->font, $onecode);
        }
    }
    private function createOther()
    {
        for ($i = 0;$i < 10;$i++){
            $tcolor = imagecolorallocate($this->img,rand(0,255),rand(0,255),rand(0,255));
            imagestring($this->img, rand(1,2), rand(0,$this->w), rand(0,$this->h), '*', $tcolor);
        }
    }
    private function createline()
    {
        for ($i = 0;$i < 4;$i++){
            $tcolor = imagecolorallocate($this->img,rand(0,255),rand(0,255),rand(0,255));
            imageline($this->img, rand(0,$this->w), rand(0,$this->h), rand(0,$this->w), rand(0,$this->h), $tcolor);
        }
    }
    public function getImg()
    {
        $this->createOther();
        $this->createString();
        $this->createline();
        header("Content-type: image/gif");
        imagegif($this->img);
    }
}
