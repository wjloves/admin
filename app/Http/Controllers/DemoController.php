<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Article;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Voice;

class DemoController extends Controller
{
    private $app;

    public function __construct()
    {
        $wechatArr = [
            '1' => 'demo_option', //公众号测试账号
            '2' => 'zhaopin_option', //PHP招聘,这里可以多账号
        ];
        $options   = config('wechat');
        $this->app = new Application($options);
    }

    /**
     * 服务端示例.
     *
     * @throws \EasyWeChat\Core\Exceptions\InvalidArgumentException
     */
    public function server()
    {
        $app    = $this->app;
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            $toUserName = $message->ToUserName;//接收方帐号（该公众号 ID）
            $fromUserName = $message->FromUserName;//发送方帐号（OpenID, 代表用户的唯一标识）
            $createTime = $message->CreateTime;//消息创建时间（时间戳）
            $msgID = $message->MsgId;//消息 ID（64位整型）
            $text = '发送方帐号:'.$fromUserName."\n";
            $text .= '接收方帐号:'.$toUserName."\n";
            $text .= '消息ID:'.$msgID."\n";
            $text .= '时间:'.date('Y-m-d H:i:s', $createTime)."\n";
            switch ($message->MsgType) {

                //文字消息
                case 'text':
                    $gettext = $message->Content;
                    if ($gettext == '图文') {
                        //回复图文信息
                        $news = new News([
                            'title' => '回复图文信息',
                            'description' => '测试回复图文信息',
                            'url' => 'http://www.baidu.com',
                            'image' => 'http://zdy.bfimg.com/img/20160311/180*240_1457662632.jpg',
                            // ...
                        ]);

                        return [$news, $news, $news, $news, $news, $news];
                    } elseif ($gettext == '文章') {
                        //未测成功
                        $art = new Article([
                            'title' => 'EasyWeChat', //标题
                            'author' => 'overtrue', //作者
                            'content' => 'EasyWeChat 是一个开源的微信 SDK', //具体内容
                            'source_url' => 'http://www.baidu.com', //来源 URL
                            'show_cover' => 0, //是否显示封面，0 为 false，即不显示，1 为 true，即显示
                            'digest' => '', //图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空
                            //thumb_media_id 图文消息的封面图片素材id（必须是永久mediaID）
                        ]);

                        return $art;
                    } else {
                        if (in_array($gettext, get_class_methods($this))) {
                            return '成功调用'.$gettext.'方法'.$this->$gettext();
                        }

                        return $text.'文字消息：'.$gettext;
                    }
                    break;
                //图片消息
                case 'image':
                    $mediaId = $message->MediaId;//图片媒体id，可以调用多媒体文件下载接口拉取数据。
                    $img = new Image(['media_id' => $mediaId]);
                    //下载到本地
                    //file_put_contents(storage_path('logs')."/{$mediaId}.jpg", file_get_contents($message->PicUrl));

                    return $text.'图片链接：'.$message->PicUrl."\n 图片媒体ID：".$mediaId;
                    //return $img;//回复图片
                    break;
                //语音消息
                case 'voice':
                    //请注意，开通语音识别后，用户每次发送语音给公众号时，微信会在推送的语音消息XML数据包中，增加一个 `Recongnition` 字段
                    $mediaId = $message->MediaId;//语音消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $format = $message->Format;//语音格式，如 amr，speex 等
                    $recongnition = $message->Recongnition;//开通语音识别后才有,未测试成功
                    //return $text.'语音消息媒体id:'.$mediaId."\n".'语音格式:'.$format."\n".'识别后:'.$recongnition;
                    $voice = new Voice(['media_id' => $mediaId]);

                    return $voice;//回复语音
                    break;
                //小视频与视频消息
                case 'shortvideo':
                case 'video':
                    $mediaId = $message->MediaId;//视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
                    $thumbMediaId = $message->ThumbMediaId;//视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
                    return $text.'视频消息媒体id:'.$mediaId."\n".'视频消息缩略图的媒体id:'.$thumbMediaId;
                    break;
                //坐标消息(发送位置)
                case 'location':
                    $x = $message->Location_X;//地理位置纬度
                    $y = $message->Location_Y;//地理位置经度
                    $scale = $message->Scale;//地图缩放大小
                    $label = $message->Label;//地理位置信息
                    return $text."纬度：{$x}\n经度：{$y}\n地图缩放大小：{$scale}\n地理位置信息：{$label}";
                    break;
                //链接消息,未测成功
                case 'link':
                    $title = $message->Title;//消息标题
                    $desc = $message->Description;//消息描述
                    $url = $message->Url;//消息链接

                    return $text."消息标题：{$title}\n消息描述：{$desc}\n消息链接：{$url}";
                    break;
                //事件消息
                case 'event':
                    $event = $message->Event;//事件类型 （如：subscribe(订阅)、unsubscribe(取消订阅) ...）
                    switch ($event) {
                        //关注
                        case 'subscribe':
                            $line = $text."\n事件：关注";
                            break;
                        //取消关注
                        case 'unsubscribe':
                            $line = $text."\n事件：取消关注";
                            break;

                        //上报地理位置事件，比如每次打开时上报，自动上报的，用户基本不知道。可以用来服务端记录用户所在位置，分析用户组成。
                        case 'LOCATION':
                            $line = $text."\n事件：上报地理位置事件";
                            //$str = "地理位置纬度: ".$userMsg->Latitude."地理位置经度: ".$userMsg->Longitude."地理位置精度:".$userMsg->Precision;
                            break;

                        //用户自定义菜单里的发送位置事件
                        case 'location_select':
                            $line = $text."\n事件：用户自定义菜单里的发送位置事件";
                            //$str = "用户自定义菜单点击了发送位置，".$userMsg->EventKey." Label:".$userMsg->SendLocationInfo->Label." Location_X:".$userMsg->SendLocationInfo->Location_X." Location_Y:".$userMsg->SendLocationInfo->Location_Y;
                            break;

                        //点击菜单拉取消息时的事件推送
                        case 'CLICK':
                            $line = $text."\n事件：点击菜单拉取消息时的事件推送".$message->EventKey;

                            // if(in_array($userMsg->EventKey,array('JIN_REN_GE_QU','TI_JIAO_WANG_ZHI'))){
                            break;

                        //点击菜单跳转链接时的事件推送
                        case 'VIEW':
                            $line = $text."\n事件：点击菜单跳转链接时的事件推送".$message->EventKey;
                            break;

                        //扫码带提示
                        case 'scancode_waitmsg':
                            $line = $text."\n 事件：扫码带提示\nEventKey:".$message->EventKey;//.' 扫码结果'.$message->ScanCodeInfo->ScanResult;
                            break;

                        //扫码推事件，比如扫了一个公众号，则直接跳过去。
                        case 'scancode_push':
                            $line = $text."\n事件：扫码推事件";
                            //$str = "EventKey:".$userMsg->EventKey." 扫码结果".$userMsg->ScanCodeInfo->ScanResult;
                            break;

                        //拍照发图
                        case 'pic_sysphoto':
                            $line = $text."\n 事件：拍照发图";
                            //$str = "EventKey:".$userMsg->EventKey."拍照发图共:".$userMsg->SendPicsInfo->Count."张，";
                            break;

                        //拍照或者相册发图
                        case 'pic_photo_or_album':
                            $line = $text."\n事件：拍照或者相册发图";
                            //$str = "EventKey:".$userMsg->EventKey."拍照或者相册发图共:".$userMsg->SendPicsInfo->Count."张，";
                            break;

                        case 'pic_weixin':
                            $line = $text."\n事件：微信相册共发图共";
                            //$str = "EventKey:".$userMsg->EventKey."微信相册共发图共:".$userMsg->SendPicsInfo->Count."张，";
                            break;

                        default:
                            $line = $text."\n事件：未知事件".$event;
                            break;

                    }
                    //事件日志
                    if (isset($line)) {
                        error_log("===================================================\n".$line."\n", 3, storage_path('logs').'/wechat_event.log');
                    }

                    return '事件类型：'.$event;
                    break;
                //其它消息
                default:
                    return '其它消息'.$message->MsgType;
                    break;
            }
        });
        $server->serve()->send();
    }

    /**
     * oauth授权回调.
     */
    public function callback()
    {
        session_start();
        $app   = $this->app;
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();

        $_SESSION['wechat_user'] = $user->toArray();

        $targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];
        header('location:'.$targetUrl); // 跳转到 api/wechat/profile
    }

    /**
     * oauth授权后的用户信息.
     *
     * @return mixed
     */
    public function profile()
    {
        session_start();
        $app   = $this->app;
        $oauth = $app->oauth;

        // 未登录
        if (empty($_SESSION['wechat_user'])) {
            $_SESSION['target_url'] = '/api/wechat/profile';

            return $oauth->redirect();
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
             //$oauth->redirect()->send();
        }
        // 已经登录过
        $user = $_SESSION['wechat_user'];
        echo '<h3>个人资料</h3>';
        echo '<h5>OpenID:'.$user['id'].'</h5><br>';
        echo '<h5>Name:'.$user['name'].'</h5><br>';
        echo "头像:<img src='".$user['avatar']."'/><br>";
        echo '<h5>邮箱:'.$user['email'].'</h5><br>';
        echo '<h5>AccessToken:'.$user['token'].'</h5><br>';
    }

    /**
     * 菜单.
     *
     * @return \EasyWeChat\Support\Collection|string
     */
    private function menu()
    {
        $app   = $this->app;
        $menu  = $app->menu;
        $menus = $menu->all();//查询菜单
        //$menus = $menu->current();//自定义菜单

        return $menus;
    }

    /**
     * 添加菜单.
     *
     * @return bool
     */
    private function addmenu()
    {
        $app     = $this->app;
        $menu    = $app->menu;
        $buttons = [
            [
                'name' => 'Oauth示例',
                'sub_button' => [
                    [
                        'type' => 'view',
                        'name' => '个人信息',
                        'url' => 'http://www.xxxxxxxxx.com/api/wechat/profile',
                    ],
                    [
                        'type' => 'view',
                        'name' => 'jssdk',
                        'url' => 'http://www.xxxxxxxxx.com/api/wechat/jssdk',
                    ],
                    [
                        'type' => 'view',
                        'name' => 'weui',
                        'url' => 'http://www.xxxxxxxxx.com/api/wechat/weui',
                    ],
                    [
                        'type' => 'location_select',
                        'name' => '发送位置',
                        'key' => 'FA_SONG_WEI_ZHI',
                    ],
                ],
            ],
            [
                'name' => '网址导航',
                'sub_button' => [
                    [
                        'type' => 'view',
                        'name' => '暴风',
                        'url' => 'http://www.baofeng.com',
                    ],
                    [
                        'type' => 'click',
                        'name' => '提交网址',
                        'key' => 'TI_JIAO_WANG_ZHI',
                    ],
                ],
            ],
            [
                'name' => '扫码拍照',
                'sub_button' => [
                    [
                        'type' => 'scancode_waitmsg',
                        'name' => '扫码带提示',
                        'key' => 'SAO_MA_DAI_TI_SHI',
                    ],
                    [
                        'type' => 'scancode_push',
                        'name' => '扫码推事件',
                        'key' => 'SAO_MA_TUI_SHI_JIAN',
                    ],
                    [
                        'type' => 'pic_sysphoto',
                        'name' => '拍照发图',
                        'key' => 'PAI_ZHAO_FA_TU',
                    ],
                    [
                        'type' => 'pic_photo_or_album',
                        'name' => '拍照或者相册发图',
                        'key' => 'PA_ZHAO_HUOZHE_XIANGCHE_FATU',
                    ],
                    [
                        'type' => 'pic_weixin',
                        'name' => '微信相册发图',
                        'key' => 'WEIXIN_XIANGCHE_FATU',
                    ],
                ],
            ],
        ];
        //$menu->destroy(); // 全部删除菜单
        //$menu->destroy($menuId);//根据菜单 ID 来删除(删除个性化菜单时用，ID 从查询接口获取ID

        $menu->add($buttons);

        return '添加菜单成功。';
    }

