<?php
/**
 * Created by PhpStorm.
 * User: BaAGee
 * Date: 2016/7/23
 * Time: 17:25
 */
namespace Common\Model;
use Think\Model;
class ArticleContentModel extends Model{
    private $_db='';
    public function __construct(){
        parent::__construct();
        $this->_db=M('article_content');
    }
    /*插入文章内容*/
    public function insertContent($data,$id){
        if(!empty($data) && !empty($id)){
            $datas=array('article_id'=>$id,'content'=>$data,'create_time'=>time());
            return $this->_db->add($datas);
        }else{
            return false;
        }
    }
    /*获取文章内容*/
    public function getContentById($data){
        return $this->_db->where($data)->field('content')->find();   
    }
    /*更新文章内容*/
    public function updateContent($data,$id){
        if(!empty($data) && !empty($id)){
            $datas=array('content'=>$data,'update_time'=>time());
            return $this->_db->where("article_id=$id")->save($datas);
        }else{
            return false;
        }   
    }
    
}