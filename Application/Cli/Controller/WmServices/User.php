<?php
namespace Cli\Controller\WmServices;

class User
{
    public function getName($id)
    {
        $result = array(
            'status'  => 0,
            'message' => '',
        );

        if($id) {
            $result = array(
                'status'  => 1,
                'name'    => 'webyang.net',
                'message' => '',
            );
        }

        \Think\log::record('entera:'.$id);

        return $result;
    }

}
