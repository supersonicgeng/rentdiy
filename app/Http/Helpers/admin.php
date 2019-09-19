<?php
/**
 * 递归生成无限极分类数组
 * @param $data
 * @param int $parent_id
 * @param int $count
 * @return array
 */
//function tree(&$data, $parent_id = 0, $count = 1)
//{
//    static $treeList = [];
//
//    foreach ($data as $key => $value) {
//        if ($value['parent_id'] == $parent_id) {
//            $value['count'] = $count;
//            $treeList [] = $value;
//            unset($data[$key]);
//            tree($data, $value['id'], $count + 1);
//        }
//    }
//    return $treeList;
//}

/***
 * @param $data
 * @param int $id
 * @return array
 * 树形图
 */
function treeSon(&$data, $id, $member_type, $count = 1)
{

    $tree = [];

    foreach ($data as $key => $value) {

        if ($value['parent_id'] == $id) {
            //儿子大于爸爸 跳出循环
            if ($value['member_type'] > $member_type or $value['member_type'] >= 2) {
                unset($data[$key]);
                continue;
            }

            //查询二级市场 父级不是vip或者超级vip 就脱团
            if ($count == 2 and $member_type == 1) {
                unset($data[$key]);
                continue;
            }

            if ($count > 2) {
                if ($member_type < 3) {
                    unset($data[$key]);
                    continue;
                }
            }

            //父亲找到儿子
            $v['children'] = treeSon($data, $value['id'], $member_type, $count + 1);
            $v['name'] = $value['id'];
            $v['value'] = $value['username'];
            $tree[] = $v;
            unset($data[$key]);

        }
    }
    return $tree;
}


/***
 * @param $members
 * @param $mid 父级
 * @return array
 */
function GetTeamMember($members, $mid)
{
    $Teams = array();//最终结果

    if (is_array($mid)) {
        $mids = $mid;//第一次执行时候的用户id
    } else {
        $mids = array($mid);//第一次执行时候的用户id
    }

    do {
        $othermids = array();
        $state = false;
        foreach ($mids as $valueone) {
            foreach ($members as $key => $valuetwo) {

                //由于推广员 和 超级推广员 同级互斥
                if ($valuetwo['parent_id'] == $valueone and $valuetwo['member_type'] < 2) {

                    $Teams[] = $valuetwo['id'];//找到我的下级立即添加到最终结果中
                    $othermids[] = $valuetwo['id'];//将我的下级id保存起来用来下轮循环他的下级
//                    array_splice($members, $key, 1);//从所有会员中删除他
                    unset($members[$key]);
                    $state = true;
                }
            }
        }
        $mids = $othermids;//foreach中找到的我的下级集合,用来下次循环
    } while ($state == true);

    return $Teams;
}


/***
 * 查询第二市场ID
 * @param $data
 * @param int $parent_id
 * @return array
 */
function secondMark(&$data, $parent_id, $member_type, $count = 1)
{
    $treeList = [];

    foreach ($data as $key => $value) {
        if ($value['parent_id'] == $parent_id) {

            //儿子大于爸爸 跳出循环
            if ($value['member_type'] > $member_type || $value['member_type'] >= 2) {
                unset($data[$key]);
                continue;
            }

            if ($count == 1) {
                unset($data[$key]);
                $treeList = array_merge($treeList, secondMark($data, $value['id'], $member_type, $count + 1));

                continue;
            }


            if ($count == 2 and $member_type >= 2) {

                $treeList [] = $value['id'];
                unset($data[$key]);
                $treeList = array_merge($treeList, secondMark($data, $value['id'], $member_type, $count + 1));
                continue;
            }

            if ($count > 2 and $member_type == 3) {
                $treeList [] = $value['id'];
                unset($data[$key]);
                $treeList = array_merge($treeList, secondMark($data, $value['id'], $member_type, $count + 1));
            }

        }
    }
    return $treeList;
}


function son(&$data, $parent_id = 0)
{
    static $treeList = [];

    foreach ($data as $key => $value) {
        if ($value['parent_id'] == $parent_id and $value['member_type'] < 2) {
            $treeList [] = $value['id'];
            unset($data[$key]);
            son($data, $value['id']);
        }
    }
    return $treeList;
}


