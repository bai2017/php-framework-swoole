<?php
namespace framework\components\url;
use framework\base\Component;

class Url extends Component
{

    protected $_defaultType;
    protected $_currentModule;
    protected $_curRoute = [];

    public function run()
    {
        $this->_currentModule = $this->formatUrl();
        return $this->_currentModule;
    }

    public function getCurrentModule()
    {
        return $this->_currentModule;
    }

    protected function formatUrl()
    {
        $type = $this->getType();
        if ($type === '?')
        {
            $system = $_GET[$this->getValueFromConf('systemKey', 's')] ?? '';
            if (!empty($system)) {
                if (!in_array($system, $this->getValueFromConf('systems',[]))) {
                    $this->triggerThrowable(new \Exception('app ' . $system . ' not found', 404));
                }
            } else {
                $system = $this->getValueFromConf('defaultSystem');
            }


            $urlInfo =  array(
                'system' => $system,
                'controller' => empty($_GET[$this->getValueFromConf('controllerKey', 'm')]) ? $this->getValueFromConf('defaultController', 'index') : $_GET[$this->getValueFromConf('controllerKey', 'm')],
                'action' => empty($_GET[$this->getValueFromConf('actionKey', 'act')]) ? $this->getValueFromConf('defaultAction', 'index') : $_GET[$this->getValueFromConf('actionKey', 'act')]
            );
        }
        else
        {
            $routerKey = $this->getValueFromConf('routerKey');
            if ($routerKey) {
                $query = empty($_GET[$routerKey]) ? '' : $_GET[$routerKey];
            } else {
                $query = $this->getPathInfo();
                $query = ltrim($query,'/');
            }


            $tmpQuery = explode($this->getValueFromConf('separator', '/'), $query);
            if (!empty($tmpQuery[0]) && $tmpQuery[0] === 'favicon.ico') {
                return false;
            }


            $keyStart = 0;
            if (in_array($tmpQuery[0], $this->getValueFromConf('systems',[]))) {
                $system = $tmpQuery[0];
                unset($tmpQuery[0]);
                $keyStart = 1;
            } else {
                $system = $this->getValueFromConf('defaultSystem');
            }


            $urlInfo =  array(
                'system' => $system,
                'controller' => empty($tmpQuery[0 + $keyStart]) ? $this->getValueFromConf('defaultController', 'index') : $tmpQuery[0 + $keyStart],
                'action' => empty($tmpQuery[1 + $keyStart]) ? $this->getValueFromConf('defaultAction', 'index') : $tmpQuery[1 + $keyStart]
            );
            $count = count($tmpQuery);
            for($i=2 + $keyStart;$i < $count; $i+=2)
            {
                $_GET[$tmpQuery[$i]] = !isset($tmpQuery[$i+1]) ?  '' : $tmpQuery[$i+1];
            }


            unset($tmpQuery);
        }


        $this->_curRoute = $urlInfo;
        unset($urlInfo);

        return $this->_curRoute;
    }

    public function getPathInfo()
    {
        return $_SERVER['PATH_INFO'] ?? '';
    }

    public function getCurRoute()
    {
        return $this->_curRoute;
    }

    public function getType()
    {
        if(!$this->_defaultType)
        {
            $this->_defaultType = $this->getValueFromConf('type', '?');

            if(!in_array($this->_defaultType,array('/','?'))) {
                $this->_defaultType = '?';
            }
        }
        return $this->_defaultType;
    }


    public function createUrl($url)
    {
        $tmpUrl = $_SERVER['HTTP_HOST'] . $_SERVER['URL'] . '?';
        if ($this->getType() === '?')
        {
            if(is_array($url))
            {
                foreach ($url as $key=>$item)
                {
                    $tmpUrl .= $key . '=' . $item . '&';
                }
                $tmpUrl = trim($tmpUrl, '&');
            }
            else
            {
                $tmpUrl .= $url;
            }
        }
        else
        {
            $tmpUrl .= $url;
        }

        unset($urlModule, $url);
        return $tmpUrl;
    }
}