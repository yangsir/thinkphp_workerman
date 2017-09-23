<?php
namespace Cli\Controller;

use Workerman\Worker;

/**
 * 用户信息查询
 */
class WorkermanController{

    public function __construct()
    {
        $this->logPath = APP_PATH . 'Runtime/Logs/Cli/workerman.logs';
    }

    /**
     * 用户信息查询
     */
    public function index(){

        if(!IS_CLI){
            die("access illegal");
        }

        require_once APP_PATH.'Workerman/Autoloader.php';

        Worker::$daemonize = true;//以守护进程运行
        Worker::$pidFile = '/data/logs/Worker/workerman.pid';//方便监控WorkerMan进程状态
        Worker::$stdoutFile = '/data/logs/Worker/stdout.log';//输出日志, 如echo，var_dump等
        Worker::$logFile = '/data/logs/Worker/workerman.log';//workerman自身相关的日志，包括启动、停止等,不包含任何业务日志

        //$worker = new Worker('JsonNL://0.0.0.0:10986');//此处我使用内网ip
        $worker = new Worker('JsonNL://127.0.0.1:10986');//此处我使用内网ip
        $worker->name = 'Worker';
        $worker->count = 32;
        //$worker->transport = 'udp';// 使用udp协议，默认TCP
        $worker->onWorkerStart = function($worker){
            echo "Worker starting...\n";
        };
        $worker->onMessage = function($connection, $data){
            // 判断数据是否正确
            if(empty($data['class']) || empty($data['method']) || !isset($data['param_array']))
            {
                // 发送数据给客户端，请求包错误
                return $connection->send(array('code'=>400, 'msg'=>'bad request', 'data'=>null));
            }
            // 获得要调用的类、方法、及参数
            $class = $data['class'];
            $method = $data['method'];
            $param_array = $data['param_array'];

            // 判断类对应文件是否载入
            if(!class_exists($class))
            {
                $include_file = __DIR__ . "/WmServices/$class.php";
                if(is_file($include_file))
                {
                    require_once $include_file;
                }
                $className = '\Cli\Controller\WmServices\\'.$class;
                if(!class_exists($className))
                {
                    $code = 404;
                    $msg = $include_file.", class $className not found";
                    // 发送数据给客户端 类不存在
                    return $connection->send(array('code'=>$code, 'msg'=>$msg, 'data'=>null));
                }
            }

            // 调用类的方法
            try
            {
                $ret = call_user_func_array(array($className, $method), $param_array);
                if($ret['status'] == 0)
                {
                    //处理失败，记录log
                    $this->addLog('Class : '. $class.', method:'. $method.', params:'.json_encode($param_array).', err_message:'.$ret['message']);
                }
                //每次调用之后关闭数据库链接
                M()->close();
                // 发送数据给客户端，调用成功，data下标对应的元素即为调用结果
                return $connection->send(array('code'=>0, 'msg'=>'ok', 'data'=>$ret));
            }
                // 有异常
            catch(Exception $e)
            {
                // 发送数据给客户端，发生异常，调用失败
                $code = $e->getCode() ? $e->getCode() : 500;
                $this->addLog('Class : '. $class.', method:'. $method.', params:'.json_encode($param_array).', err_message:'.$e->getMessage());
                //每次调用之后关闭数据库链接
                M()->close();
                return $connection->send(array('code'=>$code, 'msg'=>$e->getMessage(), 'data'=>$e));
            }

        };
        $worker->onBufferFull = function($connection){
            echo "bufferFull and do not send again\n";
        };
        $worker->onBufferDrain = function($connection){
            echo "buffer drain and continue send\n";
        };
        $worker->onWorkerStop = function($worker){
            echo "Worker stopping...\n";
        };
        $worker->onError = function($connection, $code, $msg){
            echo "error $code $msg\n";
        };
        // 运行worker
        Worker::runAll();
    }


    protected function addLog($message, $type = 'ERROR', $exit = false)
    {
        file_put_contents($this->logPath, date('Y-m-d H:i:s').' '.$type.':'.$message.PHP_EOL, FILE_APPEND);
        if($exit)
        {
            exit;
        }
    }
}
