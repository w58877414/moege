<?php
/**
 * Created by PhpStorm.
 * User: 78742
 * Date: 2016/12/17 0017
 * Time: 17:45
 */
namespace moe\controller;

require_once 'moe_core/Controller.php';

class MoeDemo extends \moe\core\Controller
{
    /**
     * @var \moe\model\MoeDemoModel
     */
    public $model;
    public function __construct()
    {
        parent::__construct('MoeDemoModel','MoeDemo');
        session_start();
        //检测是否登录，否则跳转登录页面
        if(!(isset($_SESSION['a_id']) && $_SESSION['a_id'])) {
            if($_GET['v']!='login' && $_GET['v']!='logging'){
                header('Location: ?m='.$this->views.'&v=login');
            }
        }
        $ginfo = $_GET['v'];
        if(stripos($ginfo,'upd')!==false || stripos($ginfo,'add')!==false || stripos($ginfo,'del')!==false || stripos($ginfo,'clear')!==false || stripos($ginfo,'logout')!==false){
            if($_SESSION['a_level']==2 && stripos($ginfo,'admin')===false && stripos($ginfo,'log')===false){ $this->error_back('你无权操作！');}   //管理权限为2时只有浏览权限
            $this->model->admin_log();  //日志记录（为了正确记录登录,logging中在登陆成功处也添加了log函数）
        }
		/*if($ginfo=='memberUpdPass' || $ginfo=='memberUpd' || $ginfo=='memberClear' || $ginfo=='memberDel' || $ginfo=='adminUpdPass' || $ginfo=='adminUpd' || $ginfo=='adminDel' || $ginfo=='adminUpdPass' || $_GET['nid']=='3'){
			$this->error_back('本系统为测试展示用，账户修改功能暂且禁止。');
        }*/
        if (count($_POST) > 0){
            $_POST = $this->form_arr_filter($_POST)?:$this->error_back('存在非法字符！');
        }
    }
    public function index()
    {
        $data['title'] = '欢迎来到格萌网的后台';
        $this->useView('top', $data);
		$data['notice'] = $this->model->noticeNew();
        $data['overview'] = $this->model->indexOverview();
        $data['action'] = $this->model->indexAction();
        $this->useView('index', $data);
        $this->useView('bottom');
    }
    public function login()
    {
        $data['title'] = '格萌网管理系统';
        $this->useView('login',$data);
    }
    public function logging()
    {
        if(isset($_POST['submit']) && $_POST['submit']){
            $name = $_POST['username']?: $this->error_back($this->gset['user_null']);
            $pass = $_POST['password']?: $this->error_back($this->gset['pass_null']);
            if($_POST['rememberme']=='rememberme'){
                setcookie("username",$_POST['username']);
            }
            $salt = $this->model->login_username($name)?: $this->error_back($this->gset['user_nosearch']);
            $info = $this->model->logging($name, $pass, $salt);
            if ($info){
                $_SESSION['a_id'] = $info['a_id'];
                $_SESSION['a_username'] = $info['a_username'];
                $_SESSION['a_nickname'] = $info['a_nickname'];
                $_SESSION['a_head'] = $info['a_head'];
                $_SESSION['a_level'] = $info['a_level'];
                $this->model->admin_log();
                $this->succeed_href('登陆成功','?m='.$this->views);
            }else{
                $this->error_back('密码不正确');
            }
        }else{
            $this->error_back('非法访问！');
        }
    }
    public function logout()
    {
        if(session_destroy()){
            $this->succeed_href('退出登陆成功','?m='.$this->views);
        }else{
            $this->error_back('退出登陆失败');
        }
    }
    public function admin()
    {
        $data['title'] = '管理员';
        if($_SESSION['a_level']!=='0'){
            $aid = (int)$_SESSION['a_id'];
        }else{
            $aid = isset($_GET['aid'])? (int)$_GET['aid'] : (int)$_SESSION['a_id'];
            $pp = $_GET['pp']?:1;
            if(isset($_GET['searchkey']) && $_GET['searchkey']){
                $searchkey = $_GET['searchkey'];
                $searchtype = $_GET['searchtype'];
                $data['list'] = $this->model->adminlist($data['pagespp'], 'pp', $pp, 10, $searchtype, $searchkey);
            }else{
                $data['list'] = $this->model->adminlist($data['pagespp'], 'pp', $pp, 10);
            }
        }
        $data['info'] = $this->model->admininfo($aid);
        $p = $_GET['p']?:1;
        if(isset($_GET['$searchlogkey']) && $_GET['searchlogkey']){
            $searchlogkey = $_GET['searchlogkey'];
            $searchlogtype = $_GET['searchlogtype'];
            $data['log_list'] = $this->model->admin_log_list($data['pages'], 'p', $p, 10, $_SESSION['a_id'], $data['info']['a_level'], $searchlogtype, $searchlogkey);
            echo 1;
        }else{
            $data['log_list'] = $this->model->admin_log_list($data['pages'], 'p', $p, 10, $_SESSION['a_id'], $data['info']['a_level']);
        }
        $this->useView('top', $data);
        $this->useView('admin', $data);
        $this->useView('bottom');
    }
    public function adminAdd()
    {
        if (isset($_POST['ausername'])){
            $ausername = $_POST['ausername']?: $this->error_back($this->gset['user_null']);
            $this->form_str_username($ausername)?: $this->error_back($this->gset['user_err']);
            $this->model->admin_name_same($ausername)===0 ? : $this->error_back($this->gset['user_same']);
            $anickname = $_POST['anickname']?: $this->error_back($this->gset['nickname_null']);
            $this->form_str_nickname($anickname)?: $this->error_back($this->gset['nickname_err']);
            $mpass1 = $_POST['mpass1']?: $this->error_back($this->gset['pass_null']);
            $mpass2 = $_POST['mpass2']?: $this->error_back($this->gset['pass_diff']);
            $mpass = ($mpass1 === $mpass2)? $mpass1 : $this->error_back($this->gset['pass_diff']);
            $this->form_str_password($mpass)?: $this->error_back($this->gset['pass_err']);
            $alevel = $_POST['alevel'];
            if ($this->model->adminAdd($ausername, $anickname, $mpass, $alevel)){
                $this->succeed_href('管理员添加成功');
            }else{
                $this->error_back('添加管理员失败');
            }
        }else{
            $this->error_back('非法访问！');
        }
    }
    public function adminUpd()
    {
        if (isset($_POST['aid'])){
            if($_SESSION['a_level']!=='0'){
                $aid = (int)$_SESSION['a_id'];
                $level = '1';
            }else{
                $aid = $_POST['aid']? (int)$_POST['aid'] : (int)$_SESSION['aid'];
                $level = $_POST['level'];
            }
            $ousername = $_POST['ousername'];
            $username = $_POST['username'];
            if($ousername!=$username){$this->model->admin_name_same($username)===0 ? : $this->error_back($this->gset['user_same']);};
            $nickname = $_POST['nickname'];
            if($_POST['email'] == ''){
                $email = '';
            }else{
                $email = $this->form_str_email($_POST['email'])? $_POST['email'] : $this->error_back($this->gset['email_err']);
            }
            $phone = $_POST['phone'];
            if($this->model->adminUpd($aid, $username, $nickname, $email, $phone, $level)){
                if($_SESSION['a_id']==$aid){
                    $_SESSION['a_username'] = $username;
                    $_SESSION['a_nickname'] = $nickname;
                    $_SESSION['a_level'] = $level;
                }
                $this->succeed_href('信息修改成功');
            }else{
                $this->error_back('信息修改失败');
            }
        }else{
            $this->error_back('非法访问！');
        }
    }
    public function adminUpdPass()
    {
        if (isset($_POST['aid'])){
            if($_SESSION['a_level']!=='0'){
                $aid = (int)$_SESSION['a_id'];
            }else{
                $aid = $_POST['aid']? (int)$_POST['aid'] : (int)$_SESSION['aid'];
            }
            $mpass1 = $_POST['mpass1']?: $this->error_back($this->gset['pass_null']);
            $mpass2 = $_POST['mpass2']?: $this->error_back($this->gset['pass_diff']);
            $mpass = ($mpass1 === $mpass2)? $mpass1 : $this->error_back($this->gset['pass_diff']);
            $this->form_str_password($mpass)?: $this->error_back($this->gset['pass_err']);
        }else{
            $this->error_back('非法访问！');
        }
        if ($this->model->adminUpdPass($aid, $mpass)){
            $this->succeed_href('密码修改成功');
        }else{
            $this->error_back('密码修改失败');
        }
    }
    public function adminUpdhead()
    {
        if ($_FILES['mhead1']['error'] > 0){
            $this->error_back('非法访问！'.$_FILES['mhead1']['error']);
        }else{
            ($_FILES['mhead1']['size'] <= $this->gset['head_sizemax']*1024) ?: $this->error_back($this->gset['head_sizeerr']);
            if($_SESSION['a_level']!=='0'){
                $aid = (int)$_SESSION['a_id'];
            }else{
                $aid = $_POST['aid']? (int)$_POST['aid'] : (int)$_SESSION['aid'];
            }
            $headS = abs((int)$_POST['headS']);
            $headW = abs((int)$_POST['headW']);
            $headH = abs((int)$_POST['headH']);
            $headT = abs((int)$_POST['headT']);
            $headL = abs((int)$_POST['headL']);
            require_once 'moe_core/imgGD.php';
            $imgupload = new \moe\core\imgGD($_FILES['mhead1']['name'],$_FILES['mhead1']['type'],$_FILES['mhead1']['size'],$_FILES['mhead1']['tmp_name'],true);
            $imgdata = $imgupload->alterImgsize($headW,$headH);
            $imgdata = $imgupload->clippingImg($headL,$headT,$headS,$headS,$imgdata);
            $imghead = $imgupload->moveImgdataforjpeg(DEFALUT_UPHEAD,$imgdata,'admin'.$aid)?: $this->error_back($this->gset['img_uploaderr']);
            $this->model->adminUpdHead($aid, $imghead)? $this->succeed_href($this->gset['head_sqlsuc']) : $this->error_back($this->gset['head_sqlerr']);
            if($_SESSION['a_id']===$aid){ $_SESSION['a_head'] = $imghead;}
        }
    }
    public function adminDel()
    {
        if ($_SESSION['a_level']==='0'||$_SESSION['a_id']==$_GET['aid']){
            $aid = $_GET['aid'];
            if ($this->model->adminDel($aid)){
                if($_SESSION['a_id']==$_GET['aid']){
                    session_destroy();
                    $this->succeed_href('删除管理员成功','?m='.$this->views.'&v=login');
                }else{
                    $this->succeed_href('删除管理员成功','?m='.$this->views.'&v=admin');
                }
            }else{
                $this->error_back('删除管理员失败');
            }
        }else{
            $this->error_back('您无权操作');
        }
    }
    public function notice()
    {
        $data['title'] = '网站公告管理';
        $p = $_GET['p'] ? $_GET['p'] : 1;
        $search = $_GET['search']? $_GET['search'] : null;
        $data['noticelist'] = $this->model->noticeList($data['pages'], 'p', $p, 12, $search);
        $this->useView('top', $data);
        $this->useView('notice_list', $data);
        $this->useView('bottom');
    }
    public function noticeAdd()
    {
        if(empty($_POST['title']) && empty($_POST['editorValue'])){
            $data['title'] = '发布新公告';
            $this->useView('top', $data);
            $this->useView('notice_upd', $data);
            $this->useView('bottom');
        }else{
            $title = $_POST['title'];
            $article = $_POST['editorValue'];
            $ctime = $_POST['ctime'];
            $this->date_format_check($ctime)?: $this->error_back('时间格式不正确');
            if($this->model->noticeAdd($title, $article, $ctime)){
                $this->succeed_href('发布成功','?m='.$this->views.'&v=notice');
            }else{
                $this->error_back('发布失败');
            }
        }
    }
    public function noticeUpd()
    {
        $nid = (int)$_GET['nid']?: $this->error_back('非法访问');
        if(empty($_POST['title']) && empty($_POST['editorValue'])){
            $data['title'] = '网站公告修改';
            $data['notice'] = $this->model->noticeOne($nid)?: $this->error_back('无此ID公告');
            $this->useView('top', $data);
            $this->useView('notice_upd', $data);
            $this->useView('bottom');
        }else{
            $title = $_POST['title'];
            $article = $_POST['editorValue'];
            $ctime = $_POST['ctime'];
            $this->date_format_check($ctime)?: $this->error_back('时间格式不正确');
            if($this->model->noticeUpd($nid, $title, $article, $ctime)){
                $this->succeed_href('修改成功','?m='.$this->views.'&v=notice');
            }else{
                $this->error_back('修改失败');
            }
        }
    }
    public function noticeDel()
    {
        $nid = (int)$_GET['nid']?: $this->error_back('非法访问');
        if($this->model->noticeDel($nid)){
            $this->succeed_href('删除成功');
        }else{
            $this->error_back('删除失败');
        }
    }
    public function pic()
    {
        $data['title'] = '作品管理';
        $order = $_GET['o'] ? $_GET['o'] : '';
        $p = $_GET['p'] ? $_GET['p'] : 1;
        $pic_id = $_GET['picid']? 'picid='.$_GET['picid'] : null;
        $tag_id = $_GET['tagid']? 'tagid='.$_GET['tagid'] : null;
        $type_id = $_GET['typeid']? 'typeid='.$_GET['typeid'] : null;
        $m_id = $_GET['mid']? 'mid='.$_GET['mid'] : null;
        $f_id = $_GET['fid']? 'fid='.$_GET['fid'] : null;
        $date = $_GET['date']? 'date='.$_GET['date'] : null;
        $search = $_GET['search']? 'search='.$_GET['search'] : null;
        $isshow = $_GET['isshow']? 'isshow='.$_GET['isshow'] : null;
        $data['piclist'] = $this->model->picList($data['pages'], $data['datelist'], 'p', $p, 12, $order, $pic_id, $tag_id, $type_id, $m_id, $date, $search, $f_id, $isshow);
        $this->useView('top', $data);
        $this->useView('pic_list',$data);
        $this->useView('bottom');
    }
    public function picIn()
    {
        $data['title'] = '图片详细信息';
        $pid= $_GET['pid']?:$this->error_back('ID为空');
        $data['pic'] = $this->model->picIn($pid);
        $data['typelist'] = $this->model->picTypeList();
        $data['taglist'] = $this->model->picTagList($pid);
        $this->useView('top', $data);
        $this->useView('pic_In', $data);
        $this->useView('bottom');
    }
    public function picDel()
    {
        $pid = $_GET['pid']?:$this->error_back('ID为空！');
        $str = $this->model->picDel($pid);
        if ($str===true){
            $this->succeed_href('删除图片成功');
        }else{
            $this->error_back($str);
        }
    }
    public function picUpd()
    {
        setcookie('pic_upd_return','1');
        if (isset($_POST['pid'])){
            $pid = (int)$_POST['pid']?:$this->error_back('ID为空！');
            $picname = $_POST['picname']?: $this->error_back($this->gset['picname_null']);
            $this->form_str_picname($picname)?: $this->error_back($this->gset['picname_err']);
            $picinfo = $_POST['picinfo'];
            $pictypeid = (int)$_POST['pictypeid'];
            $pictool = $_POST['pictool'];
            if ($this->model->picUpd($pid, $picname, $picinfo, $pictypeid, $pictool)){
                $this->succeed_href('修改作品信息成功');
                setcookie('pic_upd_return', '', time()-3600);
            }else{
                $this->error_back('修改作品信息失败！');
            }
        }else{
            $this->error_back('非法访问！');
        }
    }
    public function picUpdDis()
    {
        setcookie('pic_upd_return', '', time()-3600);
        $this->succeed_href();
    }
    public function picIsShow()
    {
        $pid = $_GET['pid']?:$this->error_back('ID为空！');
        $isshow = isset($_GET['isshow'])?$_GET['isshow']:$this->error_back('非法访问！');
        if($isshow==1){
            $this->model->picDisShow($pid) ? $this->succeed_href('取消审核成功') : $this->error_back('取消审核失败');
        }else{
            $this->model->picEnShow($pid) ? $this->succeed_href('审核成功') : $this->error_back('审核失败');
        }
    }
    public function picTagAdd()
    {
        $pid= $_GET['pid']?:$this->error_back('ID为空');
        $tagname = $_POST['tagname'];
        if (!$_GET['pid']){
            echo 'ID为空';
        }elseif (!$_POST['tagname']){
            echo $this->gset['tag_null'];
        }elseif (!$this->form_str_tagname($tagname)){
            echo $this->gset['tag_err'];
        }elseif ($this->model->picTagAdd($pid, $tagname)){
            echo '标签-'.$tagname.'-添加成功';
        }else{
            echo '标签-'.$tagname.'-添加失败';
        }
        exit(0);
    }
    public function picTagDel()
    {
        $pid = $_GET['pid']?:$this->error_back('ID为空！');
        $tagid = $_GET['tagid']?:$this->error_back('ID为空！');
        if ($this->model->picTagDel($pid, $tagid)){
            $this->succeed_href('删除标签应用成功');
        }else{
            $this->error_back('删除标签应用失败');
        }
    }
    public function type()
    {
        $data['title'] = '分类管理';
        $p = $_GET['p'] ? $_GET['p'] : 1;
        $search = $_GET['search']? $_GET['search'] : null;
        $order = $_GET['o'] ? $_GET['o'] : null;
        $data['typelist'] = $this->model->typeList($data['pages'], 'p', $p, 12, $search, $order);
        $this->useView('top', $data);
        $this->useView('type_list', $data);
        $this->useView('bottom');
    }
    public function typeAdd()
    {
        if (isset($_GET['typename'])){
            $typename = $_GET['typename']?: $this->error_back('分类名不能为空！');
            $typeinfo = $_GET['typeinfo'];
        }else{
            $this->error_back('非法访问！');
        }
        if ($this->model->typeAdd($typename, $typeinfo)){
            $this->succeed_href('新增分类成功');
        }else{
            $this->error_back('新增分类失败！');
        }
    }
    public function typeDel()
    {
        $typeid = $_GET['typeid']?:$this->error_back('ID为空！');
        if ($this->model->typeListNum($typeid)){
            $this->error_back('分类下有作品，不能删除该分类！');
        }elseif ($this->model->typeDel($typeid)){
            $this->succeed_href('删除该分类成功');
        }else{
            $this->error_back('删除分类失败！');
        }
    }
    public function typeUpd()
    {
        if (isset($_POST['typeid'])){
            $typeid = (int)$_POST['typeid']?:$this->error_back('ID为空！');
            $typename = $_POST['typename']?: $this->error_back('分类名不能为空！');
            $typeinfo = $_POST['typeinfo'];
        }else{
            $this->error_back('非法访问！');
        }
        if ($this->model->typeUpd($typeid, $typename, $typeinfo)){
            $this->succeed_href('修改分类成功');
        }else{
            $this->error_back('修改分类失败');
        }
    }
    public function tag()
    {
        $data['title'] = '标签管理';
        $p = $_GET['p'] ? $_GET['p'] : 1;
        $search = $_GET['search']? $_GET['search'] : null;
        $date = $_GET['date']? $_GET['date'] : null;
        $order = $_GET['o'] ? $_GET['o'] : null;
        $data['taglist'] = $this->model->tagList($data['pages'], $data['datelist'], 'p', $p, 40, $search, $date, $order);
        $this->useView('top', $data);
        $this->useView('tag_list', $data);
        $this->useView('bottom');
    }
    public function tagAdd()
    {
        $tagname = $_POST['tagname']?: $this->error_back($this->gset['tag_null']);
        $this->form_str_tagname($tagname)?: $this->error_back($this->gset['tag_err']);
        $this->model->tag_name_id($tagname)===false?: $this->error_back($this->gset['tag_same']);
        if ($this->model->tagAdd($tagname)){
            $this->succeed_href('新增标签成功');
        }else{
            $this->error_back('新增标签失败！');
        }
    }
    public function tagClear(){
        $tagid= $_GET['tagid']?:$this->error_back('ID为空');
        if ($this->model->tagClear($tagid)){
            $this->succeed_href('清空该标签成功');
        }else{
            $this->error_back('清空标签失败！');
        }
    }
    public function tagDel(){
        $tagid= $_GET['tagid']?:$this->error_back('ID为空');
        if ($this->model->tagListNum($tagid)){
            $this->error_back('该标签下有作品应用，不能删除该标签（可点击“清空”按钮，清空应用后再删除）');
        }elseif ($this->model->tagDel($tagid)){
            $this->succeed_href('删除该标签成功');
        }else{
            $this->error_back('删除标签失败！');
        }
    }
    public function tagUpd()
    {
        if (isset($_POST['tagid'])) {
            $tagid = (int)($_POST['tagid'])?: $this->error_back('ID为空！');
            $tagname = $_POST['tagname']?: $this->error_back($this->gset['tag_null']);
            $this->form_str_tagname($tagname)?: $this->error_back($this->gset['tag_err']);
            $this->model->tag_name_id($tagname)===false?: $this->error_back($this->gset['tag_same']);
        }else{
            $this->error_back('非法访问！');
        }
        if ($this->model->tagUpd($tagid, $tagname)){
            $this->succeed_href('修改标签成功');
        }else{
            $this->error_back('修改标签失败');
        }
    }
    public function group()
    {
        $data['title'] = '群组管理';
        $p = $_GET['p'] ? $_GET['p'] : 1;
        $search = $_GET['search']? $_GET['search'] : null;
        $mid = $_GET['mid'] ? $_GET['mid'] : null;
        $order = $_GET['o'] ? $_GET['o'] : null;
        $data['grouplist'] = $this->model->groupList($data['pages'], 'p', $p, 12, $search, $order, $mid);
        $this->useView('top', $data);
        $this->useView('group_list', $data);
        $this->useView('bottom');
    }
    public function groupIn()
    {
        $groupid= $_GET['gid']?:$this->error_back('ID为空');
        $data['title'] = '群组详细';
        $this->useView('top', $data);
        $this->useView('group_In', $data);
        $this->useView('bottom');
    }
    public function groupAdd()
    {
        if (isset($_POST['groupname'])){
            $groupname = $_POST['groupname']?: $this->error_back('群组名不能为空！');
            $groupinfo = $_POST['groupinfo'];
        }else{
            $this->error_back('非法访问！');
        }
        if ($this->model->groupAdd($groupname, $groupinfo)){
            $this->succeed_href('新增群组成功');
        }else{
            $this->error_back('新增群组失败！');
        }
    }
    public function groupDel()
    {
        $groupid = $_GET['gid']?:$this->error_back('ID为空！');
        if ($this->model->groupDel($groupid)){
            $this->succeed_href('删除该群组成功');
        }else{
            $this->error_back('删除群组失败！');
        }
    }
    public function groupUpd()
    {
        if (isset($_POST['gid'])){
            $groupid = (int)$_POST['gid']?:$this->error_back('ID为空！');
            $groupname = $_POST['groupname']?: $this->error_back('群组名不能为空！');
            $groupinfo = $_POST['groupinfo'];
        }else{
            $this->error_back('非法访问！');
        }
        if ($this->model->groupUpd($groupid, $groupname, $groupinfo)){
            $this->succeed_href('修改群组成功');
        }else{
            $this->error_back('修改群组失败');
        }
    }
    public function member()
    {
        $data['title'] = '会员管理';
        $p = $_GET['p'] ? $_GET['p'] : 1;
        $search = $_GET['search']? $_GET['search'] : null;
        $fid = $_GET['fid'] ? $_GET['fid'] : null;
        $order = $_GET['o'] ? $_GET['o'] : null;
        $groupid = $_GET['gid'] ? $_GET['gid'] : null;
        $data['memberlist'] = $this->model->memberList($data['pages'], 'p', $p, 12, $search, $order, $fid, $groupid);
        $this->useView('top', $data);
        $this->useView('member_list', $data);
        $this->useView('bottom');
    }
    public function memberIn()
    {
        $data['title'] = '会员详细信息';
        $this->useView('top', $data);
        $mid= $_GET['mid']?:$this->error_back('ID为空');
        $data['m_info'] = $this->model->memberIn($mid);
        $data['m_piclist'] = $this->model->memberPic($mid);
        $data['m_follow'] = $this->model->memberFollow($mid);
        $data['m_group'] = $this->model->memberGroup($mid);
        $data['m_favorite'] = $this->model->memberFavorite($mid);
        $this->useView('member_In', $data);
        $this->useView('bottom');
    }
    public function memberAdd()
    {
        if (isset($_POST['mname'])){
            $mname = $_POST['mname']?: $this->error_back($this->gset['user_null']);
            $this->form_str_username($mname)?: $this->error_back($this->gset['user_err']);
            $this->model->member_name_same($mname)===0 ? : $this->error_back($this->gset['user_same']);
            $mnickname = $_POST['mnickname']?: $this->error_back($this->gset['nickname_null']);
            $this->form_str_nickname($mnickname)?: $this->error_back($this->gset['nickname_err']);
            $mpass1 = $_POST['mpass1']?: $this->error_back($this->gset['pass_null']);
            $mpass2 = $_POST['mpass2']?: $this->error_back($this->gset['pass_diff']);
            $mpass = ($mpass1 === $mpass2)? $mpass1 : $this->error_back($this->gset['pass_diff']);
            $this->form_str_password($mpass)?: $this->error_back($this->gset['pass_err']);
        }else{
            $this->error_back('非法访问！');
        }
        if ($this->model->memberAdd($mname, $mnickname, $mpass)){
            $this->succeed_href('用户添加成功');
        }else{
            $this->error_back('添加用户失败');
        }
    }
    public function memberClear()
    {
        $mid = $_GET['mid']?:$this->error_back('ID为空');
        if ($this->model->memberPicNum($mid)==0){
            $this->error_back('该用户无作品');
        }
        $flag = $this->model->memberClear($mid);
        if ($flag===true){
            $this->succeed_href('清空用户作品成功');
        }else{
            $this->error_back($flag);
        }
    }
    public function memberDel()
    {
        $mid= $_GET['mid']?:$this->error_back('ID为空');
        if ($this->model->memberListNum($mid)){
            $this->error_back('该用户下有作品，请先进行对应的清空操作');
        }elseif ($this->model->memberDel($mid)){
            $this->succeed_href('删除用户成功','?m='.$this->views.'&v=member');
        }else{
            $this->error_back('删除用户失败！');
        }
    }
    public function memberFollowDel()
    {
        $mid= $_GET['mid']?:$this->error_back('ID为空');
        $fmid= $_GET['fmid']?:$this->error_back('ID为空');
        if ($this->model->memberFollowDel($mid, $fmid)){
            $this->succeed_href('取消关注成功');
        }else{
            $this->error_back('取消关注失败！');
        }
    }
    public function memberGroupDel()
    {
        $mid= $_GET['mid']?:$this->error_back('ID为空');
        $groupid= $_GET['gid']?:$this->error_back('ID为空');
        if ($this->model->memberGroupDel($mid, $groupid)){
            $this->succeed_href('退出群组成功');
        }else{
            $this->error_back('退出群组失败！');
        }
    }
    public function memberFavoriteDel()
    {
        $mid= $_GET['mid']?:$this->error_back('ID为空');
        $pid= $_GET['pid']?:$this->error_back('ID为空');
        if ($this->model->memberFavoriteDel($mid, $pid)){
            $this->succeed_href('取消收藏成功');
        }else{
            $this->error_back('取消收藏失败！');
        }
    }
    public function memberUpd()
    {
        setcookie('member_upd_return','1');
        if (isset($_POST['m_id'])){
            $mid = (int)$_POST['m_id']?:$this->error_back('ID为空！');
            $moname = $_POST['m_oname']?: $this->error_back($this->gset['user_null']);
            $mname = $_POST['m_name']?: $this->error_back($this->gset['user_null']);
            $this->form_str_username($mname)?: $this->error_back($this->gset['user_err']);
            if ($moname != $mname){$this->model->member_name_same($mname)===0 ? : $this->error_back($this->gset['user_same']);}
            $mnickname = $_POST['m_nickname']?: $this->error_back($this->gset['nickname_null']);
            $this->form_str_nickname($mnickname)?: $this->error_back($this->gset['nickname_err']);
            $mgender = in_array($_POST['m_gender'], $this->gset['gender_arr'])?$_POST['m_gender']: $this->error_back($this->gset['gender_err']);
            $mbirthday = $this->date_format_check($_POST['m_birthday'])? $_POST['m_birthday'] : $this->error_back($this->gset['birthday_err']);
            $mjob = (strlen($_POST['m_job']) < $this->gset['job_len'])? $_POST['m_job'] : $this->error_back($this->gset['job_err']);
            $maddress = (strlen($_POST['m_address']) < $this->gset['address_len'])? $_POST['m_address'] : $this->error_back($this->gset['address_err']);
            $memail = $this->form_str_email($_POST['m_email'])? $_POST['m_email'] : $this->error_back($this->gset['email_err']);
            $mqq = ((is_numeric($_POST['m_qq']) || $_POST['m_qq']=='') && strlen($_POST['m_qq']) < $this->gset['qq_len'])? $_POST['m_qq'] : $this->error_back($this->gset['qq_err']);
            $mtool = (strlen($_POST['m_tool']) < $this->gset['tool_len']) ? $_POST['m_tool'] :  $this->error_back($this->gset['tool_err']);
            $mtag = (strlen($_POST['m_tag']) < $this->gset['usertag_len']) ? $_POST['m_tag'] :  $this->error_back($this->gset['usertag_err']);
            $minfo = (strlen($_POST['m_info']) < $this->gset['userinfo_len']) ? $_POST['m_info'] :  $this->error_back($this->gset['userinfo_err']);
            if ($this->model->memberUpd($mid, $mname, $mnickname, $mgender, $mbirthday, $mjob, $maddress, $memail, $mqq, $mtool, $mtag, $minfo)){
                $this->succeed_href('修改用户信息成功');
                setcookie('member_upd_return', '', time()-3600);
            }else{
                $this->error_back('修改用户信息失败！');
            }
        }else{
            $this->error_back('非法访问！');
        }
    }
    public function memberUpdDis()
    {
        setcookie('member_upd_return', '', time()-3600);
        $this->succeed_href();
    }
    public function memberUpdPass()
    {
        if (isset($_POST['mid'])){
            $mid = (int)$_POST['mid']?:$this->error_back('ID为空！');
            $mpass1 = $_POST['mpass1']?: $this->error_back($this->gset['pass_null']);
            $mpass2 = $_POST['mpass2']?: $this->error_back($this->gset['pass_diff']);
            $mpass = ($mpass1 === $mpass2)? $mpass1 : $this->error_back($this->gset['pass_diff']);
            $this->form_str_password($mpass)?: $this->error_back($this->gset['pass_err']);
        }else{
            $this->error_back('非法访问！');
        }
        if ($this->model->memberUpdPass($mid, $mpass)){
            $this->succeed_href('密码修改成功');
        }else{
            $this->error_back('密码修改失败');
        }
    }
    public function memberUpdhead()
    {
        if ($_FILES['mhead1']['error'] > 0){
            $this->error_back('非法访问！'.$_FILES['mhead1']['error']);
        }else{
            ($_FILES['mhead1']['size'] <= $this->gset['head_sizemax']*1024) ?: $this->error_back($this->gset['head_sizeerr']);
            $mid = (int)$_POST['mid']?:$this->error_back('ID为空！');
            $headS = abs((int)$_POST['headS']);
            $headW = abs((int)$_POST['headW']);
            $headH = abs((int)$_POST['headH']);
            $headT = abs((int)$_POST['headT']);
            $headL = abs((int)$_POST['headL']);
            require_once 'moe_core/imgGD.php';
            $imgupload = new \moe\core\imgGD($_FILES['mhead1']['name'],$_FILES['mhead1']['type'],$_FILES['mhead1']['size'],$_FILES['mhead1']['tmp_name']);
            $imgdata = $imgupload->alterImgsize($headW,$headH);
            $imgdata = $imgupload->clippingImg($headL,$headT,$headS,$headS,$imgdata);
            $imghead = $imgupload->moveImgdataforjpeg(DEFALUT_UPHEAD,$imgdata,$mid)?: $this->error_back($this->gset['img_uploaderr']);
            $this->model->memberUpdHead($mid, $imghead)? $this->succeed_href($this->gset['head_sqlsuc']) : $this->error_back($this->gset['head_sqlerr']);
        }
    }
}