<?php

namespace app\controller;

use think\facade\Db;
use think\facade\View;
use think\Request;

class Proxy extends Common
{
    public function index(Request $request)
    {
        $data['statusList'] = ['无效','有效'];

        $pageSize = 20;
        $where = [];
        $search = $request->param('search');
        if ($search) {
            $where[] = ['name','like',"%{$search}%"];
        }
        $status = $request->param('status');
        if ($status) {
            $status = array_keys($data['statusList'],$status)[0];
            $where[] = ['status','=',$status];
        }
        $list = Db::table('proxy')->where($where)->order("id", 'desc')->paginate([
            'list_rows' => $pageSize,
            'query' => $request->param()
        ]);
        $data['list'] = $list->items();
        $data['page'] = $list->render();
        return View::fetch('index', $data);
    }

    public function add(Request $request)
    {
        if ($request->isPost()) {
            ini_set('max_execution_time', 0);
            $data['host'] = $request->param('host');
            $data['port'] = $request->param('port');
            if (Db::name('proxy')->where('host', $data['host'])->where('port', $data['port'])->count('id')) {
                $this->error('代理已存在');
            }
            $result = testAgent($data['host'], $data['port']);
            if ($result == 200) {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            $data['create_time'] = date('Y-m-d h:i:s', time());
            if (Db::name('proxy')->insert($data)) {
                $this->success('代理添加成功');
            } else {
                $this->error('代理添加失败，请稍候再试');
            }
        } else {
            return View::fetch('add');
        }
    }


    public function edit(Request $request)
    {
        $id = $request->param('id');
        $info = Db::name('proxy')->where('id', $id)->find();
        if (!$info) {
            $this->error('数据不存在');
        }
        if ($request->isPost()) {
            ini_set('max_execution_time', 0);
            $data['host'] = $request->param('host');
            $data['port'] = $request->param('port');
            if (Db::name('proxy')->where('host', $data['host'])->where('port', $data['port'])->where('id', '<>', $id)->count('id')) {
                $this->error('代理已存在');
            }
            $result = testAgent($data['host'], $data['port']);
            if ($result == 200) {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            $data['create_time'] = date('Y-m-d h:i:s', time());
            if (Db::name('proxy')->where('id', $id)->update($data)) {
                $this->success('代理编辑成功');
            } else {
                $this->error('代理编辑失败，请稍候再试');
            }
        } else {
            $data['info'] = $info;
            return View::fetch('edit', $data);
        }
    }


    public function del(Request $request)
    {
        $id = $request->param('id');
        if (Db::name('proxy')->where('id', $id)->delete()) {
            $this->success('代理删除成功');
        } else {
            $this->error('代理删除失败');
        }
    }

    // 批量删除
    public function batch_del(Request $request){
        $ids = $request->param('ids');
        if (!$ids) {
            return $this->apiReturn(0,[],'请先选择要删除的数据');
        }
        $map[] = ['id','in',$ids];
        if (Db::name('proxy')->where($map)->delete()) {
            return $this->apiReturn(1,[],'批量删除成功');
        } else {
            return $this->apiReturn(0,[],'批量删除失败');
        }
    }

    public function test_speed(Request $request)
    {
        $id = $request->param('id');
        $info = Db::name('proxy')->where('id', $id)->find();
        $info['proxy'] = 'http://' . $info['host'] . ":{$info['port']}";
        if (request()->isPost()) {
            $url = $request->param('url');
        } else {
            $url = 'http://www.baidu.com';
        }
        $info['result'] = $this->proxyAccess($url, $info['host'], $info['port']);
        if (!empty($info['result'])) {
            $status = 1;
        } else {
            $status = 0;
        }
        Db::name('proxy')->where('id', $id)->update(['status'=>$status]);
        $data['info'] = $info;
        return View::fetch('test_speed', $data);
    }


    public function proxyAccess($url, $ip, $port)
    {
        $ch = curl_init();
        $header[] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36";
        // //定义请求类型
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 是否检测服务器的证书是否由正规浏览器认证过的授权CA颁发的
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 是否检测服务器的域名与证书上的是否一致
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // 设置代理
        curl_setopt($ch, CURLOPT_PROXY, $ip);
        curl_setopt($ch, CURLOPT_PROXYPORT, $port);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}