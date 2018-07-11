<?php
/**
 * Created by Maiya.
 * User: Junely
 * Date: 2018/7/9
 * Time: 15:01
 */

namespace app\wechat\model;


use think\Model;

class Subscribe extends Model
{

    public function sendTemplate($appid)
    {
        /*第一步：需要用户同意授权，获取一次给用户推送一条订阅模板消息的机会
        https://mp.weixin.qq.com/mp/subscribemsg?action=get_confirm&appid=wxaba38c7f163da69b&scene=1000&template_id=1uDxHNXwYQfBmXOfPJcjAS3FynHArD8aWMEFNRGSbCc&redirect_url=http%3a%2f%2fsupport.qq.com&reserved=test#wechat_redirect*/

    }
}