<?php
namespace app\wechat\controller;

use app\common\controller\Base;
use app\wechat\model\ResposeEvent;
use app\wechat\model\ServiceMessage;
use app\wechat\model\Template;
use think\Exception;

class Index extends Base
{
    public $validate_token = 'weixin';
    /**
     * @throws Exception
     */
    public function index()
    {
        $this->valid();
//        $this->responseMsg();

    }
    /**
     * @throws Exception
     */
    public function valid()
    {
        $echoStr = input("echostr");

        //valid signature , option
        //判定
        if($this->checkSignature()  && $echoStr){
            echo $echoStr;
            exit;
        }else{
            $this->responseMsg();
        }
    }
    /**
     * @return bool
     * @throws Exception
     */
    private function checkSignature()
    {
        // you must define TOKEN by yourself
//        if (!defined("TOKEN")) {
//            throw new Exception('TOKEN is not defined!');
//        }

        $signature = input("signature");
        $timestamp = input("timestamp");
        $nonce = input("nonce");

        $token = $this->validate_token;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule  形成数组按照字典序排序
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );   //通过sha1加密

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        //获取微信推送过来的post数据（xml格式）
        $postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
        //1.接受普通消息以及被动回复消息
        $this->getUserMessage($postStr);
        //2.消息事件推送
        $this->getEvent($postStr);

    }

    /**
     * 接受普通消息以及被动回复消息
     * @param $postStr
     */
    public function getUserMessage($postStr)
    {
        if (!empty($postStr)) {
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr);
            $msgType = $postObj->MsgType;
            switch ($msgType) {
                case 'text':
                    $respose_event_model = new ResposeEvent();
                    if (strtolower(trim($postObj->Content)) == 'pic'){
                        $result = $respose_event_model->responseNews($postObj,4);
                    }else{
                        $contentStr = "您随便输入了一段话";
                        $result = $respose_event_model->responseText($postObj,$contentStr);
                    }
                    echo $result;
                    break;
                case 'image':
                    $mediaId = $postObj->MediaId;
                    $respose_event_model = new ResposeEvent();
                    $result = $respose_event_model->responseImg($postObj,$mediaId);
                    echo $result;
                    break;
                case 'voice':
                    $contentStr = "您输入的是语音！";
                    $respose_event_model = new ResposeEvent();
                    $result = $respose_event_model->responseText($postObj,$contentStr);
                    echo $result;
                    break;
                default:
                    echo "Input something...";
                    break;
            }
        }else {
            echo "";
            exit;
        }
    }

    /**
     * 事件消息推送
     * @param $postStr
     */
    public function getEvent($postStr)
    {

        //判断该数据包是否是订阅的事件推送
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postStr);

        if($postObj->MsgType == 'event') {
            //如果是关注 subscribe 事件
            if (strtolower($postObj->Event) == 'subscribe') {
                //回复用户消息(纯文本格式)、
                $content = '欢迎关注我们的微信公众账号' . $postObj->FromUserName . '-' . $postObj->ToUserName;
                $respose_event_model = new ResposeEvent();
                $result = $respose_event_model->responseText($postObj,$content);
                echo $result;
                /**
                 * 用户关注自动回复文本消息
                 */
                $service_message_model = new ServiceMessage();
                $service_message_model->serviceData($postObj,$this->access_token);
                /**
                 * 用户关注自动回复图文消息
                 */
                $service_message_model->serviceData($postObj,$this->access_token,'news');

            }
            if (strtolower($postObj->Event == 'unsubscribe')){
                //这里进行数据库的操作。如果用户取消关注，对项目中数据库的内容进行调整
            }
            if (strtolower($postObj->Event) == 'click'){
                $respose_event_model = new ResposeEvent();
                switch ($postObj->EventKey){
                    case 'TODAY_MUSIC':
                        /**点击菜单拉取消息时的事件推送*/
                        $contentStr = "今天火爆音乐1->{$postObj->ToUserName}->{$postObj->FromUserName}";
                        $result = $respose_event_model->responseText($postObj,$contentStr);
                        break;
                    case 'GOOD':
                        /**点击菜单拉取消息时的事件推送*/
                        $contentStr = "别点赞-{$postObj->ToUserName}->{$postObj->FromUserName}";
                        $result = $respose_event_model->responseText($postObj,$contentStr);
                        break;
                    case 'show_picture':
                        /**点击菜单拉取消息时的事件推送*/
                        $contentStr = "展示图片->{$postObj->ToUserName}->{$postObj->FromUserName}";
                        $result = $respose_event_model->responseText($postObj,$contentStr);
                        break;
                    default:
                        break;
                }
                echo $result;
            }

        }
    }

    public function test()
    {
        $template_model = new Template();
//        $result = $template_model->getTemplateId($this->access_token);
//        $result = $template_model->getTemplateList($this->access_token);
        $result = $template_model->setIndustry($this->access_token);
        halt($result);
    }

    public function https_request( $url,array $data = [], $is_json = false,array $header = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
        if ($is_json){
        $data = json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $json_header = [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
        ];
        if (!empty($header)){
        $header = array_merge($json_header,$header);
        }else{
            $header = $json_header;
        }
        }
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        if (!empty($header)){
            curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}
