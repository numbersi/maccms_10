<?php
namespace app\index\controller;
use think\captcha\Captcha;
use think\config;
use think\console\command\make\Controller;

class Verify extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id='')
    {
        header("Content-type: image/png");
        ob_clean();
        $captcha = new Captcha((array)Config::get('captcha'));
        return $captcha->entry($id);
    }

    public function check($verify,$id='')
    {
        if(!captcha_check($verify)){
            return 0;
        }
        else{
            return 1;
        }
    }

}
