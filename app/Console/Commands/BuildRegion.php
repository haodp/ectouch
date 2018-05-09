<?php

namespace App\Console\Commands;

use App\Models\Region;
use Illuminate\Console\Command;

class BuildRegion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:region';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();
        $region = new Region();

//        // 创建国家
//        $cn = $region->where('parent_id', 0)->first();
//        if (is_null($cn)) {
//            $region->region_id = 1;
//            $region->parent_id = 0;
//            $region->region_name = '中国';
//            $region->region_type = 0;
//            $region->agency_id = 0;
//            $region->save();
//        }
//
//        // 采集省份信息
//        $this->getChildren(1, 1, $client);
//        //延迟 0.3ms
//        usleep(300000);
//
//        // 采集地级市
//        $province = $region->where('region_type', 1)->get();
//        foreach ($province as $vo) {
//            $this->getChildren($vo['region_id'], 2, $client);
//            usleep(300000);
//        }
//
//        // 采集区（县）
//        $city = $region->where('region_type', 2)->get()->toArray();
//        foreach ($city as $vo) {
//            $this->getChildren($vo['region_id'], 3, $client);
//            usleep(300000);
//        }

        // 采集镇
        $city = $region->where('region_type', 3)->get()->toArray();
        foreach ($city as $vo) {
            echo "\n当前区（县）：" . $vo['region_name'];
            $this->getChildren($vo['region_id'], 4, $client);
            usleep(300000);
        }

    }

    /**
     * 获取地区列表
     * @param int $id
     * @param int $region_type
     * @param $client
     */
    private function getChildren($id = 0, $region_type = 0, $client)
    {
        $node = ($id === 1) ? '' : 'id=' . $id . '&';

        $keys = ['GZUBZ-X5NKG-WRJQS-IPFB5-2AJ4T-JGFDU', '2FWBZ-AXW63-EP73N-YFTUD-XLLR2-JQFB4', 'ICMBZ-ZV4KX-64X45-T76OP-AOZCV-6AFP4'];

        $totalFile = storage_path('framework/total.txt');
        if (!is_file($totalFile)) {
            file_put_contents($totalFile, 0);
        }
        $total = file_get_contents($totalFile);
        $total = empty($total) ? 1 : intval($total) + 1;

        // 6000 / 8000 = 1
        // 8000 / 8000 = 1
        // 8001 / 8000= 2
        $keyIndex = ceil($total / 8000) - 1;

        $url = 'http://apis.map.qq.com/ws/district/v1/getchildren?' . $node . 'key=' . $keys[$keyIndex];
        $res = $client->get($url);
        file_put_contents($totalFile, $total);

        $region = json_decode($res->getBody(), true);
        if ($region['status'] == 0) {
            $list = $region['result'][0];
            if (!empty($list)) {
                foreach ($list as $vo) {
                    echo "\n    当前城镇：" . $vo['fullname'];
                    Region::updateOrCreate([
                        'region_id' => $vo['id'],
                        'parent_id' => $id,
                        'region_name' => $vo['fullname'],
                        'region_type' => $region_type,
                        'agency_id' => 0,
                    ]);
                }
            }
        }
    }
}
