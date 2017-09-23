<?php
namespace Cli\Controller;
use Think\Controller;

class IndexController extends Controller {
    public function index(){
        echo 'cli testing~';
        \Think\log::record('nihao');
    }
}
