<?php
/**
 * 文件上传类
 */
namespace Admin\Controller;

class UploadController extends BaseController
{

    public function index()
    {
        $resdata = array();
        $thumb=I('post.thumb');
        $avatar=I('post.avatar')?I('post.avatar'):0;
        $bigimg=I('post.bigimg')?I('post.bigimg'):0;
        if($avatar){
            $str='image/avatar/';
        }else{
            $str='image/article/';
        }
        if($bigimg){
            $str='image/bigimg/';
        }

        $cfg=array(
            'rootPath'=>'./Upload/',
            'maxSize'=>3145728,
            'exts'=>array(
                    'jpg',
                    'gif',
                    'png',
                    'jpeg',
                    'JPG'
                ),
            'savePath'=>$str,//.date('Y').'/'.date('m').'/'.date('d').'/',
        );
        $upload = new \Think\Upload($cfg);
        $info = $upload->uploadOne($_FILES['Filedata']);
        if (! $info) {
            $resdata['code'] = 1;
            $resdata['msg'] = $upload->getError();
        } else {
            $resdata['code'] = 0;
            $resdata['imgurl'] = $info['savepath'] . $info['savename'];
            if($thumb){
                $img=new \Think\Image();
                $Original="./Upload/".$resdata['imgurl'];
                
                //对已经上传的图片做缩略图
                $img->open($Original);
                $thumbnail="./Upload/".$info['savepath'] ."thumb_". $info['savename'];
                $img->thumb(83,99,6);//宽高，默认强制，不是宽高比制作，缩放
                $img->save($thumbnail);
                $resdata['thumb'] = $info['savepath'] ."thumb_".  $info['savename'];
                

                //对已经上传的图片做大banner图
                $img->open($Original);
                $mdthumbnail="./Upload/".$info['savepath'] ."mdthumb_". $info['savename'];
                $imgwidth=$img->width();
                $imgheight=$img->height();
                $img->crop($imgwidth,$imgheight,0,0,460,330);//裁剪
                $img->save($mdthumbnail);
                $resdata['mdthumb'] = $info['savepath'] ."mdthumb_".  $info['savename'];
            }
        }
        $this->ajaxReturn($resdata,'XML');
    }
}