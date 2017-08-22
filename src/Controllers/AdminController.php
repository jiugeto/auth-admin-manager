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
        self::$view = 'admin.admin';
    }

    public static function index()
    {
        $datas = AdminModel::paginate(self::$limit);
        $datas->limit = self::$limit;
        return View::index();
    }

    public static function create()
    {
        return '';
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
        return '';
    }

    public static function update(Request $request,$id)
    {
        return '';
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
            'password' => Hash::make('123456'),
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