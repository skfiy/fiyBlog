<?php

function show($status,$message,$data=array()){
    $info=array(
        'status'=>$status,
        'message'=>$message,
        'data'=>$data
    );
    return $info;
}

function password($str){
    return md5(md5($str).substr($str, 0,2));
}

function getCoptFrom($id){
    return C('COPY_FROM')[$id] ? C('COPY_FROM')[$id] : "";
}

function isThumb($thumb){
    if($thumb){
        return "<span style='color:green'>有</span>";
    }else{
        return "无";
    }
}

function getMenuName($menus,$catid){
    foreach($menus as $menu){
        $navList[$menu['menu_id']]=$menu['name'];
    }
    return isset($navList[$catid])?$navList[$catid]:'';
}
// mb_substr=0,15,'utf-8'
function specialsub($str){
    return mb_substr(strip_tags(html_entity_decode($str)),0,100,'utf-8');
}

function getMenu($catid){
    return M('menu')->where('menu_id='.$catid)->field('name')->find()['name'];
}
function deleteimg($img){
    foreach ($img as $key => $value) {
        unlink('./Upload/'.$value);
    }   
}
function getParent($id){
    return M('menu')->field('name')->find($id)['name'];
}
//获取qq登陆者信息
function get_user_info(){
    $get_user_info = "https://graph.qq.com/user/get_user_info?"
        . "access_token=" . $_SESSION['access_token']
        . "&oauth_consumer_key=" . $_SESSION["appid"]
        . "&openid=" . $_SESSION["openid"]
        . "&format=json";

    $info = file_get_contents($get_user_info);
    $arr = json_decode($info, true);

    return $arr;
}
function GetBrowser(){
    $br = $_SERVER['HTTP_USER_AGENT'];
    if(!empty($br)){
        if (preg_match('/MSIE/i',$br)) {
            $br = 'MSIE';
        }elseif (preg_match('/Firefox/i',$br)) {
            $br = 'Firefox';
        }elseif (preg_match('/Chrome/i',$br)) {
            $br = 'Chrome';
        }elseif (preg_match('/Safari/i',$br)) {
            $br = 'Safari';
        }elseif (preg_match('/Opera/i',$br)) {
            $br = 'Opera';
        }else {
            $br = 'Other';
        }
        return $br;
    }else{
        return "获取浏览器信息失败！";
    } 
}

function GetOs(){
    $OS = $_SERVER['HTTP_USER_AGENT'];
    if(!empty($OS)){
        if(preg_match('/win/i',$OS) && preg_match('/nt 5.1/i', $OS)){
            $OS = 'Windows XP';
        }elseif(preg_match('/win/i',$OS) && preg_match('/nt 5.2/i', $OS)){
            $OS = 'Windows2003';
        }elseif(preg_match('/win/i',$OS) && preg_match('/nt 5/i', $OS)){
            $OS = 'Windows2000';
        }elseif(preg_match('/win/i',$OS) && preg_match('/nt 6.1/i', $OS)){
            $OS = 'Windows7';
        }elseif(preg_match('/win/i',$OS) && preg_match('/nt 10.0/i', $OS)){
            $OS = 'Windows10';
        }elseif(preg_match('/win/i',$OS) && preg_match('/nt 6.3/i', $OS)){
            $OS = 'Windows8.1';
        }elseif (preg_match('/android/i',$OS)) {
            $OS = 'Android';
        }elseif (preg_match('/mac/i',$OS)) {
            $OS = 'MAC';
        }elseif (preg_match('/linux/i',$OS)) {
            $OS = 'Linux';
        }elseif (preg_match('/unix/i',$OS)) {
            $OS = 'Unix';
        }elseif (preg_match('/bsd/i',$OS)) {
            $OS = 'BSD';
        }else {
            $OS = 'Other';
        }
        return $OS;
    }else{
        return "获取访客操作系统信息失败！";
    }   
}

function sendMail($email,$title,$content){
    vendor('PHPMailer.class#phpmailer');
    $mail = new PHPMailer();
    // 装配邮件服务器
    if (C('MAIL_SMTP')) {
        $mail->IsSMTP();
    }
    
    $mail->Host = C('MAIL_SMTP')['MAIL_HOST'];
    $mail->SMTPAuth = C('MAIL_SMTP')['MAIL_SMTPAUTH'];
    $mail->Username = C('MAIL_SMTP')['MAIL_USERNAME'];
    $mail->Password = C('MAIL_SMTP')['MAIL_PASSWORD'];
    $mail->CharSet = C('MAIL_SMTP')['MAIL_CHARSET'];

    // 装配邮件头信息
    $mail->From = C('MAIL_SMTP')['MAIL_FROM'];
    $mail->AddAddress($email);
    $mail->IsHTML(C('MAIL_SMTP')['MAIL_ISHTML']);
    $mail->FromName = C('MAIL_SMTP')['MAIL_FROMNAME'];
    // 装配邮件正文信息
    $mail->Subject = $title;
    $mail->Body = $content;
    // 发送邮件
    if (!$mail->Send()) {
        return $mail->ErrorInfo;//FALSE;
    } else {
        return TRUE;
    }
}