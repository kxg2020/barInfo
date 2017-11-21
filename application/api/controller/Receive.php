<?php
namespace app\api\controller;

use think\Controller;
use think\Log;

class Receive extends Controller{


    public function indexAction(){

        $param = request()->get();

        switch ($param['usertype']){
            case 1:
                $this->UserReceive($param);
                break;

            case 2:
                $this->StaffReceive($param);
                break;
        }
    }

    private function UserReceive($data){

        $redis = getRedis();

        // 获取用户当前的客服ID
        $StaffId = $redis->get($data['loginno'].'-current-service-staff');
        // 获取当前队列中的最新消息
        $NewFile = json_decode($redis->rPop('staff-'.$StaffId.'-say-to-user'.$data['loginno'].'-expression'),true);
Log::record(['图片消息1'=>json_encode($NewFile)]);
        if($NewFile){
            $File = [
                'file_name'=>$NewFile['pic_name'],
                'download_url'=>URL.'/api/file/downLoadFile?filepath='.$NewFile['savepath'].'&filename='.$NewFile['savename'],
                'file_from'=>$NewFile['file_from'],
                'file_to'=>$NewFile['file_to'],
            ];

            die(json_encode($File,JSON_UNESCAPED_UNICODE));
        }else{

            die(json_encode(['status'=>'failed']));
        }
    }

    private function StaffReceive($data){

        $redis = getRedis();

        // 获取当前客服的最新消息队列中的消息
        $NewFile = json_decode($redis->rPop('staff-'.$data['loginno'].'-lastest-expression'),true);
        Log::record(['图片消息2'=>json_encode($NewFile)]);
        if($NewFile){

            $File = [
                'file_name'=>$NewFile['pic_name'],
                'download_url'=>URL.'/api/file/downLoadFile?filepath='.$NewFile['savepath'].'&filename='.$NewFile['savename'],
                'file_from'=>$NewFile['file_from'],
                'file_to'=>$NewFile['file_to'],
            ];

            die(json_encode($File,JSON_UNESCAPED_UNICODE));
        }else{

            die(json_encode(['status'=>'failed']));
        }
    }

}