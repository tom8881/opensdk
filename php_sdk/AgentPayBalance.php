<?php

/**
 * @Description 代付成功通知接收
 */

include 'PayCommon.class.php';

$array = array(
    //商户id
    "mchId" => $merchantId,
    // 请求时间
    "reqTime" => date('YmdHis'),
);

$sign = PayCommon::createSign($array, $merchantKey);
$array['sign'] = $sign;
$url = $payUrl . "/agentPay/queryBalance";
$HttpClient = new HttpClient();
$result = $HttpClient->post($url, $array, 10);
var_dump(json_decode($result));

