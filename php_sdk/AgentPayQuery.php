<?php

/**
 * @Description 代付成功通知接收
 */

include 'PayCommon.class.php';

$array = array(
    //商户id
    "mchId" => $merchantId,
    // 商户代付单号
    "mchOrderNo" => "AP15612999699810",
    // 代付订单号
    "agentpayOrderId" => "G01201906231026130130004",
    // 是否执行通知
    "executeNotify" => 0,
    // 请求时间
    "reqTime" => date('YmdHis'),
);

$sign = PayCommon::createSign($array, $merchantKey);
$array['sign'] = $sign;
$url = $payUrl . "/agentPay/queryOrder";
$HttpClient = new HttpClient();
$result = $HttpClient->post($url, $array, 10);
var_dump(json_decode($result));