/**
     * jssdk示例.
     *
     * @return mixed
     */
    public function jssdk()
    {
        $app = $this->app;
        $js  = $app->js;
        //$url = 'http://www.xxxxxxxxx.com/';
        //$js->setUrl($url);//设置当前URL，如果不想用默认读取的URL，可以使用此方法手动设置，通常不需要。
        $apiArr = [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'onMenuShareQZone',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'translateVoice',
            'startRecord',
            'stopRecord',
            'onVoiceRecordEnd',
            'playVoice',
            'onVoicePlayEnd',
            'pauseVoice',
            'stopVoice',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard',
        ];
        //获取JSSDK的配置数组，默认返回 JSON 字符串，当 $json 为 false 时返回数组，你可以直接使用到网页中。
        $jsconfig = $js->config($apiArr, $debug = true, $json = true);

        return view('api.wechat.jssdk', compact('jsconfig'));
    }

    /**
     * 长链接转短链接.
     *
     * @return string
     */
    private function shorturl()
    {
        $app = $this->app;
        $url = $app->url;
        //长链接转短链接
        $shortUrl = $url->shorten('http://www.baidu.com');

        return $shortUrl->short_url;
    }

    /**
     * 二维码生成
     * Bag temporary($sceneId, $expireSeconds = null) 创建临时二维码；
     *      $sceneId 场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000）
     * Bag forever($sceneValue) 创建永久二维码
     *     $sceneValue 场景值ID（字符串形式的ID），字符串类型，长度限制为1到64，仅永久二维码支持此字段
     * Bag card(array $card) 创建卡券二维码
     * string url($ticket) 获取二维码网址，用法： <img src="<?php $qrcode->url($qrTicket); ?>">；.
     *
     * @return string
     */
    private function qrcode()
    {
        $app    = $this->app;
        $qrcode = $app->qrcode;
        //创建临时二维码,最大过期时间为：30天
        $result        = $qrcode->temporary($sceneId = 56, $expireSeconds = 6 * 24 * 3600);
        $ticket        = $result->ticket;
        $expireSeconds = $result->expire_seconds; // 有效秒数
        // 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片
        $url = $result->url;
        //用法： <img src="{{$qrcode->url($qrTicket)}}">；
        return '<img src="'.$qrcode->url($ticket).'">';

        //创建永久二维码
        //$result = $qrcode->forever(56);// 或者 $qrcode->forever("foo");
        //$ticket = $result->ticket;
        //$url = $result->url;

        //$url = $qrcode->url($ticket);//获取二维码网址
        //$qrcode->card($card);//创建卡券二维码

        //获取二维码内容
        //$url = $qrcode->url($ticket);
        //$content = file_get_contents($url); // 得到二进制图片内容
        //file_put_contents(__DIR__ . '/code.jpg', $content); // 写入文件
    }
}