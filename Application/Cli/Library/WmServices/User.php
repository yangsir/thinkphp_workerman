<?php
/**
 * workermen启动控制器
 * author webyang.net
 */
namespace Cli\Controller\WmServices;

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
        }

        return $result;
    }
}
