<?php

class HttpClient
{
    protected $_error = array(
        'code' => 0,
        'msg' => '',
        'cost' => 0,
    );
    private $_curlopt = array(
        CURLOPT_RETURNTRANSFER => true
    );

    private $_httpHeader = array();
    private $_cookie = '';

    public function _construct($config = array())
    {
        if (!empty($config)) {
            $this->setOpt($config);
        }
    }

    public function setCode($code)
    {
        $this->_error['code'] = $code;
    }

    public function setMessage($message)
    {
        $this->_error['msg'] = $message;
    }

    public function getError()
    {
        return $this->_error;
    }

    public function getCode()
    {
        return $this->_error['code'];
    }

    public function getMessage()
    {
        return $this->_error['msg'];
    }

    /**
     * GET
     * @param $url
     * @param $data
     * @param int $timeout
     * @return bool|mixed
     */
    public function get($url, $data = '', $timeout = 5)
    {
        if ('https' === substr($url, 0, 5)) {
            $options = array(
                CURLOPT_BINARYTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_TIMEOUT => $timeout,
            );
        } else {
            $options = array(
                CURLOPT_HTTPGET => 1,
                CURLOPT_HEADER => false,
                CURLOPT_TIMEOUT => $timeout,
            );
        }

        $options[CURLOPT_TIMEOUT] = $timeout;
        if (!empty($data)) {
            $data = is_array($data) ? http_build_query($data) : $data;
            $url .= '?' . $data;
        }
        return $this->_request('get', $url, $options);
    }

    /**
     * POST JSon
     * @param $url
     * @param $data
     * @param int $timeout
     * @return mixed
     */
    public function postJson($url, $data, $timeout = 5)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }

        /** 判断HTTPS请求 */
        if ('https' === substr($url, 0, 5)) {
            $options = array(
                CURLOPT_BINARYTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data,
            );
        } else {
            $options = array(
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data,
            );
        }
        $options[CURLOPT_TIMEOUT] = $timeout;
        $this->setHeader(
            array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length:' . strlen($data)
            )
        );
        return $this->_request('post', $url, $options);
    }

    /**
     * POST
     * @param $url
     * @param $data
     * @param int $timeout
     * @return mixed
     */
    public function post($url, $data, $timeout = 5)
    {
        /** 判断HTTPS请求 */
        if ('https' === substr($url, 0, 5)) {
            $options = array(
                CURLOPT_BINARYTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data,
            );
        } else {
            $options = array(
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data,
            );
        }
        $options[CURLOPT_TIMEOUT] = $timeout;
        return $this->_request('post', $url, $options);
    }

    private function _request($method, $url, $options)
    {
        $curl = curl_init($url);

        if ($curl === false) {
            $this->setError(-1, $method . '-curl_init');
            return false;
        }

        $curl_opts = $this->merge($options, $this->_curlopt);

        if (!empty($this->_httpHeader)) {
            $curl_opts[CURLOPT_HTTPHEADER] = $this->getHeader();
        }

        if (!empty($this->_cookie)) {
            $curl_opts[CURLOPT_COOKIE] = $this->getCookie();
        }

        curl_setopt_array($curl, $curl_opts);

        $start = $this->_microtime();
        $rs = curl_exec($curl);
        $conn_time = round($this->_microtime() - $start, 3);

        if ($rs === false) {
            $this->setError(curl_errno($curl), curl_error($curl));
            curl_close($curl);
            return false;
        }
        curl_close($curl);
        $this->_error['cost'] = $conn_time;
        return $rs;
    }

    /**
     * 设置CURL参数
     * @param $name
     * @param $value
     * @return $this
     */
    public function setOpt($name, $value = '')
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->setOpt($k, $v);
            }
        } else {
            if ($name == CURLOPT_PROXY) {
                $this->_curlopt[CURLOPT_HTTPPROXYTUNNEL] = 1;
            }
            $this->_curlopt[$name] = $value;
        }
        return $this;
    }

    public function setHeader($name, $value = '')
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->setHeader($k, $v);
            }
        } else {
            $this->_httpHeader[$name] = $value;
        }
        return $this;
    }

    public function getHeader()
    {
        return $this->_httpHeader;
    }

    public function setCookie($cookie)
    {
        $this->_cookie = $cookie;
        return $this;
    }

    public function getCookie()
    {
        return $this->_cookie;
    }

    public function resetHeader()
    {
        $this->_httpHeader = array();
        return $this;
    }

    public function resetCookie()
    {
        $this->_cookie = '';
        return $this;
    }

    /**
     * 时间统计
     * @return mixed
     */
    private function _microtime()
    {
        return microtime(true);
    }

    public function merge()
    {
        $list = func_get_args();
        if (empty($list))
            return array();
        $data = array();
        foreach ($list as $vo) {
            if (!is_array($vo)) {
                continue;
            }
            if (empty($data)) {
                $data = $vo;
            } else {
                foreach ($vo as $k => $v) {
                    $data[$k] = $v;
                }
            }
        }

        return $data;
    }
}