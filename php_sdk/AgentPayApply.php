<?php

/**
 * @Description 代付成功通知接收
 */

include 'PayCommon.class.php';

$array = array(
    //商户id
    "mchId" => $merchantId,
    // 商户代付单号
    "mchOrderNo" => "AP" . round(microtime(true) * 10000),
    // 代付金额,单位分
    "amount" => 119,
    // 账户属性:0-对私,1-对公,默认对私
    "accountAttr" => 0,
    // 收款人账户名
    "accountName" => "丁伟民",
    // 收款人账户号
    "accountNo" => "6222020200443455",
    // 开户行所在省份(账户属性对公时必填)
    "province" => "",
    // 开户行所在市(账户属性对公时必填)
    "city" => "",
    // 开户行名称(账户属性对公时必填)
    "bankName" => "",
    // 联行号(账户属性对公时必填)
    "bankNumber" => "",
    // 回调URL
    "notifyUrl" => $agentPayNotifyUrl,
    // 备注
    "remark" => "代付测试",
    // 扩展域
    "extra" => "",
    // 请求时间
    "reqTime" => date('YmdHis'),
    // 用户uid
    "uid" => 123455555,
    //用户ip
    "cip" => "11.23.44.23",
);

$sign = PayCommon::createSign($array, $merchantKey);
$array['sign'] = $sign;
$url = $payUrl . "/agentPay/apply";
$HttpClient = new HttpClient();
$result = $HttpClient->post($url, $array, 10);
var_dump(json_decode($result));

