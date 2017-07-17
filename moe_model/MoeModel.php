<?php/** * Created by PhpStorm. * User: 78742 * Date: 2017/2/11 0011 * Time: 15:51 */namespace moe\model;use moe\core\imgGD;require_once 'moe_core/Model.php';class MoeModel extends \moe\core\Model{    public function __construct()    {        parent::__construct();    }    public function logging($username, $pass, $salt)    {        $bing[':username'] = [$username,'str'];        $password = $this->moe_password_hash($pass, $salt);        $bing[':password'] = [$password,'str'];        return $this->conn->selectAll('select m_id,m_name,m_nickname,m_head from moe_member where m_name=:username and m_password=:password',$bing,1);    }    public function login_username($username)    {        $bing[':username'] = [$username,'str'];        return $this->conn->selectAll('select m_salt from moe_member where m_name=:username',$bing,0);    }    public function signup($nickname, $username, $pass)    {        $salt = $this->moe_password_salt();        $password = $this->moe_password_hash($pass, $salt);        $mhead = DEFALUT_UPHEAD.'/defalut.png';        $ctime = date('Y-m-d H:i:s');        $bing=[            ':mname' => [$username, 'str'],            ':mnickname' => [$nickname, 'str'],            ':password' => [$password, 'str'],            ':salt' => [$salt, 'str'],            ':mhead' => [$mhead, 'str'],            ':ctime' => [$ctime, 'str']        ];        return $this->conn->queryChange('insert into moe_member(m_name,m_nickname,m_password,m_salt,m_head,m_ctime) values(:mname,:mnickname,:password,:salt,:mhead,:ctime);', $bing);    }    public function indexfollow($mid)    {        $bing[':mid'] = [$mid, 'int'];        return $this->conn->selectAll('select m.m_id,m_nickname,m_head from moe_follow f left join moe_member m on f.fm_id=m.m_id where f.m_id=:mid order by locktime desc limit 0,10',$bing,2);    }    public function indexfollowF($mid)    {        $bing[':mid'] = [$mid, 'int'];        return $this->conn->selectAll('select m.m_id,m_nickname,m_head from moe_follow f left join moe_member m on f.m_id=m.m_id where f.fm_id=:mid order by locktime desc limit 0,10',$bing,2);    }    public function indexpushuser()    {        return $this->conn->selectAll('select m.m_id,m_nickname,m_head from moe_pic p left join moe_member m on p.m_id=m.m_id group by p.m_id order by count(*) desc limit 0,10');    }    public function indexpushgroup()    {        return $this->conn->selectAll('select g.group_id,group_name from moe_group g left join moe_group_join j on g.group_id=j.group_id group by g.group_id order by count(m_id) limit 0,5');    }    public function indexgroup($mid)    {        $bing[':mid'] = [$mid, 'int'];        return $this->conn->selectAll('select g.group_id,group_name from moe_group_join j left join moe_group g on g.group_id=j.group_id where m_id=:mid limit 0,5', $bing);    }    public function indexnotice()    {        return $this->conn->selectAll('select id,title,ctime from moe_notice order by ctime desc limit 0,4');    }    public function indexnewimg()    {        return $this->conn->selectAll('select pic_id,pic_name,pic_ssrc from moe_pic where pic_isshow=\'1\' order by pic_ctime desc limit 0,6');    }    public function indexshareimg()    {        return $this->conn->selectAll('select pic_id,pic_name,pic_ssrc from moe_pic where pic_typeid=4 and pic_isshow=\'1\' order by pic_ctime desc limit 0,6');    }    public function indexfavoriteimg($mid)    {        $bing[':mid'] = [$mid, 'int'];        return $this->conn->selectAll('select pic_id,pic_name,pic_ssrc from moe_pic where pic_id in(select pic_id from moe_favorite where m_id=:mid) and pic_isshow=\'1\' order by pic_ctime desc limit 0,6', $bing);    }    public function indextag()    {        return $this->conn->selectAll('select t.tag_id,t.tag_name from moe_pic_tag p left join moe_tag t on p.tag_id=t.tag_id group by tag_id order by count(pic_id) desc limit 0,30');    }    public function indexrank($type)    {        switch ($type){            case 'times':$order = 'order by alltimes desc';break;            case 'grade':$order = 'order by allgrade desc';break;            case 'grades':$order = 'order by allgrades desc';break;            case 'daytimes':$order = 'order by daytimes desc';break;            case 'daygrade':$order = 'order by daygrade desc';break;            case 'daygrades':$order = 'order by daygrades desc';break;            case 'weektimes':$order = 'order by weektimes desc';break;            case 'weekgrade':$order = 'order by weekgrade desc';break;            case 'weekgrades':$order = 'order by weekgrades desc';break;            default:$order = '';        }        return $this->conn->selectAll('select pic_id,pic_name,pic_ssrc,m.m_id,m_nickname from moe_pic p left join moe_member m on p.m_id=m.m_id where pic_isshow=\'1\' '.$order.' limit 0,3');    }    public function typeAll(){        return $this->conn->selectAll('select type_id,type_name from moe_type');    }    public function type_id_name($tyid){        $bing[':tyid'] = [$tyid, 'int'];        return $this->conn->selectAll('select type_name from moe_type where type_id=:tyid', $bing, 0);    }    public function notice($nid)    {        $bing[':nid'] = [$nid, 'int'];        return $this->conn->selectAll('select * from moe_notice where id=:nid', $bing, 1);    }    public function member_tougao($picname,$picinfo,$pictype,$pictool,$picsrc,$picbsrc,$picssrc,$mid)    {        $ctime = date('Y-m-d h:i:s');        $bing = [            ':pname' => [$picname, 'str'],            ':pinfo' => [$picinfo, 'str'],            ':ptype' => [$pictype, 'int'],            ':ctime' => [$ctime, 'str'],            ':psrc' => [$picsrc, 'str'],            ':pbsrc' => [$picbsrc, 'str'],            ':pssrc' => [$picssrc, 'str'],            ':ptool' => [$pictool, 'str'],            ':mid' => [$mid, 'int']        ];        $flag = $this->conn->queryChange('insert into moe_pic(pic_name,pic_info,pic_typeid,pic_ctime,pic_src,pic_bsrc,pic_ssrc,pic_tool,m_id) values(:pname,:pinfo,:ptype,:ctime,:psrc,:pbsrc,:pssrc,:ptool,:mid)', $bing);        if($flag){            $bing1[':mid'] = [$mid, 'int'];            return $this->conn->selectAll('select pic_id from moe_pic where m_id=:mid order by pic_id desc limit 0,1', $bing1, 0);        }else{            return false;        }    }    public function memberlist(&$pages, $pflag, $pagesNow = 1, $num = 10, $search = null, $gid = null)    {        $where = '';        $bing = array();        if ($search!=null){            $where .= ($where=='')? 'where ' : 'and ';            $bing[':search'] = ['%'.$search.'%', 'str'];            $where .= 'm_nickname like :search ';        }        if($gid != null){            $where .= ($where=='')? 'where ' : 'and ';            $bing[':gid'] = [$gid, 'int'];            $where .= 'm.m_id in(select m_id from moe_group_join where group_id=:gid) ';        }        $sqlnum = $this->conn->selectAll('select count(*) from moe_member m '.$where, $bing, 0);        $start = ($pagesNow-1)*$num;        $bing[':start'] = [$start,'int'];        $bing[':num'] = [$num,'int'];        $sql = 'select m.m_id,m_nickname,m_head,count(pic_id) pic_num,count(f.m_id) fans_num from (moe_member m left join moe_pic p on m.m_id=p.m_id) left join moe_follow f on m.m_id=f.fm_id '.$where.' group by m.m_id limit :start,:num';        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);        return $this->conn->selectAll($sql, $bing);    }    public function mmemberlist(&$pages, $pflag, $pagesNow = 1, $num = 10, $search = null, $gid = null, $order = null,$mid = null, $isF = false)    {        $where = '';        $bing = array();        if ($search!=null){            $where .= ($where=='')? 'where ' : 'and ';            $bing[':search'] = ['%'.$search.'%', 'str'];            $where .= 'm_nickname like :search ';        }        if($gid != null){            $where .= ($where=='')? 'where ' : 'and ';            $bing[':gid'] = [$gid, 'int'];            $where .= 'm.m_id in(select m_id from moe_group_join where group_id=:gid) ';        }        if($mid != null){            $where .= ($where=='')? 'where ' : 'and ';            $bing[':mid'] = [$mid, 'int'];            if($isF){                $where .= 'm.m_id in(select m_id from moe_follow where fm_id=:mid) ';            }else{                $where .= 'm.m_id in(select fm_id from moe_follow where m_id=:mid) ';            }        }        switch ($order){            case 'ctime':$sqlorder = 'order by m.m_ctime desc';break;            case 'imgctime':$sqlorder = 'order by count(pic_id) desc';break;            default:$sqlorder = 'order by m.m_ctime desc,m.m_id desc';        }        $sqlnum = $this->conn->selectAll('select count(*) from moe_member m '.$where, $bing, 0);        $start = ($pagesNow-1)*$num;        $bing[':start'] = [$start,'int'];        $bing[':num'] = [$num,'int'];        $sql = 'select m.m_id,m_nickname,m_head,count(pic_id) pic_num,count(f.m_id) fans_num from (moe_member m left join moe_pic p on m.m_id=p.m_id) left join moe_follow f on m.m_id=f.fm_id '.$where.' group by m.m_id '.$sqlorder.' limit :start,:num';        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);        return $this->conn->selectAll($sql, $bing);    }    public function member_check()    {        if(empty($_GET['mid'])){            return $_SESSION['m_id']?: $this->succeed_href('请先登陆','?v=login');        }else{            return $_GET['mid'];        }    }    public function member_name_same($username)    {        $bing[':username'] = [$username, 'str'];        return $this->conn->selectAll('select count(*) from moe_member where m_name=:username', $bing, 0);    }    public function member_id_nickname($mid)    {        $bing[':mid'] = [$mid, 'int'];        return $this->conn->selectAll('select m_nickname from moe_member where m_id=:mid', $bing, 0);    }    public function member_id_head($mid)    {        $bing[':mid'] = [$mid, 'int'];        return $this->conn->selectAll('select m_head from moe_member where m_id=:mid', $bing, 0);    }    public function member_info($mid)    {        $bing[':mid'] = [$mid, 'int'];        return $this->conn->selectAll('select * from moe_member where m_id=:mid', $bing, 1);    }    public function member_info_some($mid)    {        $bing[':mid'] = [$mid, 'int'];        return $this->conn->selectAll('select m_id,m_nickname,m_head from moe_member where m_id=:mid', $bing, 1);    }    public function member_follow_some($mid, $num, $isfmid = false)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':num'] = [$num, 'int'];        if($isfmid){            return $this->conn->selectAll('select m.m_id,m_nickname,m_head from moe_follow f left join moe_member m on f.m_id=m.m_id where f.fm_id=:mid order by locktime desc limit 0,:num', $bing, 2);        }else{            return $this->conn->selectAll('select m.m_id,m_nickname,m_head from moe_follow f left join moe_member m on f.fm_id=m.m_id where f.m_id=:mid order by locktime desc limit 0,:num', $bing, 2);        }    }    public function member_follow(&$pages, $pflag, $pagesNow = 1, $num = 10, $mid = null, $isfmid = false)    {        $bing[':mid'] = [$mid, 'int'];        if($isfmid){            $sqlnum = $this->conn->selectAll('select count(*) from moe_follow f left join moe_member m on f.m_id=m.m_id where f.fm_id=:mid', $bing, 0);        }else{            $sqlnum = $this->conn->selectAll('select count(*) from moe_follow f left join moe_member m on f.fm_id=m.m_id where f.m_id=:mid', $bing, 0);        }        $start = ($pagesNow-1)*$num;        $bing[':start'] = [$start,'int'];        $bing[':num'] = [$num,'int'];        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);        if($isfmid){            return $this->conn->selectAll('select m.m_id,m_nickname,m_head from moe_follow f left join moe_member m on f.m_id=m.m_id where f.fm_id=:mid order by locktime desc limit :start,:num', $bing, 2);        }else{            return $this->conn->selectAll('select m.m_id,m_nickname,m_head from moe_follow f left join moe_member m on f.fm_id=m.m_id where f.m_id=:mid order by locktime desc limit :start,:num', $bing, 2);        }    }    public function member_follow_add($mid, $fmid)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':fmid'] = [$fmid, 'int'];        return $this->conn->queryChange('insert into moe_follow(m_id,fm_id) values(:mid,:fmid)', $bing);    }    public function member_follow_num($mid, $isFans = false)    {        $bing[':mid'] = [$mid, 'int'];        if($isFans){            return $this->conn->selectAll('select count(*) from moe_follow where fm_id=:mid', $bing, 0);        }else{            return $this->conn->selectAll('select count(*) from moe_follow where m_id=:mid', $bing, 0);        }    }    public function member_follow_is($mid, $fmid)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':fmid'] = [$fmid, 'int'];        return $this->conn->selectAll('select count(*) from moe_follow where m_id=:mid and fm_id=:fmid', $bing, 0);    }    public function member_follow_del($mid, $fmid)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':fmid'] = [$fmid, 'int'];        return $this->conn->queryChange('delete from moe_follow where m_id=:mid and fm_id=:fmid', $bing);    }    public function member_favorite_add($mid, $pid)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':pid'] = [$pid, 'int'];        return $this->conn->queryChange('insert into moe_favorite(m_id,pic_id) values(:mid,:pid)', $bing);    }    public function member_favorite_del($mid, $pid)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':pid'] = [$pid, 'int'];        return $this->conn->queryChange('delete from moe_favorite where m_id=:mid and pic_id=:pid', $bing);    }    public function member_favorite_is($mid, $pid)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':pid'] = [$pid, 'int'];        return $this->conn->selectAll('select count(*) from moe_favorite where m_id=:mid and pic_id=:pid', $bing, 0);    }    public function member_tag_some($mid, $num)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':num'] = [$num, 'int'];        return $this->conn->selectAll('select pt.tag_id,t.tag_name,count(*) pic_num from (moe_pic_tag pt left join moe_pic p on pt.pic_id=p.pic_id) left join moe_tag t on pt.tag_id=t.tag_id where m_id=:mid group by pt.tag_id limit 0,:num', $bing, 2);    }    public function member_taglist($mid)    {        $bing[':mid'] = [$mid, 'int'];        return $this->conn->selectAll('select pt.tag_id,t.tag_name,count(*) pic_num from (moe_pic_tag pt left join moe_pic p on pt.pic_id=p.pic_id) left join moe_tag t on pt.tag_id=t.tag_id where m_id=:mid group by pt.tag_id', $bing, 2);    }    public function member_pic_num($mid, $isF = false)    {        $bing[':mid'] = [$mid, 'int'];        if($isF){            return $this->conn->selectAll('select count(*) from moe_favorite where m_id=:mid', $bing, 0);        }else{            return $this->conn->selectAll('select count(*) from moe_pic where m_id=:mid and pic_isshow=\'1\'', $bing, 0);        }    }    public function member_pic_some($mid, $num, $isF = false)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':num'] = [$num, 'int'];        if($isF){            return $this->conn->selectAll('select pic_id,pic_name,pic_ssrc from moe_pic where pic_id in(select pic_id from moe_favorite where m_id=:mid) and pic_isshow=\'1\' limit 0,:num', $bing, 2);        }else{            return $this->conn->selectAll('select pic_id,pic_name,pic_ssrc from moe_pic where m_id=:mid and pic_isshow=\'1\' limit 0,:num', $bing, 2);        }    }    public function mmember_pic_3($mid)    {        $bing[':mid'] = [$mid, 'int'];        return $this->conn->selectAll('select pic_id,pic_ssrc,m.m_head,m.m_nickname from moe_pic p left join moe_member m on p.m_id=m.m_id where m.m_id=:mid and pic_isshow=\'1\' limit 0,3', $bing, 2);    }    public function member_pic_prev($mid, $pid)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':pid'] = [$pid, 'int'];        return $this->conn->selectAll('select pic_id,pic_name,pic_ssrc from moe_pic where m_id=:mid and pic_isshow=\'1\' and pic_id<:pid order by pic_id desc limit 0,1', $bing, 1);    }    public function member_pic_next($mid, $pid)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':pid'] = [$pid, 'int'];        return $this->conn->selectAll('select pic_id,pic_name,pic_ssrc from moe_pic where m_id=:mid and pic_isshow=\'1\' and pic_id>:pid order by pic_id asc limit 0,1', $bing, 1);    }    public function member_pic_is($mid, $pid)    {        $bing[':mid'] = [$mid, 'str'];        $bing[':pid'] = [$pid, 'str'];        return $this->conn->selectAll('select count(*) from moe_pic where pic_id=:pid and m_id=:mid and pic_isshow=\'1\'', $bing, 0);    }    public function member_pic_del($pid){        $bing[':pid'] = [$pid, 'str'];        $arr = $this->conn->selectAll('select pic_id,pic_src,pic_bsrc,pic_ssrc from moe_pic where pic_id=:pid', $bing);        foreach ($arr as $k1 => $v1){            foreach ($v1 as $k2 => $v2){                if($k2 != 'pic_id'){                    if (file_exists($v2)){                        if(!@unlink($v2)){                            return '删除图片ID:'.$v1['pic_id'].'中的'.$k2.'出错！';                        }                    }                }            }            $bing1[':picid'] = [$v1['pic_id'], 'int'];            if (!$this->conn->queryChange('delete from moe_favorite where pic_id=:picid', $bing1)){                return '文件删除完毕，用户收藏图片ID：'.$v1['pic_id'].'中的记录删除出错';            }            if (!$this->conn->queryChange('delete from moe_pic where pic_id=:picid', $bing1)){                return '文件删除完毕，图片ID：'.$v1['pic_id'].'中的记录删除出错';            }        }        return true;    }    public function member_pic_grade($pid, $mid)    {        $bing[':pid'] = [$pid, 'int'];        $bing[':mid'] = [$mid, 'int'];        $g = $this->conn->selectAll('select grade,gdate from moe_pic_grades where pic_id=:pid and m_id=:mid', $bing, 1);        if(!$g || $g['gdate']!=date('Y-m-d')){            return 0;        }else{            return $g['grade'];        }    }    public function member_pic_grade_add($pid, $mid, $grade)    {        $bing[':pid'] = [$pid, 'int'];        $bing[':mid'] = [$mid, 'int'];        $isgrade = $this->conn->selectAll('select count(*) from moe_pic_grades where pic_id=:pid and m_id=:mid', $bing, 0);        $bing[':grade'] = [$grade, 'int'];        $gdate = date('Y-m-d');        $bing[':gdate'] = [$gdate, 'str'];        if($isgrade){            $flag = $this->conn->queryChange('update moe_pic_grades set grade=:grade,gdate=:gdate where pic_id=:pid and m_id=:mid ', $bing);        }else{            $flag = $this->conn->queryChange('insert into moe_pic_grades(pic_id,m_id,grade,gdate) values(:pid,:mid,:grade,:gdate)', $bing);        }        if($flag){            return $this->pic_grade_add($pid, $grade);        }else{            return false;        }    }    public function memberUpdHead($mid,$head)    {        $bing=[            ':mid' => [$mid, 'int'],            ':head' => [$head, 'str']        ];        return $this->conn->queryChange('update moe_member set m_head=:head where m_id=:mid', $bing);    }    public function memberUpd($mid, $mnickname, $mgender, $mbirthday, $mjob, $maddress, $memail, $mqq, $mtool, $mtag, $minfo)    {        $bing = [            ':mid' => [$mid, 'int'],            ':mnickname' => [$mnickname, 'str'],            ':mgender' => [$mgender, 'str'],            ':mbirthday' => [$mbirthday, 'str'],            ':mjob' => [$mjob, 'str'],            ':maddress' => [$maddress, 'str'],            ':memail' => [$memail, 'str'],            ':mqq' => [$mqq, 'str'],            ':mtool' => [$mtool, 'str'],            ':mtag' => [$mtag, 'str'],            ':mtag' => [$mtag, 'str'],            ':minfo' => [$minfo, 'str'],        ];        return $this->conn->queryChange('update moe_member set m_nickname=:mnickname,m_tag=:mtag,m_gender=:mgender,m_address=:maddress,m_birthday=:mbirthday,m_job=:mjob,m_qq=:mqq,m_email=:memail,m_info=:minfo,m_tool=:mtool where m_id=:mid', $bing);    }    public function piclist(&$pages, $pflag, $pagesNow = 1, $num = 10, $search = null, $order = null, $tid = null, $tyid = null, $mid = null, $isfmid = false)    {        $where = '';        $bing = array();        switch ($order){            case 'alltimes':$sqlorder = 'order by alltimes desc';break;            case 'allgrade':$sqlorder = 'order by allgrade desc';break;            case 'allgrades':$sqlorder = 'order by allgrades desc';break;            case 'daytimes':$sqlorder = 'order by daytimes desc';break;            case 'daygrade':$sqlorder = 'order by daygrade desc';break;            case 'daygrades':$sqlorder = 'order by daygrades desc';break;            case 'weektimes':$sqlorder = 'order by weektimes desc';break;            case 'weekgrade':$sqlorder = 'order by weekgrade desc';break;            case 'weekgrades':$sqlorder = 'order by weekgrades desc';break;            default:$sqlorder = 'order by p.pic_ctime desc,p.pic_id desc';        }        $where = 'where pic_isshow=\'1\' ';        if ($tid!=null){            $where .= ($where=='')? 'where ' : 'and ';            $str = $this->tag_picid($tid);            $where .= 'p.pic_id in('.$str.') ';        }        if ($tyid!=null){            $where .= ($where=='')? 'where ' : 'and ';            $bing[':tyid'] = [$tyid, 'int'];            $where .= 'pic_typeid=:tyid ';        }        if ($search!=null){            $where .= ($where=='')? 'where ' : 'and ';            $bing[':search'] = ['%'.$search.'%', 'str'];            $where .= 'pic_name like :search ';        }        if ($mid!=null){            if($isfmid){                $where .= ($where=='')? 'where ' : 'and ';                $str = $this->favorite_picid($mid);                $where .= 'p.pic_id in('.$str.') ';            }else{                $where .= ($where == '') ? 'where ' : 'and ';                $bing[':mid'] = [$mid, 'int'];                $where .= 'p.m_id=:mid ';            }        }        $sqlnum = $this->conn->selectAll('select count(*) from moe_pic p '.$where, $bing, 0);        $start = ($pagesNow-1)*$num;        $bing[':start'] = [$start,'int'];        $bing[':num'] = [$num,'int'];        $sql = 'select pic_id,pic_ssrc,pic_name,m.m_id,m.m_head,m_nickname,alltimes,allgrade,allgrades from moe_pic p left join moe_member m on p.m_id=m.m_id '.$where.$sqlorder.' limit :start,:num';        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);        return $this->conn->selectAll($sql, $bing);    }    public function mpiclist(&$pages, $pflag, $pagesNow = 1, $num = 10, $search = null, $order = null, $tid = null, $tyid = null, $mid = null, $isfmid = false)    {        $where = '';        $bing = array();        switch ($order){            case 'alltimes':$sqlorder = 'order by alltimes desc';break;            case 'allgrade':$sqlorder = 'order by allgrade desc';break;            case 'allgrades':$sqlorder = 'order by allgrades desc';break;            case 'daytimes':$sqlorder = 'order by daytimes desc';break;            case 'daygrade':$sqlorder = 'order by daygrade desc';break;            case 'daygrades':$sqlorder = 'order by daygrades desc';break;            case 'weektimes':$sqlorder = 'order by weektimes desc';break;            case 'weekgrade':$sqlorder = 'order by weekgrade desc';break;            case 'weekgrades':$sqlorder = 'order by weekgrades desc';break;            default:$sqlorder = 'order by p.pic_ctime desc,p.pic_id desc';        }        if ($tid!=null){            $where .= ($where=='')? 'where ' : 'and ';            $str = $this->tag_picid($tid);            $where .= 'p.pic_id in('.$str.') ';        }        if ($tyid!=null){            $where .= ($where=='')? 'where ' : 'and ';            $bing[':tyid'] = [$tyid, 'int'];            $where .= 'pic_typeid=:tyid ';        }        if ($search!=null){            $where .= ($where=='')? 'where ' : 'and ';            $bing[':search'] = ['%'.$search.'%', 'str'];            $where .= 'pic_name like :search ';        }        if ($mid!=null){            if($isfmid){                $where .= ($where=='')? 'where ' : 'and ';                $str = $this->favorite_picid($mid);                $where .= 'p.pic_id in('.$str.') ';            }else{                $where .= ($where == '') ? 'where ' : 'and ';                $bing[':mid'] = [$mid, 'int'];                $where .= 'p.m_id=:mid ';            }        }        $sqlnum = $this->conn->selectAll('select count(*) from moe_pic p '.$where, $bing, 0);        $start = ($pagesNow-1)*$num;        $bing[':start'] = [$start,'int'];        $bing[':num'] = [$num,'int'];        $sql = 'select pic_id,pic_ssrc,pic_name from moe_pic p left join moe_member m on p.m_id=m.m_id '.$where.$sqlorder.' limit :start,:num';        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);        return $this->conn->selectAll($sql, $bing);    }    public function picinfo($pid)    {        $bing[':pid'] = [$pid, 'int'];        return $this->conn->selectAll('select * from moe_pic where pic_id=:pid and pic_isshow=\'1\'', $bing, 1);    }    public function pic_order($o)    {        $str = '';        switch ($o){            case 'alltimes':$str = '总点击排序';break;            case 'allgrade':$str = '总评分排序';break;            case 'allgrades':$str = '总评次排序';break;            case 'daytimes':$str = '日点击排序';break;            case 'daygrade':$str = '日评分排序';break;            case 'daygrades':$str = '日评次排序';break;            case 'weektimes':$str = '月点击排序';break;            case 'weekgrade':$str = '月评分排序';break;            case 'weekgrades':$str = '月评次排序';break;        }        return $str;    }    public function pic_times_add($data)    {        $wlast = strtotime(date("Y-m-d", time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));        $dlast = strtotime(date("Y-m-d"));        $weeklast = strtotime($data['weeklast']);        $daylast = strtotime($data['daylast']);        $wflag = ($wlast - $weeklast >= 3600*24*7);        $dflag = ($dlast - $daylast >= 3600*24);        $bing[':alltimes'] = [$data['alltimes']+1, 'int'];        $setsql = 'alltimes=:alltimes';        if($wflag){            $bing[':weeklast'] = [date('Y-m-d',$wlast), 'str'];            $setsql .= ',weeklast=:weeklast';            $bing[':weektimes'] = [1, 'int'];        }else{            $bing[':weektimes'] = [$data['weektimes']+1, 'int'];        }        $setsql .= ',weektimes=:weektimes';        if($dflag){            $bing[':daylast'] = [date('Y-m-d',$dlast), 'str'];            $setsql .= ',daylast=:daylast';            $bing[':daytimes'] = [1, 'int'];        }else{            $bing[':daytimes'] = [$data['daytimes']+1, 'int'];        }        $setsql .= ',daytimes=:daytimes';        return $this->conn->queryChange('update moe_pic set '.$setsql.' where pic_id='.$data['pic_id'],$bing);    }    public function pic_grade_add($pid, $grade)    {        $bing[':pid'] = [$pid, 'int'];        $data = $this->conn->selectAll('select pic_id,weekgrade,weekgrades,weeklast,daygrade,daygrades,daylast,allgrade,allgrades from moe_pic where pic_id=:pid', $bing, 1);        $wlast = strtotime(date("Y-m-d", time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));        $dlast = strtotime(date("Y-m-d"));        $weeklast = strtotime($data['weeklast']);        $daylast = strtotime($data['daylast']);        $wflag = ($wlast - $weeklast >= 3600*24*7);        $dflag = ($dlast - $daylast >= 3600*24);        $allgrade = $data['allgrade']+$grade;        $allgrades = $data['allgrades']+1;        $bing[':allgrade'] = [$allgrade, 'int'];        $bing[':allgrades'] = [$allgrade, 'int'];        $setsql = 'allgrade=:allgrade,allgrades=:allgrades';        if($wflag){            $bing[':weeklast'] = [date('Y-m-d',$wlast), 'str'];            $setsql .= 'weeklast=:weeklast';            $weekgrade = $grade;            $weekgrades = 1;            $bing[':weekgrade'] = [$weekgrade, 'int'];            $bing[':weekgrades'] = [$weekgrades, 'int'];        }else{            $weekgrade = $data['weekgrade']+$grade;            $weekgrades = $data['weekgrades']+1;            $bing[':weekgrade'] = [$weekgrade, 'int'];            $bing[':weekgrades'] = [$weekgrades, 'int'];        }        $setsql .= ',weekgrade=:weekgrade,weekgrades=:weekgrades';        if($dflag){            $bing[':daylast'] = [date('Y-m-d',$dlast), 'str'];            $setsql .= ',daylast=:daylast';            $daygrade = $grade;            $daygrades = 1;            $bing[':daygrade'] = [$daygrade, 'int'];            $bing[':daygrades'] = [$daygrades, 'int'];        }else{            $daygrade = $data['daygrade']+$grade;            $daygrades = $data['daygrades']+1;            $bing[':daygrade'] = [$daygrade, 'int'];            $bing[':daygrades'] = [$daygrades, 'int'];        }        $setsql .= ',daygrade=:daygrade,daygrades=:daygrades';        return $this->conn->queryChange('update moe_pic set '.$setsql.' where pic_id=:pid', $bing);    }    public function pic_taglist($pid)    {        $bing[':pid'] = [$pid, 'int'];        return $this->conn->selectAll('select tag_id,tag_name from moe_tag where tag_id in(select tag_id from moe_pic_tag where pic_id=:pid)', $bing);    }    public function pic_tag_num($pid)    {        $bing[':pid'] = [$pid, 'int'];        return $this->conn->selectAll('select count(*) from moe_pic_tag where pic_id=:pid', $bing, 0);    }    public function pic_tag_isexist($pid, $tid)    {        $bing[':pid'] = [$pid, 'int'];        $bing[':tid'] = [$tid, 'int'];        return $this->conn->selectAll('select count(*) from moe_pic_tag where tag_id=:tid and pic_id=:pid', $bing, 0);    }    public function pic_tag_add($pid, $tname)    {        $tid = $this->tag_is_same($tname);        if ($tid === false) {            $date = date('Y-m-d h:i:s');            $bing1[':tname'] = [$tname, 'str'];            $bing1[':cdate'] = [$date, 'str'];            $this->conn->queryChange('insert into moe_tag(tag_name,tag_ctime) values(:tname,:cdate)',$bing1);            $tid = $this->tag_is_same($tname);            $bing2[':tid'] = [$tid, 'int'];            $bing2[':pid'] = [$pid, 'int'];            return $this->conn->queryChange('insert into moe_pic_tag(pic_id,tag_id) values(:pid,:tid)',$bing2);        } else {            if($this->pic_tag_isexist($pid, $tid) > 0){ $this->error_back('请勿重复添加标签');}            $bing[':tid'] = [$tid, 'int'];            $bing[':pid'] = [$pid, 'int'];            return $this->conn->queryChange('insert into moe_pic_tag(pic_id,tag_id) values(:pid,:tid)',$bing);        }    }    public function pic_tag_addarr($pid, $pictag)    {        $pictag = str_replace('，',',',$pictag);        $tagarr = explode(',',$pictag);        $restr = '';        for ($i=0;$i<10;$i++){            $tagname = trim($tagarr[$i]);            if($tagname!=''){                if(!($this->pic_tag_add($pid, $tagname))){                    $restr .= '-'.$tagname.'-';                }            }        }        return $restr;    }    public function tag_list(&$pages, $pflag, $pagesNow = 1, $num = 10, $search = null, $order = null)    {        $where = '';        $bing = array();        if ($search!=null){            $bing[':search'] = ['%'.$search.'%', 'str'];            $where = 'where tag_name like :search ';        }        switch ($order){            case 'ctime':$sqlorder = 'order by t.tag_ctime desc ';break;            case 'num':$sqlorder = 'order by count(pic_id) desc ';break;            default:$sqlorder = 'order by t.tag_ctime desc,t.tag_id desc ';        }        $sqlnum = $this->conn->selectAll('select count(*) from moe_tag m '.$where, $bing, 0);        $start = ($pagesNow-1)*$num;        $bing[':start'] = [$start,'int'];        $bing[':num'] = [$num,'int'];        $sql = 'select t.tag_id,t.tag_name,count(pic_id) pic_num from moe_tag t left join moe_pic_tag p on t.tag_id=p.tag_id group by t.tag_id '.$sqlorder.' limit :start,:num';        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);        return $this->conn->selectAll($sql, $bing);    }    public function tag_picid($tid)    {        $bing[':tid'] = [$tid, 'int'];        $arr = $this->conn->selectAll('select pic_id from moe_pic_tag where tag_id=:tid', $bing);        $str = '0';        foreach ($arr as $k=>$v){            $str .= ','.$v[0];        }        return $str;    }    public function tag_is_same($tname)    {        $bing[':tname'] = [$tname, 'int'];        return $this->conn->selectAll('select tag_id from moe_tag where tag_name=:tname', $bing, 0);    }    public function tag_id_name($tid)    {        $bing[':tid'] = [$tid, 'int'];        return $this->conn->selectAll('select tag_name from moe_tag where tag_id=:tid', $bing, 0);    }    public function favorite_picid($mid)    {        $bing[':mid'] = [$mid, 'int'];        $arr = $this->conn->selectAll('select pic_id from moe_favorite where m_id=:mid', $bing);        $str = '0';        foreach ($arr as $k=>$v){            $str .= ','.$v[0];        }        return $str;    }    public function grouplist(&$pages, $pflag, $pagesNow = 1, $num = 10, $search = null, $order = null)    {        $where = '';        $bing = array();        if ($search!=null){            $bing[':search'] = ['%'.$search.'%', 'str'];            $where .= 'where group_name like :search ';        }        switch ($order){            case 'ctime':$sqlorder = 'order by g.group_ctime desc ';break;            case 'num':$sqlorder = 'order by count(m_id) desc ';break;            default:$sqlorder = 'order by g.group_ctime desc,g.group_id desc ';        }        $sqlnum = $this->conn->selectAll('select count(*) from moe_group g '.$where, $bing, 0);        $start = ($pagesNow-1)*$num;        $bing[':start'] = [$start,'int'];        $bing[':num'] = [$num,'int'];        $sql = 'select g.group_id,group_name,group_info,group_ctime,count(m_id) member_num from moe_group g left join moe_group_join j on g.group_id=j.group_id '.$where.' group by g.group_id '.$sqlorder.' limit :start,:num';        $pages = $this->paging($sqlnum, $pflag, $pagesNow, $num);        return $this->conn->selectAll($sql, $bing);    }    public function groupinfo($gid)    {        $bing[':gid'] = [$gid, 'int'];        return $this->conn->selectAll('select g.*,count(m_id) member_num from moe_group g left join moe_group_join j on g.group_id=j.group_id where g.group_id=:gid', $bing, 1);    }    public function group_member_some($gid)    {        $bing[':gid'] = [$gid, 'int'];        return $this->conn->selectAll('select j.m_id,join_time,m.m_nickname,m.m_head from moe_group_join j left join moe_member m on j.m_id=m.m_id where group_id=:gid order by join_time desc limit 0,10', $bing);    }    public function group_isjoin($gid, $mid)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':gid'] = [$gid, 'int'];        return $this->conn->selectAll('select count(*) from moe_group_join where group_id=:gid and m_id=:mid', $bing, 0);    }    public function group_add($mid, $gid)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':gid'] = [$gid, 'int'];        $gdate = date('Y-m-d h:i:s');        $bing[':gdate'] = [$gdate, 'str'];        return $this->conn->queryChange('insert into moe_group_join(group_id,m_id,join_time) values(:gid,:mid,:gdate)', $bing);    }    public function group_del($mid, $gid)    {        $bing[':mid'] = [$mid, 'int'];        $bing[':gid'] = [$gid, 'int'];        return $this->conn->queryChange('delete from moe_group_join where group_id=:gid and m_id=:mid', $bing);    }}