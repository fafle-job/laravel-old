<?php

namespace App\Http\Controllers;

use App\StatisticsModel;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use \Serverfireteam\Panel\CrudController;
use App\GirlsModel;
use Illuminate\Http\Request;
use App\Users;
use App\BlockIdModel;
use App\BroadcastsModel;
use App\ResponseLettersModel;
use App\SentLetterModel;
use App\Smiles;
use App\BlacklistModels;
use App\User;
use App\Admins;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;
use Auth;

class mycrudController extends CrudController{
	public function __construct() { }

	public function all($entity)
	{
		$users = Users::all()->toArray();
		foreach($users as $k=>$records){
			$usersArr[]=$records;
		}

		$data = [
			'allUsers' => DB::table('users')->where('name', Auth::guard('panel')->user()->id)->get(),
			'allGirls' => DB::table('girls')->get(),
			'test' => Auth::guard('panel')->user()->id
		];
		return view('UserPanel', $data);
	}

	public function deleteAdmin($id){
		$idgirls[] = StatisticsModel::where('user_id', '=', $id)->lists('idgirl');//id всех анкет которые добавил админ
		foreach($idgirls[0] as  $idgirl){
			$idToDel[] = $idgirl;
		}
		if(isset($idToDel)){
			foreach($idToDel as $del){
				$broadcastCount = BroadcastsModel::where('idGirl', '=', $del)->lists('id');
				if(count($broadcastCount)>0){
					BroadcastsModel::where('idGirl', '=', $del)->delete();//удалить данные  броадкасте
				}

				$ResponseLettersCount = ResponseLettersModel::where('idGirl', '=', $del)->lists('id');
				if(count($ResponseLettersCount)>0){
					ResponseLettersModel::where('idGirl', '=', $del)->delete();//удалить ответы на вхдящие письма
				}

				$SentLetterCount =SentLetterModel::where('idGirl', '=', $del)->lists('id');
				if(count($SentLetterCount)>0){
					SentLetterModel::where('idGirl', '=', $del)->delete();//удалить первые отправленные письма
				}

				$SmilesCount =Smiles::where('idGirl', '=', $del)->lists('id');
				if(count($SmilesCount)>0){
					Smiles::where('idGirl', '=', $del)->delete();//удалить улыбки
				}
			}
		}

		$BlacklistCount =BlacklistModels::where('user_id', '=', $id)->lists('id');
		if(count($BlacklistCount)>0){
			BlacklistModels::where('user_id', '=', $id)->delete();//удалить записи админа из чернго списка
		}

		$GirlstCount =GirlsModel::where('user_id', '=', $id)->lists('id');
		if(count($GirlstCount)>0){
			GirlsModel::where('user_id', '=', $id)->delete();//удалить аккаунты кторые закреплены за админом
		}

		$StatisticsCount =StatisticsModel::where('user_id', '=', $id)->lists('id');
		if(count($StatisticsCount)>0){
			StatisticsModel::where('user_id', '=', $id)->delete();//удалить записи админа из статистики
		}

		Users::where('id', '=', $id)->delete();//удалить админа
		return redirect()->back()->with(['success' => 'Админ удален']);
	}

	public function deleteacount($id){
		GirlsModel::where('originId', '=', $id)->delete();//удалить анкету
		StatisticsModel::where('idgirl', '=', $id)->delete();//удалить анкету
		return redirect()->back()->with(['success' => 'Анкета удален']);
	}

	public function editacount($item){
		$allDataDBgirl= GirlsModel::where('originid', '=', $item)->get();
		$allDataDBTranslator= StatisticsModel::where('idgirl', '=', $item)->lists("translator")->toArray();
		$allDataDBNickname= StatisticsModel::where('idgirl', '=', $item)->lists("nickname")->toArray();
		$idAdmin= GirlsModel::where('idgirl', '=', $item)->lists("user_id")->toArray();

		$flag = DB::table('blockid')->where('idaccount', $item)->first();
		//if(count($flag)>0){$flag = 1;}

		$allData = [
			'allData' => $allDataDBgirl,
			'allDataStatistics' => $allDataDBTranslator,
			'allDataNickname' => $allDataDBNickname,
			'item' => $item,
			'idAdmin' => $idAdmin,
			'flag' => $flag
		];
		return view('EditAccountPanel', $allData);
	}

