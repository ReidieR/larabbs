<?php

namespace App\Handlers;

use Overtrue\Pinyin\Pinyin;

class SlugTranslateHandler
{
    public function translate($text)
    {
        // 实例化 http 客户端
        $http = new \GuzzleHttp\Client;

        // 初始化配置信息
        $api = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
        $appid = config('services.baidu_translate.appid');
        $key =  config('services.baidu_translate.key');
        $salt = time();
        // dd($appid . '-' . $key);
        // 如果没有配置百度翻译则使用兼容的拼音方案
        if (empty($appid) || empty($key)) {
            return $this->pinyin($text);
        }

        // 根据百度翻译文档获取sign

        $sign = md5($appid . $text . $salt . $key);

        // 构建请求参数
        $query = http_build_query([
            "q" => $text,
            "from" => "zh",
            "to" => 'en',
            "appid" => $appid,
            "sign" => $sign,
            "salt" => $salt,
        ]);

        // 发送 Http Get 请求
        $response = $http->get($api . $query);

        $result = json_decode($response->getBody(), true);

        /**
         * 获取请求结果，如果请求成功，dd($result)结果如下：
         * 
         * array:3 [
         *    "form" => "zh",
         *    "to" => "en"
         *    "trans_result" => array:1 [
         *       0=>array:2 [
         *          "src" => "Xss 安全漏洞"
         *          "dst" => "Xss security vulnerability"
         *          ]   
         *      ]  
         * ]
         */
        //  获取翻译结果
        if (isset($result['trans_result'][0]['dst'])) {
            if ($result['trans_result'][0]['dst'] != 'edit') {
                return \Str::slug($result['trans_result'][0]['dst']);
            } else {
                return 'edit-larabbs';
            }
        } else {
            // 如果百度翻译没有结果则使用拼音作为后备计划
            return $this->pinyin($text);
        }
    }
    public function pinyin($text)
    {
        return \Str::slug(app(Pinyin::class)->permalink($text));
    }
}
