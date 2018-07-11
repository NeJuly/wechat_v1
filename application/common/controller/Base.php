<?php
/**
 * Created by Maiya.
 * User: Junely
 * Date: 2018/7/4
 * Time: 15:30
 */

namespace app\common\controller;


use app\wechat\model\Account;
use think\App;
use think\Controller;

class Base extends Controller
{
    public $url;
    public $appid = 'wx53845bcfe8b420e1';
    public $appsecret = 'c4f5c5ce30635f18813ea67393f6bb77';
    public $access_token;
    public function __construct(App $app = null)
    {
        $this->access_token = Account::getAccessToken($this->appid,$this->appsecret);
        parent::__construct($app);
    }

    /**
     * session 存储access_token
     * @param $appid
     * @param $appsecret
     * @return mixed
     */
    public function getAccessTokenSession($appid,$appsecret)
    {
        if (session('access_token') && session('expires_time') > time()){
            $access_token = session('access_token');
        }else{
            $this->url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
            $res = json_decode(https_request($this->url),true);
            $access_token = $res['access_token'];
            session('access_token',$access_token);
            session('expires_time',time()+7000);
        }
        return $access_token;
    }

}