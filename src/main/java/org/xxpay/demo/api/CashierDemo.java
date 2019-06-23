package org.xxpay.demo.api;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import org.xxpay.demo.util.PayDigestUtil;

import java.util.Map;

/**
 * 生成收银台URl
 *
 * @author tom.yuang
 * @date 2019/06/23
 */
public class CashierDemo extends BaseSdk {

    // 商户ID
    static final String mchId = "20000000";//20001223,20001245
    static final String appId = "710ddfebd2154434a8cfee1807b27eea";
    static final String productId = "8000,8001";
    // 私钥
    static final String key = "bWK682hxuJSXWHilCFxHCpaIANFBuT4GnDnglrXgHFOwgsrx";

    static final String baseUrl = "http://127.0.01:3020/api";
    static final String notifyUrl = "http://www.baidu.com"; // 本地环境测试,可到ngrok.cc网站注册

    public static void main(String[] args) {
        buildPaymentUrl();
    }

    static String buildPaymentUrl() {

        JSONObject paramMap = new JSONObject();
        paramMap.put("mchId", mchId);                               // 商户ID
        paramMap.put("appId", appId);
        paramMap.put("mchOrderNo", System.currentTimeMillis());     // 商户订单号
        paramMap.put("productId", productId);                       // 支付产品
        paramMap.put("amount", 1);                                  // 支付金额,单位分
        paramMap.put("subject", "XXPAY支付测试");
        paramMap.put("body", "XXPAY支付测试");
        paramMap.put("notifyUrl", notifyUrl);                       // 回调URL

        String reqSign = PayDigestUtil.getSign(paramMap, key);
        paramMap.put("sign", reqSign);                              // 签名
        String reqData = "params=" + paramMap.toJSONString();
        System.out.println("请求支付中心下单接口,请求数据:" + reqData);
        String url = baseUrl + "/cashier/pc_build?";
        String result = call4Post(url + reqData);
        System.out.println("请求支付中心下单接口,响应数据:" + result);
        Map retMap = JSON.parseObject(result);
        if ("SUCCESS".equals(retMap.get("retCode"))) {
            // 验签
            String checkSign = PayDigestUtil.getSign(retMap, key, "sign");
            String retSign = (String) retMap.get("sign");
            if (checkSign.equals(retSign)) {
                System.out.println("=========支付中心下单验签成功=========");
            } else {
                System.err.println("=========支付中心下单验签失败=========");
                return null;
            }
        }
        return retMap.get("payOrderId") + "";
    }

}
