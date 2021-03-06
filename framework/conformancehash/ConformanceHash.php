<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 18-4-22
 * Time: 下午7:36
 */

namespace framework\conformancehash;
class ConformanceHash extends DoubleList
{
    protected $_nodeList = [];

    public function addNode(Node $node)
    {
        if ($this->nodeExists($node)) {
            return false;
        }
        if(!parent::addNode($node)){
            return false;
        } // TODO: Change the autogenerated stub

        $this->_nodeList[] = $node->_value;
    }

    protected function nodeExists(Node $node)
    {
        if (in_array($node->_value, $this->_nodeList)) {
            return true;
        }

        return false;
    }

    protected function removeNodeList($key)
    {
        $key = array_search($key, $this->_nodeList);
        if ($key) {
            unset($this->_nodeList[$key]);
        }
    }

    public function removeNode(Node $node, $isParent = true)
    {
        if (!parent::removeNode($node)) {
            return false;
        } // TODO: Change the autogenerated stub

        $this->removeNodeList($node->_value);

        if ($isParent) {
            $num = $this->removeVirtualNodes($node->_value);
            $this->addVirtualNode($num);
        }
    }

    protected function removeVirtualNodes($p_node)
    {
        if ($this->_length == 1) {
            return false;
        }

        $num = 0;
        $node = $this->_node;
        while (true) {
            if ($node->_next->_isFirst) {
                break;
            }

            if ($node->_isVirtual && $node->_pValue == $p_node) {
                ++$num;
                $this->removeNode($node, false);
            }
            $node = $node->_next;
        }

        return $num;
    }

    public function addVirtualNode($num)
    {
//        随机一个node开始
        $cur = mt_rand(0, $this->_length);
        $node = $this->findNode($this->_nodeList[$cur]);
        while ($num > 0) {
            if (!$node->_isVirtual) {
                $_node = $node->cloneVN();
                $this->addNode($_node);
                unset($_node);
                --$num;
            }
            $node = $node->_next;
        }
    }

    public function findNextNodeByValue($value)
    {
        if ($this->_length == 1) {
            return $this->_node;
        }

        $value = crc32($value) % (2 << 32);
        $node = $this->_node;
        while ($node) {
            if ($node->_next->_isFirst) {
                return $node->_next;
            }
            if ($value >= $node->_value && $value < $node->_next->_value) {
                return $node->_next;
            } else {
                $node = $node->_next;
            }
        }

        return $node;
    }
}