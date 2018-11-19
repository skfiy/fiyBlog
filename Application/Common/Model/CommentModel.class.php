<?php
namespace Common\Model;
use Think\Model;
class CommentModel extends Model{
	protected $insertFields='comment,email,article_id,to';
	private $_db='';
	public function __construct(){
		 parent::__construct();
        $this->_db=M("comment");
	}
	/*添加评论*/
	public function addComment($data){
		return $this->_db->add($data);
	}
	public function getCommentByArticle($id){
		if(!empty($id)){
			$commons=$this->_db->alias('c')->field('c.*,v.nickname,v.figureurl')
			->join('__VISITORS__ v on v.openid=c.openid','LEFT')
			->where(array("c.article_id"=>$id,'c.status'=>1))->order("c.time desc")->limit(0,10)->select();
			if(!empty($commons)){
				foreach ($commons as $key => $value) {
					if($value['to']!=-1){
						$info1=$this->where(array('id'=>$value['to']))->field('time,openid,comment')->find();
						$openid=$info1['openid'];
						$info2=M('Visitors')->where('openid="'.$openid.'"')->find();
						$commons[$key]['to']=$info2['nickname'];
						$commons[$key]['tofig']=$info2['figureurl'];
						$commons[$key]['tocom']=$info1['comment'];
						$commons[$key]['totim']=$info1['time'];
					}else{
						$commons[$key]['to']='';
					}
				}
				return $commons;
			}
		}else{
			return false;
		}
	}
	/*获取所有评论*/
	public function getAllComment($data,$page,$pageSize){
		$offset=($page-1)*$pageSize;
            $lists=$this->_db->alias('c')->field("c.*,a.title,v.figureurl,v.nickname")
            ->where($data)
            ->join("__VISITORS__ v on v.openid=c.openid",'LEFT')
            ->join("__ARTICLE__ a on a.article_id=c.article_id","LEFT")
            ->order("c.time desc")
            ->limit($offset,$pageSize)
            ->select();
            return $lists;
	}
	/*获得评论数据条数*/
    public function getCommCount($data=array()){
        $data['status']=array('neq',-1);
        $count=$this->_db->where($data)->count();
        return $count;
    }
    /*删除pinglun*/
    public function deleteComm($id){
    	return $this->_db->save(array('id'=>$id,'status'=>-1));
    }
}