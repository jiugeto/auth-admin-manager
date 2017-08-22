<?php
namespace JiugeTo\AuthAdminManager\Controllers;

use Illuminate\Http\Request;
use JiugeTo\AuthAdminManager\Models\AdminModel;
use JiugeTo\AuthAdminManager\Models\RoleModel;
use Illuminate\Support\Facades\Hash;
use JiugeTo\AuthAdminManager\Controllers\ViewController as View;

class AdminController extends Controller
{
    /**
     * 管理员
     */

    public function __construct()
    {
        self::$prefix = '/admin/admin';
    }

    public static function index()
    {
        $datas = AdminModel::paginate(self::$limit);
        $datas->limit = self::$limit;
        $dataArr = array(
            'prefix' => self::$prefix,
            'view' => 'index',
        );
        return View::index($dataArr);
    }

    public static function create()
    {
        $dataArr = array(
            'prefix' => self::$prefix,
            'view' => 'create',
        );
        return View::index($dataArr);
    }

    public static function store(Request $request)
    {
        $data = self::getData($request);
        $data['created_at'] = time();
        AdminModel::create($data);
        return redirect(self::$prefix);
    }

    public static function edit($id)
    {
        $dataArr = array(
            'prefix' => self::$prefix,
            'view' => 'edit',
            'data' => self::getModelById($id),
        );
        return View::index($dataArr);
    }

    public static function update(Request $request,$id)
    {
        $model = self::getModelById($id);
        $data = self::getData($request);
        $data['updated_at'] = time();
        AdminModel::where('id',$id)->update($data);
        return redirect(self::$prefix);
    }

    public static function show($id)
    {
        $dataArr = array(
            'prefix' => self::$prefix,
            'view' => 'show',
            'data' => self::getModelById($id),
        );
        return View::index($dataArr);
    }

    /**
     * 收集、验证
     */
    public static function getData(Request $request)
    {
        if (!$request->name) {
            return array('code'=> -1, 'msg'=> '管理员必填！');
        }
        $roleModel = RoleModel::find($request->role);
        if (!$roleModel) {
            return array('code'=> -2, 'msg'=> '该角色不存在！');
        }
        return array(
            'name' => $request->name,
            'role' => $request->role,
            'password' => Hash::make('123456'),//默认密码
        );
    }

    /**
     * 通过 ID 获取 Model
     */
    public static function getModelById($id)
    {
        $model = AdminModel::find($id);
        if (!$model) { return array('code'=> -1, 'msg'=> '记录不存在！'); }
        return $model;
    }
}