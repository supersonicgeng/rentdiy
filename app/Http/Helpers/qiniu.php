<?php
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

function qiniu_Upload($filePath)
{

// 需要填写你的 Access Key 和 Secret Key
    $accessKey = "ddPV25r_12dGquZBBceIKCWfV-qFWPg0Y2He1bPd";
    $secretKey = "D9LkmbrpSbmOYBgvGo-R_jjgxujhhc_bnqx5UgqH";
    $bucket = "jhm1";
// 构建鉴权对象
    $auth = new Auth($accessKey, $secretKey);
// 生成上传 Token
    $token = $auth->uploadToken($bucket);
// 要上传文件的本地路径
//$filePath = './php-logo.png';
// 上传到七牛后保存的文件名
    $key = basename($filePath);
// 初始化 UploadManager 对象并进行文件的上传。
    $uploadMgr = new UploadManager();
// 调用 UploadManager 的 putFile 方法进行文件的上传。
    $uploadMgr->putFile($token, $key, $filePath);
    unlink($filePath);

}

/***
 * 删除七牛云图片
 * @param $name
 * @return bool
 */
function qiniu_delete($name)
{
    $accessKey = "ddPV25r_12dGquZBBceIKCWfV-qFWPg0Y2He1bPd";
    $secretKey = "D9LkmbrpSbmOYBgvGo-R_jjgxujhhc_bnqx5UgqH";
    $bucket = "jhm1";
    $key = $name;
    $auth = new Auth($accessKey, $secretKey);
    $config = new \Qiniu\Config();
    $bucketManager = new \Qiniu\Storage\BucketManager($auth, $config);
    $err = $bucketManager->delete($bucket, $key);
    if ($err) {
        print_r($err);
    }

    return true;
}