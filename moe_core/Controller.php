<?php
/**
 * Created by PhpStorm.
 * User: 78742
 * Date: 2016/12/17 0017
 * Time: 17:36
 */
namespace moe\core;
require_once 'moe_core/base.php';
class Controller extends \moe\core\base
{
    public $model,$views;
    public function __construct($m, $v)
    {
        parent::__construct();
        $this->model = $this->loadModel($m);
        $this->views = $this->loadViews($v);
    }
    public function __call($funname, $args){
        $this->found404();
    }
    public function loadModel($model)
    {
        if (!\is_file('moe_model/'.$model.'.php')){
            die('您请求的model不存在');
        }
        require_once 'moe_model/'.$model.'.php';
        $model_class = '\\moe\\model\\'.$model;
        return new $model_class();
    }
    public function loadViews($views)
    {
        if (!\is_dir('moe_view/'.$views)){
            die('您请求的views不存在');
        }
        return $views;
    }
    public function useView($view, $data = null)
    {
        if (!\is_file('moe_view/'.$this->views.'/'.$view.'.mob')){
            die('您请求的view不存在');
        }
        $pageurl = 'moe_view/'.$this->views;
        $pageviews = '?m='.$this->views;
        require_once 'moe_view/'.$this->views.'/'.$view.'.mob';
    }
}