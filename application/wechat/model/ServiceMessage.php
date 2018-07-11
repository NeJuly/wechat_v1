<?php
/**
 * Created by Maiya.
 * User: Junely
 * Date: 2018/7/9
 * Time: 14:23
 */

namespace app\wechat\model;


use think\Model;

/**
 * 客服消息
 * Class ServiceMessage
 * @package app\wechat\model
 */
class ServiceMessage extends Model
{
    /**
     * @param $postObj
     * @param $access_token
     * @param string $type
     * @return bool
     */
    public function serviceData($postObj,$access_token,$type = 'text')
    {
        switch($type){
            case 'text':
                //发送文本消息
                /*{
                    "touser":"OPENID",
                    "msgtype":"text",
                    "text":
                    {
                         "content":"Hello World"
                    }
                   }*/
                $arr = [
                    'touser'=>"{$postObj->FromUserName}",
                    'msgtype'=>'text',
                    'text'=>
                        [
                            'content'=>'<a href="http://www.baidu.com">欢迎您关注junely的测试号</a>'
                        ]
                ];
                break;
            case 'news':
                //发送图文消息（点击跳转到外链） 图文消息条数限制在8条以内，注意，如果图文数超过8，则将会无响应。
                /*{
                    "touser":"OPENID",
                    "msgtype":"news",
                    "news":{
                        "articles": [
                         {
                             "title":"Happy Day",
                             "description":"Is Really A Happy Day",
                             "url":"URL",
                             "picurl":"PIC_URL"
                         },
                         {
                             "title":"Happy Day",
                             "description":"Is Really A Happy Day",
                             "url":"URL",
                             "picurl":"PIC_URL"
                         }
                         ]
                    }
                 }
                 * */
                $arr = [
                    'touser' => "{$postObj->FromUserName}",
                    'msgtype' => "news",
                    'news' =>
                        [
                        'articles' =>
                            [
                                [
                                    "title" => "百度一下，你就知道",
                                    "description" => "This is first news title description",
                                    "url" => "http://www.baidu.com",
                                    "picurl" => "https://www.baidu.com/img/bd_logo1.png"
                                ],
                                [
                                    "title" => "百度一下，你可能不知道",
                                    "description" => "This is two news title description",
                                    "url" => "http://www.baidu.com",
                                    "picurl" => "https://www.baidu.com/img/bd_logo1.png"
                                ]
                            ]
                        ]
                    ];
                break;

            default:
                break;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
        $resultData = json_decode(https_request($url,$arr,true),true);
        if ($resultData['errcode']){
            return true;
        }
    }

}