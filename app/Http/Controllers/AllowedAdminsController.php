<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Serverfireteam\Panel\CrudController;
use App\AllowedAdmins;
use Auth;
use Illuminate\Http\Request;

class AllowedAdminsController extends Controller{

	//Вывод списка разрешенных к регестрированию пользователей
	public function all($entity)
	{
		$data = [
			'allAllowedAdmins' => AllowedAdmins::where('admin_id', Auth::guard('panel')->user()->id)->get()
		];
		return view('AllowedUsers', $data);
	}

	public function index()
	{
		return redirect("/panel/AllowedAdmins/all");
	}

	//Добавить разрешонного пользователя
	public function addUser()
	{
		if(!empty($_POST['email'])){
			$allUsers = AllowedAdmins::lists('email')->toArray();
			if (!in_array($_POST['email'], $allUsers)) {
				$email = !empty($_POST['name'])?$_POST['name']:'';
				$AllowedAdmins = new AllowedAdmins();
				$AllowedAdmins->email = $_POST['email'];
				$AllowedAdmins->name = $email;
				$AllowedAdmins->admin_id = Auth::guard('panel')->user()->id;
				$AllowedAdmins->save();
				return redirect()->back()->with('message', 'Администратор успешно добавлен');
			}else{
				return redirect()->back()->with('message', 'Администратор с таким E-mail уже существует');
			}

		}else{
			return view('addAllowedUser')->with(['message' => 'Анкета удален']);
		}
	}

	//Редактирование пользователя
	public function editUser($id="")
	{
		if(!empty($_POST['email'])){
			$email=$_POST['name'];
			$name = $_POST['name'];
			$id = $_POST['id'];
			
			//AllowedAdmins::where('admin_id', Auth::guard('panel')->user()->id)->where('id','=',$id)->update(['email'=>$email]);
			AllowedAdmins::where('admin_id', Auth::guard('panel')->user()->id)->where('id','=',$id)->update(['name' => $name, 'email'=>$email]);
			return redirect()->back()->with('message', 'Данные успешно изменены');
		}else{
			$admin =  AllowedAdmins::where('id','=',$id)->where('admin_id', Auth::guard('panel')->user()->id)->get();
			$data = [
				'admin' => $admin
			];
			return view('editAllowedUser', $data);
		}
	}

	//Удаление пользователя
	public function deleteUser($id){
		AllowedAdmins::where('id', '=', $id)->where('admin_id', Auth::guard('panel')->user()->id)->delete();
		return redirect()->back()->with('message', 'Админ удален успешно!');
	}


}