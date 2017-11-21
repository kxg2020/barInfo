<?php
namespace app\api\controller;

use think\Controller;
use think\Log;

class Ex extends Controller{

    public function indexAction(){

        $get = request()->get();

        switch ($get['index']){

            // 请求切换客服
            case "exchange_staff":

                $this->GetStaffInfo();
                break;
            // 选择客服列表
            case "select_staff":

                $this->SelectStaff($get);
                break;
        }
    }

    // 获取客服详细信息
    public function GetStaffInfo(){

        $redis = getRedis();

        // 获取所有登录的客服
        $allStaff = $redis->hGetAll('staffs');

        foreach ($allStaff as $key => &$value){

            $allStaff[$key] = json_decode($value,true);
        }
        unset($value);

        // 获取所有客服队列的长度
        $queueLengthArr = [];
        foreach ($allStaff as $key => $value){

            // 根据staff_id 查询队列
            $length = $redis->lLen('staff-service-order-'.$value['staff_id']);

            // 如果服务人数不满4人,表示可以继续服务
            if($length < 4){

                $queueLengthArr[] = $value['staff_id'];
            }
        }

        //判断是否有可以服务的客服
        if(!empty($queueLengthArr)){

            $staff = [];
            foreach ($queueLengthArr as $key => $value){

                $staff[] = json_decode($redis->hGet('staffs',$value),true);
            }

            die(json_encode($staff,JSON_UNESCAPED_UNICODE));

        }else{

            // 将当前客服返回给客户端
            die(json_encode(['status'=>'failed']));
        }

    }

    // 切换客服
    public function SelectStaff($data){

        $redis = getRedis();

        // 将当前客服的当前用户删除
        $redis->lRem('staff-service-order-'.$data['loginno'],$data['bar_id'],0);

        // 切换用户当前客服
        $redis->set($data['bar_id'].'-current-service-staff',$data['staff_id']);

        // 将当前用户放入新客服的队列
        $redis->lPush('staff-service-order-'.$data['staff_id'],$data['bar_id']);

        // 客服新用户
        $redis->lPush('staff-'.$data['staff_id'].'-new-service-user',$data['bar_id']);

        // 将新客服的服务人数加一
        $res = $redis->hGet('staff-'.$data['staff_id'].'-all-serviced-user-number',date('Y-m-d',time()));
        $redis->hSet('staff-'.$data['staff_id'].'-all-serviced-user-number',date('Y-m-d',time()),$res + 1);

        // 获取当前用户与之前客服的聊天消息
        $length = $redis->lLen('user-'.$data['bar_id'].'-with-staff-'.$data['loginno']);


        for ($i = 0;$i < $length ;++ $i){

            $content = json_decode($redis->rPop('user-'.$data['bar_id'].'-with-staff-'.$data['loginno']),true);
Log::record(['消息'=>$content]);
        // 将消息放入新客服和用户的对话队列
            $redis->lPush('staff-'.$data['staff_id'].'-lastest-content',json_encode(['chat_content'=>$content['chat_content'],'chat_from'=>$content['chat_from'],'chat_to'=>$data['staff_id'],'type'=>'exchange','chat_object'=>$content['chat_object'],'chat_time'=>$content['chat_time']],JSON_UNESCAPED_UNICODE));


        // 将消息放入历史对话记录中
            $redis->lPush('user-'.$data['bar_id'].'-with-staff-'.$data['staff_id'],json_encode(['chat_content'=>$content['chat_content'],'chat_from'=>$content['chat_from'],'chat_to'=>$data['staff_id'],'type'=>'exchange','chat_object'=>$content['chat_object'],'chat_time'=>$content['chat_time']],JSON_UNESCAPED_UNICODE));
        }


        // 向用户的消息队列中加入新消息
        $redis->lPush('staff-'.$data['staff_id'].'-say-to-user-'.$data['bar_id'],json_encode(['chat_content'=>'当前为您服务的客服:'.$data['staff_id'],'chat_from'=>$data['staff_id'],'chat_to'=>$data['bar_id'],'type'=>'exchange','chat_object'=>4,'chat_time'=>date('Y-m-d H:i:s',time())]));

    }
}