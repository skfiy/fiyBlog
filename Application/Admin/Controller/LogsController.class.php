<?php
namespace Admin\Controller;
class LogsController extends BaseController {
	function index(){
		/*获取日志文件*/
		$logPath=RUNTIME_PATH.'Logs/';
    	if ( $handle  =  opendir ($logPath)) {
		     /*遍历目录 */
		     $logFiles=array();
		    while ( false  !== ( $file  =  readdir ( $handle ))) {
		     	if($file!='.' && $file!='..'){
		        	$logFiles[]=$file;
		     	}
		    }
		    closedir ( $handle );
		}
		// 根据值，以降序对关联数组进行排序
		arsort($logFiles);
		// var_dump($logFiles);
		foreach ($logFiles as $value) {
			$logFile[]=$value;
		}
		// 保留近9天的数据
		foreach ($logFile as $key => $value) {
			if($key<=8){
				$lognames[]=$value;
			}else{
				unlink($logPath.$value);
			}
		}
		$logname=I('get.logname');
		if(!empty($logname)){
			$logs=file_get_contents($logPath.$logname);
		}else{
			$logs=file_get_contents($logPath.date('y_m_d').'.log');
		}
		$this->logFiles=$lognames;
		$this->logs=$logs;
		$this->display();
	}
}