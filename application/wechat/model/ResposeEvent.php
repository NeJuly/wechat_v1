<?php
/**
 * Created by Maiya.
 * User: Junely
 * Date: 2018/7/4
 * Time: 23:12
 */

namespace app\wechat\model;


use think\Model;

class ResposeEvent extends Model
{
    /**
     * 回复文本消息
     * @param $postObj
     * @param $content
     * @return string
     */
    public function responseText($postObj,$content)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $time = time();
        $msgType = 'text';
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $content);
        return $resultStr;
    }

    /**
     *回复图文消息
     * @param $postObj
     * @param $articleCount
     * @return string
     */
    public function responseNews($postObj,$articleCount)
    {
        $textTpl = "
                    <xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <ArticleCount>%s</ArticleCount>
                    <Articles>";
        for ($i = 1; $i <= $articleCount; $i++) {
            $textTpl .= '<item>
                        <Title><![CDATA[第' . $i . '张图]]></Title> 
                        <Description><![CDATA[第' . $i . '张图的描述]]></Description>
                        <PicUrl><![CDATA[https://www.baidu.com/img/bd_logo1.png]]></PicUrl>
                        <Url><![CDATA[http://www.baidu.com]]></Url>
                        </item>';
        }
        $textTpl .= '</Articles>
                     </xml>';
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $time = time();
        $msgType = 'news';
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $articleCount);
        return $resultStr;
    }

    /**
     * 回复图片消息
     * @param $postObj
     * @param $mediaId
     * @return string
     */
    public function responseImg($postObj,$mediaId)
    {
        /*<xml>
        <ToUserName>< ![CDATA[toUser] ]></ToUserName>
        <FromUserName>< ![CDATA[fromUser] ]></FromUserName>
        <CreateTime>12345678</CreateTime>
        <MsgType>< ![CDATA[image] ]></MsgType>
        <Image>
        <MediaId>< ![CDATA[media_id] ]></MediaId>
        </Image>
        </xml>*/
        $tpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Image>
        <MediaId><![CDATA[%s]]></MediaId>
        </Image>
        </xml>";
        $toUsername = $postObj->ToUserName;
        $fromUsername = $postObj->FromUserName;
        $time = time();
        $msgType = 'image';
        $resultStr = sprintf($tpl,$fromUsername,$toUsername,$time,$msgType,$mediaId);
        return $resultStr;
    }


}