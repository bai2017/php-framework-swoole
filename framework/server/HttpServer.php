<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 17-9-16
 * Time: 下午8:48
 */
namespace framework\server;

use framework\base\Container;

class HttpServer extends BaseServer
{
    protected function init()
    {
        $this->_server = new \swoole_http_server($this->_appConf['ip'], $this->_appConf['port']);
        parent::init(); // TODO: Change the autogenerated stub
        $this->onRequest();
    }

    public function onRequest()
    {
        $this->_server->on("request", function (\swoole_http_request $request,\swoole_http_response $response)
        {
            if (!empty($this->_event))
            {
                $this->_event->onRequest($request,$response);
            }
            $container = Container::getInstance();
            if (!empty($request->get)) {
                $_GET = $request->get;
            }
            if (!empty($request->post)) {
                $_POST = $request->post;
            }

            try
            {
                $request->server['host'] = $request->header['host'];
                $urlInfo = $container->getComponent('url')->run($request->server);
                if ($urlInfo !== false) {
                    $result = $container->getComponent('dispatcher')->run($urlInfo);
                    $container->getComponent('response')->send($response, $result);
                }
                if (!empty($this->_event))
                {
                    $this->_event->onResponse($request,$response);
                }
                $container->finish();
            }
            catch (\Exception $exception)
            {
                $response->status(404);
                $response->write($exception->getMessage());
                $container->getComponent('exception')->handleException($exception);
            }
            catch (\Error $e)
            {
                $response->status(404);
                $response->write($e->getMessage());
                $container->getComponent('exception')->handleException($e);
            }

            $response->end();
            $_GET = null;
            $_POST = null;
            unset($container,$request,$response);
        });
    }
}