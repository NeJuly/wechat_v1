<?php
/**
 * Created by Maiya.
 * User: Junely
 * Date: 2018/7/5
 * Time: 10:52
 */

namespace app\wechat\model;


use think\Model;

class Account extends Model
{
    public $autoWriteTimestamp = true;
    public $resultSetType = '\think\Collection';
    protected $type = [
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
    protected $name = 'account';

    /**
     * 通过数据库存储用户的access_token
     * @param $appid
     * @param $appsecret
     * @return mixed|void
     */
    public static function getAccessToken($appid,$appsecret)
    {
        $access_token = self::where([
            ['appid','=',$appid],
            ['appsecret','=',$appsecret],
            ['token_expires','>',time()]
//
            ])
            ->value('access_token');

        if ($access_token){
            $test_token = file_get_contents('https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$access_token);
            $test_data = json_decode($test_token,true);
            if (isset($test_data['errcode'])) {
                $accessToken = self::get_wechat_access_token($appid,$appsecret);
            }else{
                $accessToken = $access_token;
            }
        }else{
            $accessToken = self::get_wechat_access_token($appid,$appsecret);
        }
        return $accessToken;
    }
    private static function get_wechat_access_token($appid,$appsecret)
    {
        //https请求方式: GET
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
        $access_data = json_decode(https_request($url),true);
        if (isset($access_data['access_token'])){
            $access_token = $access_data['access_token'];

            self::where([
                'appid'=>$appid,
                'appsecret'=>$appsecret
            ])->update([
                'access_token' =>$access_token,
                'token_expires' =>time()+7000,
            ]);
        }else{
//            {"errcode":40013,"errmsg":"invalid appid"}
            $access_token = [
                'errcode' =>$access_data['errcode'],
                'errmsg' =>$access_data['errmsg']
            ];
        }
        return $access_token;
    }
}