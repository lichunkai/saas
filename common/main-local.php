<?php
return [
    'components' => [
	    'db' => [
		    'class' => 'yii\db\Connection',
		    //'dsn' => 'mysql:host=192.168.0.200;dbname=saas_db',//修改数据
			'dsn' => 'mysql:host=127.0.0.1;dbname=test_saas_db',//修改数据
		    'username' => 'root',			
		    'password' => 'Yjk123456!', 
			//'password' => 'root',
		    'charset' => 'utf8',
	    ],
        'web_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.0.200;dbname=fangbb',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],
        //  上传，新添加的
        'Upload' => [
            'class' => 'common\components\Upload'
        ],
        //数据组装
        'LoadData' => [
            'class' => 'common\components\LoadData'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
	    'language' => 'zh-CN',
    ],
];
