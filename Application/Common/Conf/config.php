<?php
return array(
	//'配置项'=>'配置值'
	//显示页面跟踪信息
    // 'SHOW_PAGE_TRACE'=>true,
    'DEFAULT_CHARSET'       =>  'utf-8', // 默认输出编码
    //默认分组设置
    'DEFAULT_MODULE'        =>  'Home',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称
    'MODULE_ALLOW_LIST'     =>  array('Home','Admin'),   //可供访问的分组
    'URL_CASE_INSENSITIVE'  =>  true, 	//url不区分大小写
    //数据库配置
    'DB_TYPE'               =>  'mysqli',
    'DB_HOST'               =>  '127.0.0.1',
    'DB_NAME'               =>  'baagee_cms',
    'DB_USER'               =>  'root',
    'DB_PWD'                =>  '',
    'DB_PREFIX'             =>  'cms_',
    'HTML_FILE_SUFFIX'=>'.html',
    'URL_MODEL'=>'2',

    'TITLE_FONT_COLOR'=>array(
            '#5674ed'=>'#5674ed',
            '#ed568b'=>'#ed568b',
            '#5261AC'=>'#5261AC',
            '#F78F1D'=>'#F78F1D',
            '#EC098D'=>'#EC098D',
            '#8CC63F'=>'#8CC63F',
            '#A53FC6'=>'#A53FC6',
        ),
    'COPY_FROM'=>array(
            1=>'本站',
            // 2=>'新浪',
            // 3=>'网易',
            // 4=>'央视',
            // 5=>'搜狐',
        ),
    // 'UPLOAD'=>'./upload/',
    // 配置邮件发送服务器
    'MAIL_SMTP'=>array(
	    'MAIL_HOST' =>'',//smtp服务器的名称
	    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
	    'MAIL_USERNAME' =>'',//你的邮箱名
	    'MAIL_FROM' =>'',//发件人地址
	    'MAIL_FROMNAME'=>'',//发件人姓名
	    'MAIL_PASSWORD' =>'',//邮箱密码
	    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
	    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
    ),

    'LOG_RECORD' => true, // 开启日志记录
    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR,WARN', // 只记录EMERG ALERT CRIT ERR WARN,NOTICE,INFO,DEBUG错误
    'LOG_TYPE'              =>  'File', // 日志记录类型 默认为文件方式

    /*错误页面*/
    'ERROR_PAGE' =>'/Public/error.html'
);