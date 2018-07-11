<?php
/**
 * Created by Maiya.
 * User: Junely
 * Date: 2018/7/4
 * Time: 15:45
 */

namespace app\wechat\controller;


use app\common\controller\Base;

class Menu extends Base
{
    /**
     * 创建菜单列表
     */
    public function index()
    {
        $menu = [
            "button"=>[
                [
                    "type"=>"click",
                    "name"=>"今天火爆音乐",
                    "key"=>"TODAY_MUSIC"
                ],
                [
                    'type'=>'click',
                    'name'=>'展示图片',
                    'key'=>'show_picture'
                ],
                [
                    "name"=>"菜单",
                    "sub_button"=>[
                        [
                            "type"=>"view",
                            "name"=>"搜索",
                            "url"=>"http://www.soso.com/"
                        ],
//                    [
//                        "type"=>"miniprogram",
//                        "name"=>"wxa",
//                        "url"=>"http=>//mp.weixin.qq.com",
//                        "appid"=>"wx286b93c14bbf93aa",
//                        "pagepath"=>"pages/lunar/resposeEvent"
//                    ],
                        [
                            "type"=>"click",
                            "name"=>"别点赞",
                            "key"=>"GOOD"
                        ]
                    ]
                ]
            ]
        ];
        $this->menuCreate($menu);
    }

    /**
     *自定义菜单创建接口
     */
    public function menuCreate($menu)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$this->access_token}";
        $result = https_request($url,$menu,true);
        halt($result);
    }
    /**
     * 自定义菜单查询接口
     */
    public function menuQuery()
    {
        /*http请求方式：GET
        https://api.weixin.qq.com/cgi-bin/menu/get?access_token=ACCESS_TOKEN*/
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$this->access_token}";
        $result = https_request($url);
        echo '<pre>';
        halt(json_decode($result,true));
    }
    /**
     *自定义菜单删除接口
     */
    public function menuDel()
    {
        /*http请求方式：GET
        https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=ACCESS_TOKEN*/
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$this->access_token}";
        $result = https_request($url);
        echo '<pre>';
        halt(json_decode($result,true));
    }
}