/***
 * 找上级
 */
function father($data, $parent_id = 0)
{
    static $treeList = [];

    foreach ($data as $key => $value) {
        if ($value['id'] == $parent_id) {
            $treeList [] = $value['id'];
            unset($data[$key]);


            father($data, $value['parent_id']);
        }
    }
    return $treeList;
}

/**
 * 栏目名前面加上缩进
 * @param $count
 * @return string
 */
function indent_category($count)
{
    $str = '';
    for ($i = 1; $i < $count; $i++) {
        $str .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    return $str;
}

/***
 * 用户查询字段
 */
function customer_search($field)
{
    switch ($field) {
        case 'id':
            return '用户ID';
            break;
        case 'username':
            return '用户手机号';
            break;
        case 'parent_id':
            return '直属上级ID';
            break;
        case 'grandpa_id':
            return '直属上上级ID';
            break;
        case 'last_super':
            return 'SVIP';
            break;
        case 'invit_code':
            return '邀请码';
            break;
    }
}

/***
 * 商品订单查询字段
 */
function order_search($field)
{
    switch ($field) {
        case 'trade_id':
            return '订单ID';
            break;
        case 'num_iid':
            return '商品ID';
            break;
        case 'v2':
            return '上级ID';
            break;
        case 'v3':
            return '上上级ID';
            break;
        case 'v4':
            return 'SVIPID';
            break;

    }
}

/***
 * 会员订单查询状态
 */
function member_order($field)
{
    switch ($field) {
        case 'username':
            return '用户名';
            break;
        case 'trade_id':
            return '订单号';
            break;
        case 'c.id':
            return '用户ID';
            break;
        case 'v2':
            return '上级ID';
            break;
        case 'v3':
            return '上上级ID';
            break;
        case 'v4':
            return 'SVIP一ID';
            break;
        case 'v5':
            return 'SVIP二ID';
            break;

    }
}

/**
 * @func：uuid  生成唯一的UUID
 * @author
 * @date   2018-03-27
 * @return string JSON
 */
function couponUUID($prefix = '')
{
//    $chars = md5(uniqid(mt_rand(), true));
//
//    $uuid = substr($chars, 0, 5);


    return uniqid();
}


/***
 * 时间戳格式化
 */
function Timeformat($time)
{
    return date("Y-m-d H:i:s", $time);
}

/**
 * @func：生成订单号
 * @data $type 订单类型
 * @return string
 */
function order_sn($type)
{
//    $order_sn = $type . time() . substr(uniqid(), -6);

    $order_sn = $type . date('YmdHis') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);


    return $order_sn;
}


/***
 * 百分比
 */
function persent($str)
{
    return (round($str, 4) * 100) . '%';

}

/**
 * @func：setReturn 验证手机号码合法性
 * @author
 * @date
 * @return   string
 */
function checkPhone($phone_number, $descript = '')
{
    //判断手机号码不为空
    if (empty($phone_number)) {
        setReturnS(0, $descript . '手机号为空,请输入');
    }
    //判断手机号码格式
    if (!preg_match("/^1[3|4|5|6|7|8|9]\d{9}$/", $phone_number)) {
        setReturnS(0, $descript . '手机号格式有误,请重新输入');
    }
    return true;
}

/**
 * @func：时间格式设置
 * @author MJ
 * @date   2018-03-28
 * @return string JSON
 */
function timeSpan($timestamp, $current_time = 0)
{
    if (!$current_time) $current_time = time();
    $span = $current_time - $timestamp;
    if ($span < 60) {
        return "刚刚";
    } elseif ($span < 3600) {
        return intval($span / 60) . "分钟前";
    } elseif ($span < 24 * 3600) {
        return intval($span / 3600) . "小时前";
    } elseif ($span < (7 * 24 * 3600)) {
        return intval($span / (24 * 3600)) . "天前";
    } else {
        return date('Y-m-d H:i:s', $timestamp);
    }
}

/***
 * 次数转换
 */
