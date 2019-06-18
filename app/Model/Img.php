<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Img extends Model
{
    public static $SAVE_PATH = 'app/public/';
    public static $QRCODE = 'app/public/evaluate_qrcode';
    public static $EQRCODE = 'evaluate_qrcode_forever';  //司机评价二维码永久
    public static $EXCEL = 'excel';
    public static $IMG = '';
    public static $IMG_EXT = ["png", "jpg", "gif","jpeg","bmp","mp4","avi","mov","wmv","rmvb",'rm'];
    public static $FILE_EXT = ['doc','docx','pdf','xls','xlsx','csv','CSV','Csv',"png", "jpg", "gif","jpeg","bmp","mp4","avi","mov","wmv","rmvb",'rm'];
}
