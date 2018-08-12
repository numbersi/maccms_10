<?php
namespace app\admin\controller;
use think\Controller;
use app\common\controller\All;
use think\Cache;
use app\common\util\Dir;

class Base extends All
{
    var $_admin;
    var $_pagesize;
    var $_makesize;

    public function __construct()
    {
        parent::__construct();

        //判断用户登录状态
        $cl = request()->controller();
        $ac = request()->action();

        if(in_array($cl,['Index']) && in_array($ac,['login','logout'])) {

        }
        elseif(ENTRANCE=='api' && in_array($cl,['Timming']) && in_array($ac,['index'])){

        }
        else {
            $res = model('Admin')->checkLogin();

            if ($res['code'] > 1) {
                return $this->redirect('index/login');
            }
            $this->_admin = $res['info'];
            $this->_pagesize = $GLOBALS['config']['app']['pagesize'];
            $this->_makesize = $GLOBALS['config']['app']['makesize'];

            if($cl!='Update' && !$this->check_auth($cl,$ac)){
                return $this->error('您没有权限访问此页面');
            }
        }
    }

    public function check_auth($c,$a)
    {
        $c = strtolower($c);
        $a = strtolower($a);

        $auths = $this->_admin['admin_auth'] . ',index/index,index/welcome,';
        $cur = ','.$c.'/'.$a.',';
        if($this->_admin['admin_id'] =='1'){
            return true;
        }
        elseif(strpos($auths,$cur)===false){
            return false;
        }
        else{
            return true;
        }
    }

    public function _cache_clear()
    {
        //播放器配置缓存
        $vodplayer = config('vodplayer');
        $voddowner = config('voddowner');
        $vodserver = config('vodserver');
        $player = [];
        foreach($vodplayer as  $k=>$v){
            $player[$k] = [
                'show'=>(string)$v['show'],
                'des'=>(string)$v['des'],
                'ps'=>(string)$v['ps'],
                'parse'=>(string)$v['parse'],
            ];
        }
        $downer = [];
        foreach($voddowner as  $k=>$v){
            $downer[$k] = [
                'show'=>(string)$v['show'],
                'des'=>(string)$v['des'],
                'ps'=>(string)$v['ps'],
                'parse'=>(string)$v['parse'],
            ];
        }

        $server = [];
        foreach($vodserver as  $k=>$v){
            $server[$k] = [
                'show'=>(string)$v['show'],
                'des'=>(string)$v['des']
            ];
        }
        $content = 'MacPlayerConfig.player_list='.json_encode($player) . ',MacPlayerConfig.downer_list='.json_encode($downer) . ',MacPlayerConfig.server_list='.json_encode($server) .';';
        $path = './static/js/playerconfig.js';
        if(!file_exists($path)){ $path .= '.bak'; }
        $fc = @file_get_contents( $path );
        $jsb = mac_get_body($fc,'//缓存开始','//缓存结束');
        $fc = str_replace($jsb,"\r\n".$content."\r\n",$fc);
        @fwrite(fopen('./static/js/playerconfig.js','wb'),$fc);

        Dir::delDir(RUNTIME_PATH.'cache/');
        Dir::delDir(RUNTIME_PATH.'log/');
        Dir::delDir(RUNTIME_PATH.'temp/');

        return true;
    }

    protected function clearCDN()
    {
        /*
         *
        curl -X DELETE "https://api.cloudflare.com/client/v4/zones/eac5272c38291a73b90766d296a62f23/purge_cache"
    -H "X-Auth-Email: tech@spacious.hk"
    -H "X-Auth-Key: a14d124c64d7b5ef329766d71abef81443be5"
    -H "Content-Type: application/json"
    --data '{"files":[{"url":"https://www.example.com/assets/apps/d17be48.js","headers":{"Origin":"www.example.com"}}]}'
         */

    }


}