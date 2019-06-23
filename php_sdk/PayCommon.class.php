<?php
include 'PayConfig.php';
include "HttpClient.class.php";

class PayCommon
{
    /**
     * 验证签名
     *
     * @param $data
     * @param $key
     * @param $sign
     * @return bool;
     */
    public static function checkSign($data, $key, $sign)
    {
        unset($data['sign']);
        $resultSign = self::createSign($data, $key);
        return $resultSign === strtoupper($sign);
    }

    /**
     * 获得签名
     *
     * @param $data
     * @param $key
     * @return string
     */
    public static function createSign($data, $key)
    {
        $params = array();
        foreach ($data as $k => $v) {
            if (null !== $v && "" !== $v) {
                if (is_array($v)) {
                    $params[$k] = $k . "=" . self::getSortJson($v);
                } else {
                    $params[$k] = $k . "=" . $v;
                }
            }
        }
        ksort($params);
        $params["key"] = "key=" . $key;
        return strtoupper(md5(implode("&", $params)));
    }


    /**
     * 获取排序json
     * @param $data
     * @return string
     */
    public static function getSortJson($data)
    {
        ksort($data);
        return json_encode($data, JSON_UNESCAPED_SLASHES);
    }
}