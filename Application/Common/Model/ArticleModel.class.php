<?php
/**
 * Created by PhpStorm.
 * User: BaAGee
 * Date: 2016/7/23
 * Time: 17:17
 */
namespace Common\Model;
use Think\Model;
class ArticleModel extends Model{
    private $_db='';
    public function __construct(){
        parent::__construct();
        $this->_db=M("article");
    }
    /*插入主表*/
    public function insertArticle($data){
        if(!empty($data)){
            $data['username']=session('aid');
            $data['create_time']=time();
            return $this->_db->add($data);
        }else{
            return false;
        }
    }
    /*计算文章数量*/
    public function getArticleCount($where){
        return $this->_db->where($where)->count();
    }

    /*查询记录*/
    public function getArticles($where,$page='',$pageSize=''){
        if(empty($page) || empty($pageSize)){
            return $this->_db->where($where)->order("listorder desc,article_id desc")->select();
        }else{
            $offset=($page-1)*$pageSize;
            return $this->_db->where($where)->order("listorder desc,article_id desc")->limit($offset,$pageSize)->select();
        }
    }

    /*修改，状态，删除*/
    public function updateStatus($id,$status){
        $data=array('status'=>$status);
        return $this->_db->where('article_id='.$id)->save($data);
    }

    /*排序*/
    public function updateListOrder($article_id,$listorder){
        if(!is_numeric(intval($article_id)) || !is_numeric(intval($listorder))){
            throw_exception("不是有效的menu_id或者lisrorder");
        }else{
            $data=array('listorder'=>$listorder);
            $res=$this->_db->where("article_id=$article_id")->save($data);
        }
        return $res;
    }
    /*获得文章信息*/
    public function getArticleById($where){
        return $this->_db->where($where)->find();
    }
    /*修改文章*/
    public function updateArticle($data){
        if(!empty($data)){
            $data['update_time']=time();
            return $this->_db->save($data);
        }else{
            return false;
        }
    }
    /*推荐位获取信息*/
    public function getArticlesById($ids){
        if(is_array($ids) && !empty($ids)){
            $where=array('article_id'=>array('in',implode(',',$ids)));
            return $this->_db->field("title,listorder,thumb,status,article_id")->where($where)->select();
        }else{
            return false;
        }
    }
    /*获得文章正文*/
    public function selectContent($id){
        return $this->_db->alias('a')->field("a.*,ac.content")->where('a.article_id='.$id)->join("__ARTICLE_CONTENT__ ac on ac.article_id=a.article_id")->find();
    }
    /**/

    /*getMainList*/
    public function getMainList($where ,$page,$pageSize,$five){
        if($five=='' && !empty($page) && !empty($pageSize)){
            $offset=($page-1)*$pageSize;
            return $this->_db->alias('a')->where($where)->field("a.*,ac.content")->join("__ARTICLE_CONTENT__ ac on ac.article_id=a.article_id","LEFT")->order("a.listorder desc,a.article_id desc")->limit($offset,$pageSize)->select();    
        }elseif($five==5){
            return $this->_db->alias('a')->where($where)->field("a.*,ac.content")->join("__ARTICLE_CONTENT__ ac on ac.article_id=a.article_id","LEFT")->order("a.create_time desc")->limit(0,5)->select();
        }elseif($five==6){
            return $this->_db->alias('a')->where($where)->field("a.*,ac.content")->join("__ARTICLE_CONTENT__ ac on ac.article_id=a.article_id","LEFT")->order("a.visits desc")->limit(0,6)->select();
        }else{
            return $this->_db->alias('a')->where($where)->field("a.*,ac.content")->join("__ARTICLE_CONTENT__ ac on ac.article_id=a.article_id","LEFT")->order("a.create_time desc")->select();
        }
    }
    //获取上下文章
    public function getSX($id){
        $prev=$this->_db->where('status <> -1 and article_id<'.$id)->limit(1)->order('article_id desc')->field('article_id,title')->select();
        $next=$this->_db->where('status <> -1 and article_id>'.$id)->limit(1)->field('article_id,title')->select();
        if($prev){
            $prev=$prev[0];
        }
        if($next){
            $next=$next[0];
        }
        return array($prev,$next);
    }
    /*获得每个月的文章*/
    public function getYmAList($having,$page,$pageSize){
        if(!empty($page) && !empty($pageSize)){
            $offset=($page-1)*$pageSize;
            return $this->_db->alias('a')->having($having)->field("a.*,FROM_UNIXTIME(a.create_time,'%Y%m%d') Ym,ac.content")->join("__ARTICLE_CONTENT__ ac on ac.article_id=a.article_id","LEFT")->where('a.status=1')->order("a.listorder desc,a.article_id desc")->limit($offset,$pageSize)->select();
        }
    }

}