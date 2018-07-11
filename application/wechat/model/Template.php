<?php
/**
 * Created by Maiya.
 * User: Junely
 * Date: 2018/7/9
 * Time: 15:05
 */

namespace app\wechat\model;


use think\Model;

class Template extends Model
{
    /**
     *设置所属行业
     */
    public function setIndustry($access_token)
    {
        /*http请求方式: POST
        https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=ACCESS_TOKEN*/
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token={$access_token}";
        $post_data = [
            "industry_id1"=>"1",
            "industry_id2"=>"4"
        ];
        $result_data = json_decode(https_request($url,$post_data),true);
        return $result_data;
    }
    /**
     * 获得模板ID
     */
    public function getTemplateId($access_token)
    {
        /*http请求方式: POST
        https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=ACCESS_TOKEN*/
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token={$access_token}";
        $post_data = [
                "template_id_short"=>"TM00015"
        ];
        $result_data = json_decode(https_request($url,$post_data,true),true);
        return $result_data;
    }
    /**
     * 获取模板列表
     */
    public function getTemplateList($access_token)
    {
        /*http请求方式：GET
        https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=ACCESS_TOKEN*/
        $url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token={$access_token}";
        $result_data = json_decode(https_request($url),true);
        return $result_data;
    }
}