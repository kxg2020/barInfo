<?php
namespace app\api\controller;

use think\Controller;
use think\Log;

class Send extends Controller{

    public function indexAction(){

        // 获取参数
        $param = request()->get();

        switch ($param['usertype']){

            case 1:
                $this->UserSend($param);
                break;

            case 2:
                $this->StaffSend($param);
                break;
        }
    }

    // 用户发送表情
    public function UserSend($data){

        $redis = getRedis();

        // 保存图片到服务器
        $result = upload();
        Log::record(['数据1'=>json_encode($data)]);
        Log::record(['数据1-1'=>json_encode($result)]);
        $result['file_from'] = $data['loginno'];
        $result['file_to'] = $data['putto'];
        $result['pic_name'] = $data['pic_name'];

        // 将savepath后面的/号去掉
        $array = explode('/',$result['savepath']);

        $result['savepath'] = $array[0];

        // 往队列中添加一条几记录,代表这是一个图片或者截图
        $redis->lpush('staff-'.$data['putto'].'-lastest-expression',json_encode($result,JSON_UNESCAPED_UNICODE));


    }

    // 客服发送表情
    public function StaffSend($data){

        $redis = getRedis();
        Log::record(['数据2'=>json_encode($data)]);
        // 保存图片到服务器
        $result = upload();

        $result['file_from'] = $data['loginno'];
        $result['file_to'] = $data['putto'];
        $result['pic_name'] = $data['pic_name'];

        // 将savepath后面的/号去掉
        $array = explode('/',$result['savepath']);

        $result['savepath'] = $array[0];

        // 往队列中添加一条几记录,代表这是一个图片或者截图
        $redis->lpush('staff-'.$data['loginno'].'-say-to-user'.$data['putto'].'-expression',json_encode($result,JSON_UNESCAPED_UNICODE));


        die(json_encode(['status'=>'ok']));

    }
}