<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'admin_host' => 'http://admin.saas.com/',
    'img_host' => 'http://img.saas.com/',

    // 微信配置 具体可参考EasyWechat
    'wechatConfig' => [],

    // 微信支付配置 具体可参考EasyWechat
    'wechatPaymentConfig' => [
        // 必要配置
        'app_id'             => 'wxd1938c1a9065115f',
        'mch_id'             => '1500148512',
        'key'                => '21232f297a57a5a743894a0e4a801fc3',   // API 密钥
        // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
        'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
        'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！

        'notify_url'         => 'payment/notify',     // 你也可以在下单时单独设置来想覆盖它
    ],

    // 微信小程序配置 具体可参考EasyWechat
    'wechatMiniProgramConfig' => [],

    // 微信开放平台第三方平台配置 具体可参考EasyWechat
    'wechatOpenPlatformConfig' => [],

    // 微信企业微信配置 具体可参考EasyWechat
    'wechatWorkConfig' => [],

    //短信配置
    'sms'=>[
        'cr6868'=>[
            'name'=>'13951618000',
            'pwd'=>'A4F4352012FBD57F2777D8498314',
            'sign'=>'宜居客科技',
        ]
    ],
    //微信小程序配置
    'appid'=>'wxd32da4314da1d463',
    'secret'=>'d308fc23e1e08f8059a7852c4818b0f6',

    //房源采集的请求地址
    'collect_url'=>'http://caiji.gengleisz.com',
];