function display_times($times)
{

    if ($times < 10000) {
        return $times;
    } elseif ($times >= 10000 and $times < 100000000) {

        return round($times / 10000, 1) . '万';

    } elseif ($times >= 100000000) {
        return round($times / 100000000, 1) . '亿';

    }
}


/**
 * @param $id 身份证号码
 * @author
 * @data
 * @return bool
 */
function is_idcard($id)
{
    $id = strtoupper($id);
    $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
    $arr_split = array();
    if (!preg_match($regx, $id)) {
        return FALSE;
    }
    if (15 == strlen($id)) //检查15位
    {
        $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";

        @preg_match($regx, $id, $arr_split);
        //检查生日日期是否正确
        $dtm_birth = "19" . $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
        if (!strtotime($dtm_birth)) {
            return FALSE;
        } else {
            return TRUE;
        }
    } else      //检查18位
    {
        $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
        @preg_match($regx, $id, $arr_split);
        $dtm_birth = $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
        if (!strtotime($dtm_birth)) //检查生日日期是否正确
        {
            return FALSE;
        } else {
            //检验18位身份证的校验码是否正确。
            //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
            $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            $sign = 0;
            for ($i = 0; $i < 17; $i++) {
                $b = (int)$id{$i};
                $w = $arr_int[$i];
                $sign += $b * $w;
            }
            $n = $sign % 11;
            $val_num = $arr_ch[$n];
            if ($val_num != substr($id, 17, 1)) {
                return FALSE;
            } //phpfensi.com
            else {
                return TRUE;
            }
        }
    }

}


/***
 * 数字 转资金
 */
function number_transform($number)
{
    return number_format($number, 2, '.', ',');

}


/***
 * 列表排序
 * @param $param
 * @return string
 */
function table_sort($param)
{
    $url_path = url()->current();
    $faStr = 'fa-sort';
    $get = Request::all();

    if (isset($get['_sort'])) {   //判断是否存在排序字段
        $sortArr = explode(',', $get['_sort']);
        if ($sortArr[0] == $param) {   //当前排序
            if ($sortArr[1] == 'asc') {
                $faStr = 'fa-sort-asc';
                $sort = 'desc';
            } elseif ($sortArr[1] == 'desc') {
                $faStr = 'fa-sort-desc';
                $sort = 'asc';
            }
            $get['_sort'] = $param . ',' . $sort;
        } else {   //非当前排序
            $get['_sort'] = $param . ',asc';
        }
    } else {
        $get['_sort'] = $param . ',asc';
    }
    $paramStr = [];
    foreach ($get as $k => $v) {
        $paramStr[] = $k . '=' . $v;
    }
    $paramStrs = implode('&', $paramStr);
    $url_path = $url_path . '?' . $paramStrs;
    return "&nbsp<a class=\"fa " . $faStr . "\" href=\"" . $url_path . "\"></a>";
}

/***
 * @param $attr
 * @param $module
 * @return string
 */
function is_something($attr, $module)
{
    return $module->$attr ? '<a href="javascript:void(0);" data-attr="' . $attr . '" class="fa fa-check-circle text-green change_attr"></a>' : '<a href="javascript:void(0);" data-attr="' . $attr . '" class="fa fa-times-circle text-red change_attr"></a>';
}

/**
 * 会员等级
 */
function member_level($data)
{
    if ($data == 1) {

        return '<small class="label bg-blue">高级会员</small>';

    } elseif ($data == 2) {
        return '<small class="label bg-yellow">合伙人</small>';

    } elseif ($data == 3) {
        return '<small class="label bg-red">超级合伙人</small>';

    } elseif ($data == 4) {
        return '<small class="label bg-blue">普通会员</small>';

    }
}

/***
 * 淘宝订单状态
 */
function tb_order_status($tk_status)
{
    switch ($tk_status) {
        case 3:
            return '订单结算';
            break;
        case 12:
            return '订单付款';
            break;
        case 13:
            return '订单失效';
            break;
        case 14:
            return '订单成功(确认收货)';
            break;

    }
}


