<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 17-8-26
 * Time: 下午8:50
 */
return array(
    'composer' => array(
        'Logger' => function (array $params) {
            return new \Monolog\Logger($params[0]);      //这里测试composer的加载
        }
    ),
    'addComponentsMap' => array(
        'validate' => 'framework\\components\\validate\\Validate',
        'page' => 'framework\\components\\page\\Page',//如果使用api的话这里不需要
        'view' => 'framework\\components\\view\\View',     //如果使用api的话这里不需要,
        'upload' => 'framework\\components\\upload\\Upload',
        'msgTask' => 'application1\\conf\\Task',
        'crontabTask' => 'application1\\conf\\CrontabTask',
//        'sessionRedis' => 'framework\\components\\cache\\Redis',
//        'session' => 'framework\\components\\session\\Session',
        'cookie' => 'framework\\components\\cookie\\SwooleCookie',
        'captcha' => 'framework\\components\\captcha\\Captcha',
        'crontab' => 'framework\\crontab\\Crontab'
    ), //该项因为设计上的问题暂时不添加
    'components' => array(
        'redis' => array(
            'host'         => '127.0.0.1', // redis主机
            'port'         => 6379, // redis端口
            'password'     => '', // 密码
            'select'       => 0, // 操作库
            'expire'       => 3600, // 有效期(秒)
            'timeout'      => 0, // 超时时间(秒)
            'persistent'   => false, // 是否长连接,
            'prefix' => ''
        ),
//        'sessionRedis' => array(
//            'host'         => '127.0.0.1', // redis主机
//            'port'         => 6379, // redis端口
//            'password'     => '', // 密码
//            'select'       => 0, // 操作库
//            'expire'       => 3600, // 有效期(秒)
//            'timeout'      => 0, // 超时时间(秒)
//            'persistent'   => true, // 是否长连接,
//            'prefix' => ''
//        ),
        'db' => array(
            'db' => array(
                'db1' => array(
                    'type' => 'mysql',
                    'dbName' => 'test',
                    'user' => 'root',
                    'password' => '123456',
                    'host' => 'localhost:3306',
                    'persistent' => true
                )
            )
        ),
//        'session' => array(
//            'redis' => array(
//                'session_name' => '', // sessionkey前缀
//            ),
//            'httpOnly'=> true,
//            'driver'=> array(
//                'type' => 'redis',
//                'name' => 'sessionRedis'
//            ),
//            'path'=> '',
//            'name' => 'EASYSESSION',
//            'prefix' => 'easy-'
//        ),
        'server' => array(
            'event' => 'application1\\conf\\ServerWebSocketEvent',
            'ip' => '127.0.0.1',
            'port' => '86',
            'supportHttp' => true
        ),
        'upload' => array(
            'accept' => array(
                'jpg',
                'png'
            )
        ),
        'captcha' => array(
            'height' => 70,
            'width' => 200,
            'num' => 5,
            'type' => 'png'   //png jpg gif
        ),
        'crontab' => array(
            'tasks' => array(
                '* * * 8-12 *--crontabTask test',
                '2 /2 3-8 3,5 1--crontabTask test',
                '3 /2 3-8/2 3,5 1--crontabTask test'
            )
        )
    )
);