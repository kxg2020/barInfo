<?php
namespace app\admin\controller;
use think\Db;
class Index extends Base {

    // 检测登录
    public function __construct()
    {
        parent::__construct();

        if($this->isLogin != 1){

            $this->redirect('login/index');
        }

        // 判断是否能够访问当前的控制器
        if (!in_array(strtolower(request()->controller()),$this->url)){

            abort(404,'您没有权限');
        };
    }

    public function indexAction(){

        $redis = getRedis();

        // 查询所有客服人数
        $staff = Db::table('xm_staffinfo')->field('staff_id,staff_name,handle_icon_path')->select();


        // 查询所有用户人数
        $user = Db::table('xm_webbaruser')->count();

        $data = [];

        //客服名字
        $staffName = [];

        // 查询客服的评论数
        $rateData = [];

        // 查询客服服务的人数
        foreach ($staff as $key => $value){

            $rateData[$value['staff_name']]['nice'] = 0;
            $rateData[$value['staff_name']]['all'] = 0;
            $rateData[$value['staff_name']]['icon'] =  $value['handle_icon_path'];

            $res = $redis->hGetAll('staff-'.$value['staff_id'].'-all-serviced-user-number');
            ksort($res);
            $data[$value['staff_id']] = $res;
            $staffName []= $value['staff_name'];

            $row = Db::table('xm_staff_grade')
                ->field('staff_id,is_solve_problem')
                ->where(['staff_id'=>$value['staff_id']])
                ->select();

            foreach ($row as $k => $v){

                $rateData[$value['staff_name']]['all'] += 1;


                if($v['is_solve_problem'] == 1){

                    $rateData[$value['staff_name']]['nice'] += 1;
                }
            }
        }

        // 好评率
        foreach ($rateData as $key => &$value){
            if($value['nice'] !== 0){

                $value['nice'] = bcdiv($value['nice'] , $value['all'],2) * 100;


            }
            unset($value['all']);

        }
        unset($value);

        $newDate = [];
        // 过去六天的日期
        for($i = 6;$i > 0 ;$i --){
            $newDate[] = date('Y-m-d',strtotime("-$i days"));
        }

        // 取出过去六天客服服务的人数
        $newData = [];
       foreach ($newDate as $key => $value){

           foreach ($data as $k => $v){

               if(isset($v[$value])){

                   $newData[$k][] = $v[$value];
               }else{

                   $newData[$k][] = '0';
               }
           }
       }
       // 组装数据
        $result = [];
        foreach ($staff as $key => $value){
            foreach ($newData as $k => $v){
                if($value['staff_id'] == $k){
                    $result[] = [
                        'data'=>$v,
                        'name'=>$value['staff_name'],
                        'type'=>'line',
                    ];
                }
            }
        }

        $this->assign([
            'staff'=>count($staff),
            'staff_name'=>json_encode($staffName,JSON_UNESCAPED_UNICODE),
            'staff_data'=>json_encode($result,JSON_UNESCAPED_UNICODE),
            'new_date'=>json_encode($newDate),
            'staff_color'=>json_encode(["#5BC0DE",'#F0AD4E','#D9534F','#060606']),
            'user'=>$user,
            'rateData'=>$rateData,
        ]);
        return view("index/index");
    }

    // 根据时间筛选
    public function SelectByDateAction(){

        $redis = getRedis();
        // 获取时间参数
        $param = request()->post();

        $date = [];

        // 生成日期
        for ($i = strtotime($param['start']);$i <= strtotime($param['end']);$i+=86400){

            $date[] = date('Y-m-d',$i);
        }

        // 查询所有客服人数
        $staff = Db::table('xm_staffinfo')->field('staff_id,staff_name')->select();

        $data = [];

        //客服名字
        $staffName = [];

        // 查询客服服务的人数
        foreach ($staff as $key => $value){

            $res = $redis->hGetAll('staff-'.$value['staff_id'].'-all-serviced-user-number');
            ksort($res);
            $data[$value['staff_id']] = $res;
            $staffName []= $value['staff_name'];
        }

        // 取出客服服务的人数
        $newData = [];
        foreach ($date as $key => $value){

            foreach ($data as $k => $v){

                if(isset($v[$value])){

                    $newData[$k][] = $v[$value];
                }else{

                    $newData[$k][] = '0';
                }
            }
        }
        // 组装数据
        $result = [];
        foreach ($staff as $key => $value){
            foreach ($newData as $k => $v){
                if($value['staff_id'] == $k){
                    $result[] = [
                        'data'=>$v,
                        'name'=>$value['staff_name'],
                        'type'=>'line',
                    ];
                }
            }
        }

        die(json_encode([
            'staff_name'=>$staffName,
            'staff_data'=>$result,
            'new_date'=>$date,
        ]));
    }

}