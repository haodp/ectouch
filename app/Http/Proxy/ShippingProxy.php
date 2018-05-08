<?php

namespace App\Http\Proxy;

use GuzzleHttp\Client;

/**
 * Class ShippingProxy
 * @package App\Http\Proxy
 */
class ShippingProxy
{
    /**
     * @var Http
     */
    private $http;

    /**
     * @var string
     */
    private $queryExpressUrl = 'https://m.kuaidi100.com/query?type=%s&postid=%s';

    /**
     * ShippingProxy constructor.
     */
    public function __construct()
    {
        $this->http = new Client();
    }

    /**
     * @param string $com
     * @param string $num
     * @return mixed
     */
    public function getExpress($com = '', $num = '')
    {
        $url = sprintf($this->queryExpressUrl, $com, $num);
        $cache_id = 'express_' . md5($url);

        $result = cache($cache_id);
        if ($result !== false) {
            return $result;
        }

        $response = $this->http->get($url, [
            'headers' => $this->defaultHeader(),
            'timeout' => 5
        ]);
        $result = json_decode($response->getBody(), true);

        if ($result['message'] === 'ok') {
            cache($cache_id, $result, 600);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 默认HTTP头
     *
     * @return array
     */
    private function defaultHeader()
    {
        $header = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.%d Safari/537.%d";
        return [
            'User-Agent' => sprintf($header, time(), time() + rand(1000, 9999)),
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-language' => 'zh-cn,zh;q=0.5',
            'Accept-Charset' => 'GB2312,utf-8;q=0.7,*;q=0.7',
        ];
    }

}