	public function posteditacount($item){
		if(isset($_POST['accountid'])){
			$originid = $_POST['accountid'];
		}

		if(strlen($_POST['newpassword'])>0){
			if($_POST['newpassword'] == $_POST['confirmnewpassword']){
				$password= $_POST['newpassword'];
				GirlsModel::where('originid','=',$originid)->update(array('password' => $password));
			}else{
				return redirect()->back()->with('message', 'Пароли не совпаают');
			}
		}

		if(strlen($_POST['newlogin'])>0){
			$login = $_POST['newlogin'];
			GirlsModel::where('originid','=',$originid)->update(array('login' => $login));
		}

		if(strlen($_POST['newtranslater'])>0){
			$translator= $_POST['newtranslater'];
			StatisticsModel::where('idgirl','=',$originid)->update(array('translator' => $translator));
		}

		if(strlen($_POST['newnickname'])>0){
			$nickname= $_POST['newnickname'];
			StatisticsModel::where('idgirl','=',$originid)->update(array('nickname' => $nickname));
		}

		//проверяем разблокировать ли анкета
		$flag = BlockIdModel::where('idAccount','=', $originid)->lists('idAccount')->toArray();
		if(count($flag)==0 && isset($_POST['unblock']) ){
			$idAccount = $_POST['unblock'];
			$idAdmin = $_POST['idAdmin'];
			//Разблокировка ID
			DB::table('blockid')->insert(
				array('idAccount' => $idAccount, 'idAdmin' =>$idAdmin)
			);
		}

		if(isset($_POST['block']) ){
			$idAccount = $_POST['block'];
			$idAdmin = $_POST['idAdmin'];
			//Разблокировка ID
			DB::table('blockid')->where('idAccount', '=', $idAccount)->delete();
			//DB::table('blockid')->delete(array('idAccount' => $idAccount, 'idAdmin' =>$idAdmin));
		}

		return redirect()->back();

		//return view('EditAccountPanel');
	}

	//Разблокировка всех аккаунтов одного админа
	public function  blockAllID($id){
		$idBlockgirls = GirlsModel::where('user_id','=', $id)->lists('originid')->toArray();
		$idgirls = BlockIdModel::where('idAdmin','=', $id)->lists('idAccount')->toArray();
		foreach($idBlockgirls as $k=> $idBlockgirl){
			if(!in_array($idBlockgirls[$k] ,$idgirls)){
				DB::table('blockid')->insert(
					array('idAccount' => $idBlockgirl, 'idAdmin' =>$id)
				);
			}
		}
		return redirect()->back();
	}

	//Блокировка всех аккаунтов одного админа
	public function  UnblockAllID($id){
		$idBlockgirls = GirlsModel::where('user_id','=', $id)->lists('originid');
		foreach($idBlockgirls as $idBlockgirl){
			DB::table('blockid')->where('idAccount', '=', $idBlockgirl)->delete();
		}
		return redirect()->back();
	}

	public function addAdmin(){
		$Admins = Admins::all();
		foreach($Admins as $k=>$records){
			$usersArr[]=$records;
		}
		$data = [
			'allAdmins' => $Admins,
		];
		return view('addAdminInPanel', $data);
	}

	public function addAdminPost(){
		if(!empty($_POST['email'])){
			$allUsers = Admins::lists('email')->toArray();
			if (!in_array($_POST['email'], $allUsers)) {
				//$email = !empty($_POST['name'])?$_POST['name']:'';
				$user = new Admins();
				$user->email = $_POST['email'];
				$user->first_name = $_POST['name'];
				$user->password = bcrypt('1111');
				$user->save();
				return redirect()->back()->with('message', 'Администратор успешно добавлен');
			}else{
				return redirect()->back()->with('message', 'Администратор с таким E-mail уже существует');
			}
		}else{
			return view('addAdminInPanel')->with(['message' => 'Анкета удалена']);
		}
	}

	public function blockAdminPanel($id){
		$admin = Admins::where('id', $id)->get();
		$email = $admin[0]->email;
		$str ="@_!#$";
		if(!strpos($email, $str)){
			Admins::where('id', $id)->update(array('email' => $email.$str, 'activated'=>1));
			return redirect()->back();
		}else{
			$newemail = substr($email,0,-5);
			Admins::where('id', $id)->update(array('email' => $newemail, 'activated'=>0));
			return redirect()->back();
		}
	}

	public function deleteAdminPanel($id){
		Admins::where('id', $id)->delete();//удалить данные  броадкасте
		return redirect()->back()->with('message', 'Администратор Удален');
	}
}