/***
 * 获取优惠券优惠金额
 */
function getCouponPrice($coupon)
{
    $preg = '/减[\s\S]*?元/i';
    preg_match_all($preg, $coupon, $res);

    $price = mb_substr($res[0][0], 1, -1, 'utf-8');

    return $price;
}

/***
 * 广告位跳转类型
 */
function jumpType($type)
{
    switch ($type) {
        case 1:
            return '单品';
            break;
        case 2:
            return '列表';
            break;
        case 3:
            return '跳转';
            break;
        case 4:
            return '榜单列表';
            break;

    }
}


/**
 * @param int $code
 * @param string $msg
 * @param null 数据格式化
 */
function setReturnS($code = 0, $msg = '', $data = null)
{
    if ($code == 0 && $msg == '') {
        $msg = '网络繁忙,请稍后再试';
    }
    $returnData = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    ];
    echo json_encode($returnData, JSON_UNESCAPED_UNICODE);
    exit();
}


/**
 * RSA签名
 * @param $data 待签名数据
 * @param $private_key_path 商户私钥文件路径
 * return 签名结果
 */
function rsaSign($data)
{
    $path = 'data/key/public_key.txt';
    $st = json_encode($data);
    $pri = file_get_contents($path);
    $pk = openssl_pkey_get_public($pri);
    openssl_public_encrypt($st, $encrypt_data, $pk);
    return base64_encode($encrypt_data);
}


/***
 * 信用卡订单状态
 */
function kdfOrderStatus($type)
{
    switch ($type) {
        case 0:
            return '老户订单';
            break;
        case 1:
            return '待查询';
            break;
        case 2:
            return '审核中';
            break;
        case 3:
            return '审核通过';
            break;
        case 4:
            return '审核拒绝';
            break;
        case 5:
            return '未完成';
            break;
        case 6:
            return '异常订单';
            break;
        case 7:
            return '失效订单';
            break;

    }
}

/***
 * 信用卡订单搜索类型
 */
function kdf_order_search($field)
{
    switch ($field) {
        case 'kdf_id':
            return '订单ID';
            break;
        case 'v2':
            return '上级ID';
            break;
        case 'v3':
            return '上上级ID';
            break;
        case 'v4':
            return 'SVIPID';
            break;

    }
}


/***
 * 手机回收订单状态
 */
function phoneStatus($type)
{
    switch ($type) {
        case 10:
            return '待下单';
            break;
        case 11:
            return '待揽件邮寄';
            break;
        case 13:
            return '物流运输中';
            break;
        case 14:
            return '到货检测中';
            break;
        case 15:
            return '待确认交易';
            break;
        case 16:
            return '待付款';
            break;
        case 17:
            return '付款中';
            break;
        case 18:
            return '付款失败';
            break;
        case 19:
            return '付款成功';
            break;
        case 91:
            return '取消交易';
            break;

    }
}

/***
 * 手机回收订单搜索类型
 */
function phone_order_search($field)
{
    switch ($field) {
        case 'okey':
            return '订单ID';
            break;
        case 'v2':
            return '上级ID';
            break;
        case 'v3':
            return '上上级ID';
            break;
        case 'v4':
            return 'SVIPID';
            break;

    }
}

/**
 *时间戳 转   日期格式 ： 精确到毫秒，x代表毫秒
 */
function get_microtime_format($time)
{
    if (strstr($time, '.')) {
        sprintf("%01.3f", $time); //小数点。不足三位补0
        list($usec, $sec) = explode(".", $time);
        $sec = str_pad($sec, 3, "0", STR_PAD_RIGHT); //不足3位。右边补0
    } else {
        $usec = $time;
        $sec = "000";
    }
    $date = date("Y-m-d H:i:s", $usec);
    return str_replace('x', $sec, $date);
}

/**
 *时间日期转时间戳格式，精确到毫秒
 */
function get_data_format($time)
{
    list($usec, $sec) = explode(".", $time);
    $date = strtotime($usec);
    $return_data = str_pad($date . $sec, 13, "0", STR_PAD_RIGHT); //不足13位。右边补0
    return $return_data;
}
