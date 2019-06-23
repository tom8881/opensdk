package org.xxpay.demo.api;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import org.xxpay.demo.util.PayDigestUtil;

import java.util.Map;

/**
 * 代付demo
 *
 * @author tom.yuang
 * @date 2019/06/23
 */
public class AgentpayDemo extends BaseSdk {

    // 商户ID
    static final String mchId = "20000000";//20001223,20001245
    // 私钥
    static final String key = "D4SZ8TQK1Z8UPYMOLSKQQPMWYKVXW8IAHBMNJEFXJLCYPF7AWKCTKN1SXWS82ZNPMOBRFEGCK5TOGOQKPC59LP0FHIP6TU5GZ5TZXHHJ7YDGHSWP2URHZX1YUKPUMPAM";

    static final String payUrl = "https://api.madaofu.com/api";
    static final String notifyUrl = "http://localhost:8081/"; // 本地环境测试,可到ngrok.cc网站注册

    public static void main(String[] args) {
        //applyAgentpayTest();
        //quryAgentpayTest("", "AP1556477060400");
        quryBalanceTest();
    }

    /**
     * 申请代付接口测试
     *
     * @return
     */
    static String applyAgentpayTest() {
        JSONObject paramMap = new JSONObject();
        // 商户ID
        paramMap.put("mchId", mchId);
        // 商户代付单号
        paramMap.put("mchOrderNo", "AP" + System.currentTimeMillis());
        // 代付金额,单位分
        paramMap.put("amount", 119);
        // 账户属性:0-对私,1-对公,默认对私
        paramMap.put("accountAttr", 0);
        // 收款人账户名
        paramMap.put("accountName", "丁志伟");
        // 收款人账户号
        paramMap.put("accountNo", "6222020200098542455");
        // 开户行所在省份(账户属性对公时必填)
        paramMap.put("province", "");
        // 开户行所在市(账户属性对公时必填)
        paramMap.put("city", "");
        // 开户行名称(账户属性对公时必填)
        paramMap.put("bankName", "");
        // 联行号(账户属性对公时必填)
        paramMap.put("bankNumber", "");
        // 回调URL
        paramMap.put("notifyUrl", notifyUrl);
        // 备注
        paramMap.put("remark", "代付测试");
        // 扩展域
        paramMap.put("extra", "");
        // 请求时间
        paramMap.put("reqTime", getCurrentTime());
        // 用户uid
        paramMap.put("uid", 123455555);
        //用户ip
        paramMap.put("cip", "11.23.44.23");
        String reqSign = PayDigestUtil.getSign(paramMap, key);
        // 签名
        paramMap.put("sign", reqSign);
        String reqData = genUrlParams(paramMap);
        System.out.println("请求支付网关代付接口,请求数据:" + reqData);
        String url = payUrl + "/agentPay/apply?";
        String result = call4Post(url + reqData);
        System.out.println("请求支付网关代付接口,响应数据:" + result);
        Map retMap = JSON.parseObject(result);
        if ("SUCCESS".equals(retMap.get("retCode"))) {
            // 验签
            String checkSign = PayDigestUtil.getSign(retMap, key, "sign");
            String retSign = (String) retMap.get("sign");
            if (checkSign.equals(retSign)) {
                System.out.println("=========支付网关代付验签成功=========");
            } else {
                System.err.println("=========支付网关代付验签失败=========");
                return null;
            }
        }
        return retMap.get("agentpayOrderId") + "";
    }

    /**
     * 查询代付接口测试
     *
     * @param agentpayOrderId
     * @param mchOrderNo
     * @return
     */
    static String quryAgentpayTest(String agentpayOrderId, String mchOrderNo) {
        JSONObject paramMap = new JSONObject();
        paramMap.put("mchId", mchId);                               // 商户ID
        paramMap.put("agentpayOrderId", agentpayOrderId);           // 代付单号
        paramMap.put("mchOrderNo", mchOrderNo);                     // 商户订单号
        paramMap.put("executeNotify", "true");                      // 是否执行回调,true或false,如果为true当订单状态为支付成功(2)时,支付网关会再次回调一次业务系统
        paramMap.put("reqTime", getCurrentTime());                  // 请求时间
        String reqSign = PayDigestUtil.getSign(paramMap, key);
        paramMap.put("sign", reqSign);                              // 签名
        String reqData = genUrlParams(paramMap);
        System.out.println("请求支付网关代付查询接口,请求数据:" + reqData);
        String url = payUrl + "/agentPay/queryOrder?";
        String result = call4Post(url + reqData);
        System.out.println("请求支付网关代付查询接口,响应数据:" + result);
        Map retMap = JSON.parseObject(result);
        if ("SUCCESS".equals(retMap.get("retCode"))) {
            // 验签
            String checkSign = PayDigestUtil.getSign(retMap, key, "sign");
            String retSign = (String) retMap.get("sign");
            if (checkSign.equals(retSign)) {
                System.out.println("=========支付网关代付查询验签成功=========");
            } else {
                System.err.println("=========支付网关代付查询验签失败=========");
                return null;
            }
        }
        return retMap.get("agentpayOrderId") + "";
    }

    /**
     * 查询代付余额接口测试
     *
     * @return
     */
    static String quryBalanceTest() {
        JSONObject paramMap = new JSONObject();
        paramMap.put("mchId", mchId);                               // 商户ID
        paramMap.put("reqTime", getCurrentTime());                  // 请求时间
        String reqSign = PayDigestUtil.getSign(paramMap, key);
        paramMap.put("sign", reqSign);                              // 签名
        String reqData = genUrlParams(paramMap);
        System.out.println("请求支付网关代付余额查询接口,请求数据:" + reqData);
        String url = payUrl + "/agentPay/queryBalance?";
        String result = call4Post(url + reqData);
        System.out.println("请求支付网关代付余额查询接口,响应数据:" + result);
        Map retMap = JSON.parseObject(result);
        if ("SUCCESS".equals(retMap.get("retCode"))) {
            // 验签
            String checkSign = PayDigestUtil.getSign(retMap, key, "sign");
            String retSign = (String) retMap.get("sign");
            if (checkSign.equals(retSign)) {
                System.out.println("=========支付网关代付余额查询验签成功=========");
            } else {
                System.err.println("=========支付网关代付余额查询验签失败=========");
                return null;
            }
        }
        return retMap.get("agentpayBalance") + "";
    }

}
