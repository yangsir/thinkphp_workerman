<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {

    public function index() {
        $this->show('Thinkphp集成Workerman，更多请关注：<a href="http://webyang.net">webyang.net</a>','utf-8');
    }

    
    public function test() {

        $uid = 1;
        \Home\Library\RpcClient::config(C('RPC_ADDRESS'));
        $rpcClient = \Home\Library\RpcClient::instance('User');
        //var_dump($rpcClient);exit;

        // ==同步调用==
        $return = $rpcClient->getName($uid);
        var_dump($return);

        // ==异步调用==
        $return = $rpcClient->asend_getName($uid);
        var_dump($return);

    }

}