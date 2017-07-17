<?php
/**
 * Created by PhpStorm.
 * User: 78742
 * Date: 2016/12/17 0017
 * Time: 18:22
 */

namespace moe\model;

use moe\core\imgGD;

require_once 'moe_core/Model.php';

class MoeDemoModel extends \moe\core\Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function indexOverview()
    {
        $overview = array();
        $overview['pic_num'] = $this->conn->selectAll('select count(*) from moe_pic',null,0);
        $overview['tag_num'] = $this->conn->selectAll('select count(*) from moe_tag',null,0);
        $overview['type_num'] = $this->conn->selectAll('select count(*) from moe_type',null,0);
        $overview['group_num'] = $this->conn->selectAll('select count(*) from moe_group',null,0);
        $overview['member_num'] = $this->conn->selectAll('select count(*) from moe_member',null,0);
        $overview['admin_num'] = $this->conn->selectAll('select count(*) from moe_admin',null,0);
        return $overview;
    }
    public function indexAction()
    {
        $actionList = array();
        $actionList['pic'] = $this->conn->selectAll('select pic_id,pic_name,pic_ctime from moe_pic order by pic_ctime desc limit 0,5',null,2);
        $actionList['tag'] = $this->conn->selectAll('select * from moe_tag order by tag_ctime desc limit 0,5',null,2);
        $actionList['group'] = $this->conn->selectAll('select group_id,group_name,group_ctime from moe_group order by group_ctime desc limit 0,5',null,2);
        $actionList['member'] = $this->conn->selectAll('select m_id,m_name,m_ctime from moe_member order by m_ctime desc limit 0,5',null,2);
        return $actionList;
    }

    public function logging($user,$pass,$salt)
    {
        $bing[':username'] = [$user,'str'];
        $password = $this->moe_password_hash($pass, $salt);
        $bing[':password'] = [$password,'str'];
        return $this->conn->selectAll('select a_id,a_username,a_nickname,a_head,a_level from moe_admin where a_username=:username and a_password=:password',$bing,1);
    }
    public function login_username($username)
    {
        $bing[':username'] = [$username,'str'];
        return $this->conn->selectAll('select a_salt from moe_admin where a_username=:username',$bing,0);
    }

    public function admin_log($id = 0)
    {
        $aid = $_SESSION['a_id']?:$id;
        $aurl = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        $ip = $this->getIP();
        $atime = date('Y-m-d H:i:s');
        $bing = [
            ':aid' => [$aid ,'int'],
            ':aurl' => [$aurl ,'atr'],
            ':ip' => [$ip ,'str'],
            ':atime' => [$atime ,'str'],
        ];
        return $this->conn->queryChange('insert into moe_admin_log(aid,aurl,ip,atime) values(:aid,:aurl,:ip,:atime)',$bing);
    }
    public function admin_log_list(&$pages, $pflag, $pagesNow = 1, $num = 10, $aid = 0, $level = '1', $searchtype = null, $searchkey = null)
    {
        $bing = array();
        $sqlwhere = '';
        $sqlsearch = '';
        if($level !== '0'){
            $sqlwhere = 'where ';
            $bing[':aid'] = [$aid, 'int'];
            $sqlsearch .= 'aid=:aid ';
        }
        if($searchtype!==null){
            if($sqlwhere == null){
                $sqlwhere = 'where ';
            }
            $sqlsearch = 'where ';
            switch ($searchtype){
                case 'aid':$sqlsearch .= 'aid like :key ';break;
                case 'aurl':$sqlsearch .= 'aurl like :key ';break;
                case 'ip':$sqlsearch .= 'ip like :key ';break;
                default:$sqlsearch .= 'aurl like :key ';
            }
            $bing[':key'] = ['%'.$searchkey.'%', 'str'];
        }
        $sqlnum = $this->conn->selectAll('select count(*) from moe_admin_log '.$sqlwhere.$sqlsearch,$bing,0);
        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);
        $start = ($pagesNow-1)*$num;
        $bing[':start'] = [$start,'int'];
        $bing[':num'] = [$num,'int'];
        return $this->conn->selectAll('select * from moe_admin_log '.$sqlwhere.$sqlsearch.' order by atime desc limit :start,:num',$bing);
    }

    public function adminlist(&$pages, $pflag, $pagesNow = 1, $num = 10, $searchtype = null, $searchkey = null)
    {
        $bing = array();
        $sqlsearch = '';
        if($searchtype!==null){
            $sqlsearch = 'where ';
            switch ($searchtype){
                case 'id':$sqlsearch .= 'a_id like :key ';break;
                case 'nickname':$sqlsearch .= 'a_nickname like :key ';break;
                case 'username':$sqlsearch .= 'a_username like :key ';break;
                default:$sqlsearch .= 'a_username like :key ';
            }
            $bing[':key'] = ['%'.$searchkey.'%', 'str'];
        }
        $sqlnum = $this->conn->selectAll('select count(*) from moe_admin '.$sqlsearch,$bing,0);
        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);
        $start = ($pagesNow-1)*$num;
        $bing[':start'] = [$start,'int'];
        $bing[':num'] = [$num,'int'];
        return $this->conn->selectAll('select a_id,a_username,a_nickname,a_email,a_level,a_ctime from moe_admin '.$sqlsearch.'order by a_ctime desc limit :start,:num',$bing);
    }
    public function admininfo($aid)
    {
        $bing[':aid'] = [$aid, 'int'];
        return $this->conn->selectAll('select * from moe_admin where a_id=:aid',$bing,1);

    }
    public function adminAdd($username, $nickname, $pass, $level)
    {
        $salt = $this->moe_password_salt();
        $password = $this->moe_password_hash($pass, $salt);
        $head = DEFALUT_UPHEAD.'/defalut.png';
        $time = date('Y-m-d H:i:s');
        $bing=[
            ':username' => [$username, 'str'],
            ':nickname' => [$nickname, 'str'],
            ':password' => [$password, 'str'],
            ':salt' => [$salt, 'str'],
            ':ahead' => [$head, 'str'],
            ':ctime' => [$time, 'str'],
            ':alevel' => [$level, 'str']
        ];
        return $this->conn->queryChange('insert into moe_admin(a_username,a_nickname,a_password,a_salt,a_head,a_ctime,a_level) values(:username,:nickname,:password,:salt,:ahead,:ctime,:alevel);', $bing);
    }
    public function adminUpd($aid, $username, $nickname, $email, $phone, $level)
    {
        $bing = [
            ':aid' => [$aid, 'int'],
            ':username' => [$username, 'str'],
            ':nickname' => [$nickname, 'str'],
            ':email' => [$email, 'str'],
            ':phone' => [$phone, 'str'],
            ':alevel' => [$level, 'str'],
        ];
        return $this->conn->queryChange('update moe_admin set a_username=:username,a_nickname=:nickname,a_email=:email,a_phone=:phone,a_level=:alevel where a_id=:aid', $bing);
    }
    public function adminUpdPass($id, $pass){
        $salt = $this->moe_password_salt();
        $password = $this->moe_password_hash($pass, $salt);
        $bing=[
            ':id' => [$id, 'int'],
            ':password' => [$password, 'str'],
            ':salt' => [$salt, 'str']
        ];
        return $this->conn->queryChange('update moe_admin set a_password=:password,a_salt=:salt where a_id=:id', $bing);
    }
    public function adminUpdHead($id,$head)
    {
        $bing=[
            ':id' => [$id, 'int'],
            ':head' => [$head, 'str']
        ];
        return $this->conn->queryChange('update moe_admin set a_head=:head where a_id=:id', $bing);
    }
    public function adminDel($aid)
    {
        $bing[':aid'] = [$aid, 'int'];
        $flag = $this->conn->queryChange('delete from moe_admin where a_id=:aid',$bing);
        $this->conn->queryChange('delete from moe_admin_log where aid=:aid',$bing);
        return $flag;
    }
    public function admin_name_same($str)
    {
        $bing[':username'] = [$str, 'str'];
        return $this->conn->selectAll('select count(*) from moe_admin where a_username=:username', $bing, 0);
    }
	public function noticeNew()
	{
		return $this->conn->selectAll('select id,title from moe_notice group by ctime desc', null, 1);
	}
    public function noticeList(&$pages, $pflag, $pagesNow = 1, $num = 10, $search = null)
    {
        if ($search){
            $bing[':search'] = ['%'.$search.'%', 'str'];
            $sqlsearch = 'where title like :search ';
        }
        $sqlnum = $this->conn->selectAll('select count(*) from moe_notice '.$sqlsearch, $bing, 0);
        $start = ($pagesNow-1)*$num;
        $bing[':start'] = [$start,'int'];
        $bing[':num'] = [$num,'int'];
        $sql = 'select id,title,ctime from moe_notice '.$sqlsearch.'group by ctime limit :start,:num';
        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);
        return $this->conn->selectAll($sql, $bing);
    }
    public function noticeOne($nid)
    {
        $bing[':nid'] = [$nid, 'int'];
        return $this->conn->selectAll('select * from moe_notice where id=:nid', $bing, 1);
    }
    public function noticeAdd($title, $article, $time)
    {
        $bing =[
            ':title' => [$title, 'str'],
            ':article' => [$article, 'str'],
            ':ctime' => [$time, 'str'],
        ];
        return $this->conn->queryChange('insert into moe_notice(title,article,ctime) values(:title,:article,:ctime)', $bing);
    }
    public function noticeUpd($nid, $title, $article, $time)
    {
        $bing =[
            ':nid' => [$nid, 'int'],
            ':title' => [$title, 'str'],
            ':article' => [$article, 'str'],
            ':ctime' => [$time, 'str'],
        ];
        return $this->conn->queryChange('update moe_notice set title=:title,article=:article,ctime=:ctime where id=:nid', $bing);
    }
    public function noticeDel($nid)
    {
        $bing[':nid'] = [$nid, 'int'];
        return $this->conn->queryChange('delete from moe_notice where id=:nid', $bing);
    }

    /**
     * pic列表
     * @param $pages    用于返回分页代码
     * @param $datelist 用于返回月份分类列表
     * @param string $pflag  分页标示
     * @param int $pagesNow 当前页码
     * @param int $num  每页记录数
     * @param null $order   排序规则
     * @param array ...$where_arr   数据库where条件
     * @return mixed
     */
    public function picList(&$pages, &$datelist, $pflag, $pagesNow = 1, $num = 10, $order = null, ...$where_arr)
    {
        $where = '';
        $bing = array();
        switch ($order){
            case 'alltimes':$order = 'order by alltimes desc';break;
            case 'allgrade':$order = 'order by allgrade desc';break;
            case 'allgrades':$order = 'order by allgrades desc';break;
            case 'daytimes':$order = 'order by daytimes desc';break;
            case 'daygrade':$order = 'order by daygrade desc';break;
            case 'daygrades':$order = 'order by daygrades desc';break;
            case 'weektimes':$order = 'order by daytimes desc';break;
            case 'weekgrade':$order = 'order by daygrade desc';break;
            case 'weekgrades':$order = 'order by daygrades desc';break;
            default:$order = 'order by pic_ctime desc,p.pic_id desc';
        }
        $start = ($pagesNow-1)*$num;
        $date = false;
        foreach ($where_arr as $k=>$v){
            if (strpos($v, 'picid=')!==false){
                $where .= ($where=='')?'where ':'and ';
                $picid = substr($v, strpos($v, '=')+1);
                $bing[':picid'] = [$picid,'int'];
                $where .= 'pic_id=:picid ';
            }elseif (strpos($v, 'tagid=')!==false){
                $where .= ($where=='')?'where ':'and ';
                $tagid = substr($v, strpos($v, '=')+1);
                $bing[':tagid'] = ['%'.$tagid.'%','str'];
                $where .= 'pic_tagid like :tagid ';
            }elseif (strpos($v, 'typeid=')!==false){
                $where .= ($where=='')?'where ':'and ';
                $typeid = substr($v, strpos($v, '=')+1);
                $bing[':typeid'] = [$typeid,'int'];
                $where .= 'pic_typeid=:typeid ';
            }elseif (strpos($v, 'mid=')!==false){
                $where .= ($where=='')?'where ':'and ';
                $mid = substr($v, strpos($v, '=')+1);
                $bing[':mid'] = [$mid,'int'];
                $where .= 'p.m_id=:mid ';
            }elseif (strpos($v, 'search=')!==false){
                $where .= ($where=='')?'where ':'and ';
                $search = substr($v, strpos($v, '=')+1);
                $bing[':search1'] = ['%'.$search.'%','str'];
                $bing[':search2'] = ['%'.$search.'%','str'];
                $where .= '(pic_name like :search1 or pic_info like :search2) ';
            }elseif (strpos($v, 'date=')!==false){
                $date = substr($v, strpos($v, '=')+1);
            }elseif (strpos($v, 'fid=')!==false){
                $where .= ($where=='')?'where ':'and ';
                $fid = substr($v, strpos($v, '=')+1);
                $fidstr = $this->memberFavoriteListID($fid);
                $where .='pic_id in('.$fidstr.')';
            }elseif (strpos($v, 'isshow=')!==false){
                $where .= ($where=='')?'where ':'and ';
                $isshow = substr($v, strpos($v, '=')+1);
                $bing[':isshow'] = [$isshow,'str'];
                $where .= 'pic_isshow=:isshow ';
            }
        }
        $datelist = $this->conn->selectAll('select distinct DATE_FORMAT(pic_ctime,\'%Y-%m\') date from moe_pic p '.$where.'order by pic_ctime desc,pic_id desc', $bing, 2);
        if ($date){
            $where .= ($where=='')?'where ':'and ';
            $bing[':date'] = [$date,'str'];
            $where .= 'DATE_FORMAT(pic_ctime,\'%Y-%m\')=:date ';
        }
        $sqlnum = $this->conn->selectAll('select count(*) from moe_pic p '.$where, $bing, 0);
        $bing[':start'] = [$start,'int'];
        $bing[':num'] = [$num,'int'];
        $sql = 'select pic_id,pic_ssrc,pic_name,pic_isshow,m.m_id,m.m_head,alltimes,allgrade,allgrades from moe_pic p left join moe_member m on p.m_id=m.m_id '.$where.$order.' limit :start,:num';
        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);
        return $this->conn->selectAll($sql, $bing);
    }
    public function picIn($pid)
    {
        $bing[':pid'] = [$pid, 'int'];
        return $this->conn->selectAll('select p.*,m.m_name from moe_pic p left join moe_member m on p.m_id=m.m_id where p.pic_id=:pid', $bing, 1);
    }
    public function picDel($pid){
        $bing[':pid'] = [$pid, 'str'];
        $arr = $this->conn->selectAll('select pic_id,pic_src,pic_bsrc,pic_ssrc from moe_pic where pic_id=:pid', $bing);
        foreach ($arr as $k1 => $v1){
            foreach ($v1 as $k2 => $v2){
                if($k2 != 'pic_id'){
                    if (file_exists($v2)){
                        if(!@unlink($v2)){
                            return '删除图片ID:'.$v1['pic_id'].'中的'.$k2.'出错！';
                        }
                    }
                }
            }
            $bing1[':picid'] = [$v1['pic_id'], 'int'];
            if (!$this->conn->queryChange('delete from moe_favorite where pic_id=:picid', $bing1)){
                return '文件删除完毕，用户收藏图片ID：'.$v1['pic_id'].'中的记录删除出错';
            }
            if (!$this->conn->queryChange('delete from moe_pic where pic_id=:picid', $bing1)){
                return '文件删除完毕，图片ID：'.$v1['pic_id'].'中的记录删除出错';
            }
        }
        return true;
    }
    public function picUpd($pid, $picname, $picinfo, $pictypeid, $pictool)
    {
        $bing = [
            ':pid' => [$pid, 'int'],
            ':picname' => [$picname, 'str'],
            ':picinfo' => [$picinfo, 'str'],
            ':pictypeid' => [$pictypeid, 'str'],
            ':pictool' => [$pictool, 'str']
        ];
        return $this->conn->queryChange('update moe_pic set pic_name=:picname,pic_info=:picinfo,pic_typeid=:pictypeid,pic_tool=:pictool where pic_id=:pid', $bing);
    }
    public function picEnShow($pid)
    {
        $bing[':pid'] = [$pid, 'int'];
        return $this->conn->queryChange('update moe_pic set pic_isshow=\'1\' where pic_id=:pid', $bing);
    }
    public function picDisShow($pid)
    {
        $bing[':pid'] = [$pid, 'int'];
        return $this->conn->queryChange('update moe_pic set pic_isshow=\'0\' where pic_id=:pid', $bing);
    }
    public function picTagList($pid)
    {
        $bing[':pid'] = [$pid, 'int'];
        return $this->conn->selectAll('select tag_id,tag_name from moe_tag where tag_id in(select tag_id from moe_pic_tag where pic_id=:pid)', $bing, 2);
    }
    public function picTagAdd($pid, $tagname)
    {
        $tagid = $this->tag_name_id($tagname);
        if($tagid===false){
            $this->tagAdd($tagname);
            $tagid = $this->tag_name_id($tagname);
        }
        $bing=[
            ':pid' => [$pid, 'int'],
            ':tagid' => [$tagid, 'int'],
        ];
        return $this->conn->queryChange('insert into moe_pic_tag(pic_id,tag_id) values(:pid,:tagid) ', $bing);
    }
    public function picTagDel($pid, $tagid)
    {
        $bing = [
            ':tagid' => [$tagid, 'int'],
            ':pid' => [$pid, 'int']
        ];
        return $this->conn->queryChange('delete from moe_pic_tag where pic_id=:pid and tag_id=:tagid', $bing);
    }
    public function picTypeList()
    {
        return $this->conn->selectAll('select type_id,type_name from moe_type');
    }

    public function typeList(&$pages, $pflag, $pagesNow = 1, $num = 10, $search = null, $order = null)
    {
        if ($search){
            $bing[':search1'] = ['%'.$search.'%', 'str'];
            $bing[':search2'] = ['%'.$search.'%', 'str'];
            $sqlsearch = 'where (type_name like :search1 or type_info like :search2) ';
        }
        if ($order=='num'){
            $sqlorder = 'order by type_num desc,type_id desc ';
        }else{
            $sqlorder = '';
        }
        $sqlnum = $this->conn->selectAll('select count(*) from moe_type '.$sqlsearch, $bing, 0);
        $start = ($pagesNow-1)*$num;
        $bing[':start'] = [$start,'int'];
        $bing[':num'] = [$num,'int'];
        $sql = 'select t.*,count(pic_id) type_num from moe_type t left join moe_pic p on t.type_id=p.pic_typeid '.$sqlsearch.'group by t.type_id '.$sqlorder.' limit :start,:num';
        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);
        return $this->conn->selectAll($sql, $bing);
    }
    public function typeListNum($typeid)
    {
        $bing[':typeid'] = [$typeid, 'int'];
        return $this->conn->selectAll('select count(*) from moe_pic where pic_typeid=:typeid', $bing, 0);
    }
    public function typeUpd($typeid, $typename, $typeinfo = '')
    {
        $bing = [
            ':typeid' => [$typeid, 'int'],
            ':typename' => [$typename, 'str'],
            ':typeinfo' => [$typeinfo, 'str']
        ];
        return $this->conn->queryChange('update moe_type set type_name=:typename,type_info=:typeinfo where type_id=:typeid', $bing);
    }
    public function typeAdd($typename, $typeinfo = '')
    {
        $bing = [
            ':typename' => [$typename, 'str'],
            ':typeinfo' => [$typeinfo, 'str']
        ];
        return $this->conn->queryChange('insert into moe_type(type_name,type_info) value(:typename,:typeinfo)', $bing);
    }
    public function typeDel($typeid)
    {
        $bing = [':typeid' => [$typeid, 'int']];
        return $this->conn->queryChange('delete from moe_type where type_id=:typeid', $bing);
    }

    public function tagList(&$pages, &$datelist, $pflag, $pagesNow = 1, $num = 10, $search = null, $date = null, $order = null)
    {
        $bing = array();
        $sqlwhere = '';
        $sqland = '';
        $sqlsearch = '';
        $sqldate = '';
        $sqlorder = '';
        $sqlflag = true;
        if ($search){
            $sqlwhere = 'where ';
            $sqlsearch = 'tag_name like :tagname ';
            $bing[':tagname'] = ['%'.$search.'%', 'str'];
            $sqlflag = false;
        }
        $datelist = $this->conn->selectAll('select distinct DATE_FORMAT(tag_ctime,\'%Y-%m\') from moe_tag '.$sqlwhere.$sqlsearch, $bing, 2);
        if ($date){
            $sqland = $sqlflag? 'where ' : 'and ';
            $sqldate .= 'DATE_FORMAT(tag_ctime,\'%Y-%m\')=:date ';
            $bing[':date'] = [$date, 'str'];
            $sqlflag = false;
        }
        $pagesmax = $this->conn->selectAll('select count(*) from moe_tag '.$sqlwhere.$sqlsearch.$sqland.$sqldate, $bing, 0);
        $pages = $this->paging($pagesmax, $pflag, $pagesNow, $num);
        if ($order && $order == 'num'){
            $sqlorder = 'order by tag_num desc,tag_id desc ';
        }else{
            $sqlorder = 'order by tag_ctime desc,tag_id desc ';
        }
        $start = ($pagesNow-1)*$num;
        $bing[':start'] = [$start, 'int'];
        $bing[':num'] = [$num, 'int'];
        return $this->conn->selectAll('select t.tag_id,t.tag_name,count(pic_id) tag_num from moe_tag t left join moe_pic_tag pt on t.tag_id=pt.tag_id '.$sqlwhere.$sqlsearch.$sqland.$sqldate.'group by t.tag_id '.$sqlorder.'limit :start,:num', $bing, 2);
    }
    public function tagListNum($tagid)
    {
        $bing[':tagid'] = [$tagid, 'int'];
        return $this->conn->selectAll('select count(*) from moe_pic_tag where tag_id=:tagid', $bing, 0);
    }
    public function tagDel($tagid)
    {
        $bing = [':tagid' => [$tagid, 'int']];
        return $this->conn->queryChange('delete from moe_tag where tag_id=:tagid', $bing);
    }
    public function tagClear($tagid)
    {
        $bing = [':tagid' => [$tagid, 'str']];
        return $this->conn->queryChange('delete from moe_pic_tag where tag_id=:tagid', $bing);
    }
    public function tagAdd($tagname)
    {
        $date = date('Y-m-d H:i:s');
        $bing = [
            ':tagname' => [$tagname, 'str'],
            ':date' => [$date, 'str'],
        ];
        return $this->conn->queryChange('insert into moe_tag(tag_name,tag_ctime) values(:tagname,:date) ', $bing);
    }
    public function tagUpd($tagid, $tagname)
    {
        $bing = [
            ':tagid' => [$tagid, 'int'],
            ':tagname' => [$tagname, 'str'],
        ];
        return $this->conn->queryChange('update moe_tag set tag_name=:tagname where tag_id=:tagid', $bing);
    }
    public function tag_name_id($tagname)
    {
        $bing[':tagname'] = [$tagname, 'str'];
        return $this->conn->selectAll('select tag_id from moe_tag where tag_name=:tagname', $bing, 0);
    }

    public function groupList(&$pages, $pflag, $pagesNow = 1, $num = 10, $search = null, $order = null, $mid = null)
    {
        $where = '';
        $sqlsearch = '';
        $sqlmid = '';
        if ($search){
            if($where==''){
                $where = 'where ';
            }
            $bing[':search1'] = ['%'.$search.'%', 'str'];
            $bing[':search2'] = ['%'.$search.'%', 'str'];
            $sqlsearch = '(group_name like :search1 or group_info like :search2) ';
        }
        if ($mid){
            if($where==''){
                $where = 'where ';
            }
            $midstr = $this->memberGroupListID($mid);
            $sqlmid = 'g.group_id in('.$midstr.') ';
        }
        if ($order=='num'){
            $sqlorder = 'order by group_num desc,group_ctime desc ';
        }else{
            $sqlorder = 'order by group_ctime desc';
        }
        $sqlnum = $this->conn->selectAll('select count(*) from moe_group g '.$where.$sqlmid.$sqlsearch, $bing, 0);
        $start = ($pagesNow-1)*$num;
        $bing[':start'] = [$start,'int'];
        $bing[':num'] = [$num,'int'];
        $sql = 'select g.*,count(m_id) group_num from moe_group g left join moe_group_join j on g.group_id=j.group_id '.$where.$sqlmid.$sqlsearch.' group by g.group_id '.$sqlorder.' limit :start,:num';
        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);
        return $this->conn->selectAll($sql, $bing);
    }
    public function groupListNum($groupid)
    {
        $bing[':groupid'] = [$groupid, 'int'];
        return $this->conn->selectAll('select count(*) from moe_group_join where group_id=:groupid', $bing, 0);
    }
    public function groupJoinListID($groupid)
    {
        $bing[':groupid'] = [$groupid, 'int'];
        $arr =$this->conn->selectAll('select m_id from moe_group_join where group_id=:groupid', $bing, 2);
        $str = '0';
        foreach ($arr as $k=>$v){
            $str .= ','.$v[0];
        }
        return $str;
    }
    public function groupAdd($groupname, $groupinfo = '')
    {
        $date = date('Y-m-d H:i:s');
        $bing = [
            ':groupname' => [$groupname, 'str'],
            ':groupinfo' => [$groupinfo, 'str'],
            ':date' => [$date, 'str'],
        ];
        return $this->conn->queryChange('insert into moe_group(group_name,group_info,group_ctime) value(:groupname,:groupinfo,:date)', $bing);
    }
    public function groupDel($groupid)
    {
        $flag = false;
        $bing[':groupid'] = [$groupid, 'int'];
        if ($this->conn->queryChange('delete from moe_group_join where group_id=:groupid', $bing)){
            $flag = $this->conn->queryChange('delete from moe_group where group_id=:groupid', $bing);
        }
        return $flag;
    }
    public function groupUpd($groupid, $groupname, $groupinfo = '')
    {
        $bing = [
            ':groupid' => [$groupid, 'int'],
            ':groupname' => [$groupname, 'str'],
            ':groupinfo' => [$groupinfo, 'str']
        ];
        return $this->conn->queryChange('update moe_group set group_name=:groupname,group_info=:groupinfo where group_id=:groupid', $bing);
    }

    public function memberList(&$pages, $pflag, $pagesNow = 1, $num = 10, $search = null, $order = null, $fid = null, $groupid = null)
    {
        $where = '';
        $sqlsearch = '';
        $sqlfollow = '';
        if ($search){
            if($where==''){
                $where = 'where ';
            }
            $bing[':search1'] = ['%'.$search.'%', 'str'];
            $bing[':search2'] = ['%'.$search.'%', 'str'];
            $bing[':search3'] = ['%'.$search.'%', 'str'];
            $sqlsearch = '(m_name like :search1 or m_nickname like :search2 or m_info like :search3) ';
        }
        if ($fid){
            if($where==''){
                $where = 'where ';
            }
            $midstr = $this->memberFollowListID($fid);
            $sqlfollow = 'm.m_id in('.$midstr.') ';
        }
        if ($groupid){
            if($where==''){
                $where = 'where ';
            }
            $groupidstr = $this->groupJoinListID($groupid);
            $sqlgroupid = 'm.m_id in('.$groupidstr.') ';
        }
        if ($order=='num'){
            $sqlorder = 'order by m_num desc,m_id desc ';
        }else{
            $sqlorder = 'order by m_ctime desc,m_id desc ';
        }
        $sqlnum = $this->conn->selectAll('select count(*) from moe_member m '.$where.$sqlfollow.$sqlgroupid.$sqlsearch, $bing, 0);
        $start = ($pagesNow-1)*$num;
        $bing[':start'] = [$start,'int'];
        $bing[':num'] = [$num,'int'];
        $sql = 'select m.m_id,m_name,m_nickname,m_email,m_head,count(p.pic_id) m_num from moe_member m left join moe_pic p on m.m_id=p.m_id '.$where.$sqlfollow.$sqlgroupid.$sqlsearch.'group by m.m_id '.$sqlorder.'limit :start,:num';
        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);
        return $this->conn->selectAll($sql, $bing);
    }
    public function memberListNum($mid)
    {
        $bing[':mid'] = [$mid, 'int'];
        return $this->conn->selectAll('select count(*) from moe_pic where m_id=:mid', $bing, 0);
    }
    public function memberIn($mid)
    {
        $bing[':mid'] = [$mid, 'int'];
        return $this->conn->selectAll('select * from moe_member where m_id=:mid', $bing, 1);
    }
    public function memberPic($mid)
    {
        $bing[':mid'] = [$mid, 'int'];
        return $this->conn->selectAll('select pic_id,pic_name,pic_ssrc from moe_pic where m_id=:mid order by pic_ctime desc limit 0,4', $bing, 2);
    }
    public function memberPicNum($mid)
    {
        $bing[':mid'] = [$mid, 'int'];
        return $this->conn->selectAll('select count(*) from moe_pic where m_id=:mid', $bing, 0);
    }
    public function memberFollow($mid)
    {
        $bing[':mid'] = [$mid, 'int'];
        return $this->conn->selectAll('select m.m_id,m.m_nickname,m.m_head from moe_follow f right join moe_member m on f.fm_id=m.m_id where f.m_id=:mid order by f.locktime desc limit 0,8', $bing, 2);
    }
    public function memberFollowListID($mid)
    {
        $bing[':mid'] = [$mid, 'int'];
        $arr = $this->conn->selectAll('select fm_id from moe_follow where m_id=:mid order by locktime desc', $bing, 2);
        $str = '0';
        foreach ($arr as $k=>$v){
            $str .= ','.$v[0];
        }
        return $str;
    }
    public function memberFollowDel($mid,$fmid)
    {
        $bing = [
            ':mid' => [$mid, 'int'],
            ':fmid' => [$fmid, 'int'],
        ];
        return $this->conn->queryChange('delete from moe_follow where m_id=:mid and fm_id=:fmid', $bing);
    }
    public function memberFavorite($mid)
    {
        $bing[':mid'] = [$mid, 'int'];
        return $this->conn->selectAll('select p.pic_id,pic_name,pic_ssrc from moe_favorite f right join moe_pic p on f.pic_id=p.pic_id where f.m_id=:mid order by pic_ctime desc limit 0,4', $bing, 2);
    }
    public function memberFavoriteListID($mid)
    {
        $bing[':mid'] = [$mid, 'int'];
        $arr = $this->conn->selectAll('select pic_id from moe_favorite where m_id=:mid', $bing, 2);
        $str = '0';
        foreach ($arr as $k=>$v){
            $str .= ','.$v[0];
        }
        return $str;
    }
    public function memberFavoriteDel($mid,$pid)
    {
        $bing = [
            ':mid' => [$mid, 'int'],
            ':pid' => [$pid, 'int'],
        ];
        return $this->conn->queryChange('delete from moe_favorite where m_id=:mid and pic_id=:pid', $bing);
    }
    public function memberGroup($mid)
    {
        $bing[':mid'] = [$mid, 'int'];
        return $this->conn->selectAll('select g.group_id,g.group_name from moe_group g left join moe_group_join gj on g.group_id=gj.group_id where m_id=:mid order by g.group_id desc limit 0,6', $bing, 2);
    }
    public function memberGroupListID($mid)
    {
        $bing[':mid'] = [$mid, 'int'];
        $arr = $this->conn->selectAll('select group_id from moe_group_join where m_id=:mid', $bing, 2);
        $str = '0';
        foreach ($arr as $k=>$v){
            $str .= ','.$v[0];
        }
        return $str;
    }
    public function memberGroupDel($mid,$groupid)
    {
        $bing = [
            ':mid' => [$mid, 'int'],
            ':groupid' => [$groupid, 'int'],
        ];
        return $this->conn->queryChange('delete from moe_group_join where m_id=:mid and group_id=:groupid', $bing);
    }
    public function memberAdd($mname,$mnickname,$pass)
    {
        $salt = $this->moe_password_salt();
        $password = $this->moe_password_hash($pass, $salt);
        $mhead = DEFALUT_UPHEAD.'/defalut.png';
        $ctime = date('Y-m-d H:i:s');
        $bing=[
            ':mname' => [$mname, 'str'],
            ':mnickname' => [$mnickname, 'str'],
            ':password' => [$password, 'str'],
            ':salt' => [$salt, 'str'],
            ':mhead' => [$mhead, 'str'],
            ':ctime' => [$ctime, 'str']
        ];
        return $this->conn->queryChange('insert into moe_member(m_name,m_nickname,m_password,m_salt,m_head,m_ctime) values(:mname,:mnickname,:password,:salt,:mhead,:ctime);', $bing);
    }
    public function memberClear($mid)
    {
        $bing[':mid'] = [$mid, 'str'];
        $arr = $this->conn->selectAll('select pic_id from moe_pic where m_id=:mid', $bing);
		$re = '';
        foreach ($arr as $k => $v){
            if(!$re = $this->picDel($v[0])){
				return $re;
			}
        }
		return true;
    }
    public function memberDel($mid)
    {
        $bing[':mid'] = [$mid, 'int'];
        $flag = $this->conn->queryChange('delete from moe_member where m_id=:mid', $bing);
        if($flag){
            $bing[':mid2'] = [$mid, 'int'];
            return $this->conn->queryChange('delete from moe_follow where m_id=:mid or fm_id=:mid2', $bing);
        }else{
            return $flag;
        }
    }
    public function memberUpd($mid, $mname, $mnickname, $mgender, $mbirthday, $mjob, $maddress, $memail, $mqq, $mtool, $mtag, $minfo)
    {
        $bing = [
            ':mid' => [$mid, 'int'],
            ':mname' => [$mname, 'str'],
            ':mnickname' => [$mnickname, 'str'],
            ':mgender' => [$mgender, 'str'],
            ':mbirthday' => [$mbirthday, 'str'],
            ':mjob' => [$mjob, 'str'],
            ':maddress' => [$maddress, 'str'],
            ':memail' => [$memail, 'str'],
            ':mqq' => [$mqq, 'str'],
            ':mtool' => [$mtool, 'str'],
            ':mtag' => [$mtag, 'str'],
            ':mtag' => [$mtag, 'str'],
            ':minfo' => [$minfo, 'str'],
        ];
        return $this->conn->queryChange('update moe_member set m_name=:mname,m_nickname=:mnickname,m_tag=:mtag,m_gender=:mgender,m_address=:maddress,m_birthday=:mbirthday,m_job=:mjob,m_qq=:mqq,m_email=:memail,m_info=:minfo,m_tool=:mtool where m_id=:mid', $bing);
    }
    public function memberUpdPass($mid, $pass){
        $salt = $this->moe_password_salt();
        $password = $this->moe_password_hash($pass, $salt);
        $bing=[
            ':mid' => [$mid, 'int'],
            ':password' => [$password, 'str'],
            ':salt' => [$salt, 'str']
        ];
        return $this->conn->queryChange('update moe_member set m_password=:password,m_salt=:salt where m_id=:mid', $bing);
    }
    public function memberUpdHead($mid,$head)
    {
        $bing=[
            ':mid' => [$mid, 'int'],
            ':head' => [$head, 'str']
        ];
        return $this->conn->queryChange('update moe_member set m_head=:head where m_id=:mid', $bing);
    }
    public function member_name_same($str)
    {
        $bing[':mname'] = [$str, 'str'];
        return $this->conn->selectAll('select count(*) from moe_member where m_name=:mname', $bing, 0);
    }


    public function test()
    {

    }
}
