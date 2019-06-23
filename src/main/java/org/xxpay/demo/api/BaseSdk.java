package org.xxpay.demo.api;

import org.xxpay.demo.util.HttpClient;

import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLEncoder;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Map;
import java.util.Set;

/**
 * @author: tom.yuang
 * @date: 2018/4/16
 * @description:
 */
public class BaseSdk {

    /**
     * 发起HTTP/HTTPS请求(method=POST)
     * @param url
     * @return
     */
    public static String call4Post(String url) {
        try {
            URL url1 = new URL(url);
            if("https".equals(url1.getProtocol())) {
                return HttpClient.callHttpsPost(url);
            }else if("http".equals(url1.getProtocol())) {
                return HttpClient.callHttpPost(url);
            }else {
                return "";
            }
        } catch (MalformedURLException e) {
            e.printStackTrace();
        }
        return "";
    }

    public static String genUrlParams(Map<String, Object> paraMap) {
        if(paraMap == null || paraMap.isEmpty()) {
            return "";
        }
        StringBuffer urlParam = new StringBuffer();
        Set<String> keySet = paraMap.keySet();
        int i = 0;
        for(String key:keySet) {
            urlParam.append(key).append("=");
            if(paraMap.get(key) instanceof String) {
                urlParam.append(URLEncoder.encode((String) paraMap.get(key)));
            }else {
                urlParam.append(paraMap.get(key));
            }
            if(++i == keySet.size()) {
                break;
            }
            urlParam.append("&");
        }
        return urlParam.toString();
    }

    //请求时间 格式:YYYYMMDDhhmmss
    public static String getCurrentTime() {
        return new SimpleDateFormat("yyyyMMddHHmmss").format(new Date());
    }

}
