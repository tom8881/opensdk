<?php

/**
 * @Description 代付成功通知接收
 */

include 'PayCommon.class.php';

$array = array(
    //商户id
    "mchId" => $merchantId,
    // 代付单号
    "transOrderId" => $_REQUEST['transOrderId'],
    // 代付金额,单位分
    "appId" => $_REQUEST['transOrderId'],
    // 商户订单号
    "mchOrderNo" => $_REQUEST['mchOrderNo'],
    // 支付金额
    "amount" => $_REQUEST['amount'],
    // 状态
    "status" => $_REQUEST['status'],
    // 转账结果
    "result" => $_REQUEST['result'],
    // 渠道订单号
    "channelOrderNo" => $_REQUEST['channelOrderNo'],
    // 渠道错误描述
    "channelErrMsg" => $_REQUEST['channelErrMsg'],
    // 扩展参数1
    "param1" => $_REQUEST['param1'],
    // 扩展参数2
    "param2" => $_REQUEST['param2'],
    // 转账成功时间
    "transSuccTime" => $_REQUEST['transSuccTime'],
);

$result = PayCommon::checkSign($array, $merchantKey, $_REQUEST['sign']);

if($result){
    echo "success";
}
