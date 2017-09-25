<?php
/**
 * workermen功能测试类
 * author webyang.net
 */
namespace Cli\Library\WmServices;

class User
{
    public function getName($id)
    {
        $result = array(
            'status'  => 0,
            'message' => '请求失败',
        );

        if($id) {
            $result = array(
                'status'  => 1,
                'name'    => 'webyang.net',
                'message' => '',
            );

            //数据库调用
            //$arr = M('Users')->find($id);
            //$result['name'] = $arr['username'];
        }

        return $result;
    }
}
