<?php
namespace Common\Model;
use Think\Model;
class BigimgModel extends Model{
	private $_db='';
	public function __construct(){
		 parent::__construct();
        $this->_db=M("bigimg");
	}

	/*保存*/
	public function insertimg($data){
		return $this->_db->add($data);
	}
	/*查询*/
	public function getimg(){
		return $this->_db->select();
	}
	protected function _before_delete($option){
		$id=$option['where']['id'];
		$img=$this->_db->field('bigimg')->find($id);
		deleteimg($img);
	}
}