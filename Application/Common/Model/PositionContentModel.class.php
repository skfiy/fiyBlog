<?php
/**
 * Created by PhpStorm.
 * User: BaAGee
 * Date: 2016/7/24
 * Time: 17:33
 */
namespace Common\Model;
use Think\Model;
class PositionContentModel extends Model{
    private $_db='';
    public function __construct(){
        parent::__construct();
        $this->_db=M("position_content");
    }

    /*插入*/
    public function insert($data){
        if(!empty($data) && is_array($data)){
            return $this->_db->add($data);
        }else{
            return false;
        }
    }
    /*获取数量*/
    public function getPoConCount($data){
        if(is_array($data) && !empty($data)){
            return $this->_db->alias('pc')->where($data)->count();
        }else{
            return '';
        }
    }
    /*分页获取*/
    public function getPosCons($where,$page,$pageSize){
        if(empty($pageSize) && empty($page)){
            return $this->_db->alias('pc')->field('pc.*,p.name')->where($where)->join('__POSITION__ p on p.id=pc.position_id','LEFT')->order("listorder desc,id desc")->select();
        }else{
            $offset=($page-1)*$pageSize;
            $lists=$this->_db->alias('pc')->field('pc.*,p.name')->where($where)->join('__POSITION__ p on p.id=pc.position_id','LEFT')->order("listorder desc,id desc")->limit($offset,$pageSize)->select();
            return $lists;
        }
    }
    /*getArticleList*/
    public function getArticleList($where,$page,$pageSize){
        $offset=($page-1)*$pageSize;
        $lists=$this->_db->alias('pc')->field('a.*')->where($where)->join("__ARTICLE__ a on a.article_id=pc.article_id","LEFT")->order("listorder desc,id desc")->limit($offset,$pageSize)->select();
        return $lists;
    }
    /*获取所有*/
    public function getPosContents(){
        $where=array('pc.status'=>array('neq',-1));
        return $this->_db->alias('pc')->field('pc.*,p.name')->where($where)->join('__POSITION__ p on p.id=pc.position_id','LEFT')->select();
    }
    /*删除*/
    public function updateStatus($id,$status){
        $data=array('status'=>$status);
        $res=$this->_db->where("id=$id")->save($data);
        return $res;
    }
    /*排序*/
    public function updateListOrder($id,$listorder){
        if(!is_numeric(intval($id)) || !is_numeric(intval($listorder))){
            throw_exception("不是有效的menu_id或者lisrorder");
        }else{
            $data=array('listorder'=>$listorder);
            $res=$this->_db->where("id=$id")->save($data);
        }
        return $res;
    }
    /*根据ID获取*/
    public function getPosConById($id){
        if(!empty($id)){
            return $this->_db->where("id=$id")->find();
        }else{
            return '';
        }
    }
    /*修改*/
    public function updatePosCon($data){
        return $this->_db->save($data);
    }
    /*首页文章查询*/
    public function select($where,$limit=0){
        if(!empty($where)){
            if(!$limit){
                return $this->_db->alias('pc')->field("a.title,a.title_font_color,a.banner,a.article_id,a.original,a.create_time,a.thumb,a.description")->where($where)->join("__ARTICLE__ a on a.article_id=pc.article_id","LEFT")->order("pc.listorder desc,pc.id desc")->select();    
            }else{
                return $this->_db->alias('pc')->field("a.title,a.title_font_color,a.banner,a.article_id,a.original,a.create_time,a.thumb,a.description")->where($where)->join("__ARTICLE__ a on a.article_id=pc.article_id","LEFT")->order("pc.listorder desc,pc.id desc")->limit($limit)->select();
            }
        }
    }
    /*首页主要列表文章*/
    public function getMainList($where,$page,$pageSize){
        $offset=($page-1)*$pageSize;
        $lists=$this->_db->alias('pc')->field('ac.content,a.catid,a.article_id,a.title,a.create_time,a.original')
            ->where($where)
            ->join("__ARTICLE_CONTENT__ ac on ac.article_id=pc.article_id","LEFT")
            ->join("__ARTICLE__ a on a.article_id=pc.article_id","LEFT")
            ->order("pc.listorder desc,pc.id desc")->limit($offset,$pageSize)->select();
        return $lists;
    }
    

}