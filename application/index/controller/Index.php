<?php
namespace app\index\controller;

class Index extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->label_fetch('index/index');
    }
    public function image()
    {
        header('Content-type: image/jpg');
        $url = $_GET['url'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
//打印获得的数据
        echo $output;
        curl_close($ch);
    }

    public function delCDN ()
    {
        /*
         * curl -X DELETE "https://api.cloudflare.com/client/v4/zones/eac5272c38291a73b90766d296a62f23/purge_cache"
-H "X-Auth-Email: tech@spacious.hk"
-H "X-Auth-Key: a14d124c64d7b5ef329766d71abef81443be5"
-H "Content-Type: application/json"
--data '{"files":[{"url":"https://www.example.com/assets/apps/d17be48.js","headers":{"Origin":"www.example.com"}}]}'
         */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/zones/e8173a2228f1e6f119193b2f90b5b2ec77233/purge_cache');

        $header = ["X-Auth-Email:136222866@qq.com","X-Auth-Key: e8173a2228f1e6f119193b2f90b5b2ec77233","Content-Type: application/json"];
//https://dash.cloudflare.com/api/v4/zones/45f1473417288e11b1b471da359e4a8c/purge_cache/
        //设置一个你的浏览器agent的header
        curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $params = ["url" => "http://www.fniaotv.com/static/js/playerconfig.js", "headers" => ["Origin" => "www.fniaotv.com"]];
        curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
//打印获得的数据
        echo $output;
        curl_close($ch);
    }

}
