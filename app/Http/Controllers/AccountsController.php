<?php

namespace App\Http\Controllers;
use App\SentLetterModel;
use App\ResponseLettersModel;
use App\BroadcastsModel;
use App\BlockIdModel;
use App\Smiles;
use Illuminate\Http\Request;
use Auth;
use App\Exceptions;
use App\GirlsModel;
use App\StatisticsModel;
use App\Http\Requests;
use App\User;
use App\Http\Controllers\Controller;
use duzun\hQuery;



class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
//        $users = GirlsModel::lists('login')->toArray();
//        print_r($users);
        $data = [
            'allRecords' => GirlsModel::where('user_id','=', Auth::user()->id)->orderBy('originId','asc')->paginate(20)
        ];
        return view('accounts', $data);
    }
    public function check(Request $request)
    {
        $this->validate($request, [
            'accountid' => 'required|max:255',
            'accountlogin' => 'required|max:255',
            'accountpass' => 'required|max:255',
        ]);

        return $this->addaccont();
    }

    public function init()
    {
        $urlInit = "http://www.natashaclub.com/member.php";
        $ID = $_POST['accountid'];
        $PASSWORD = $_POST['accountpass'];
        $post_data = http_build_query(array
        (
            'ID' => $ID,
            '_r' => '/member.php',
            'Password' => $PASSWORD
        ));
        $ch = curl_init($urlInit);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt'); // сохранять куки в файл
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
        $out = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        return $out;
    }

    public function addaccont()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 3; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $url = "http://www.natashaclub.com/member.php";
        $accountid = $_POST['accountid'];
        $accountlogin = $_POST['accountlogin'];
        $accountpass = $_POST['accountpass'];
        $user_id = $_POST['user_id'];
        //$allRecords = GirlsModel::where('user_id','=', Auth::user()->id)->get();
        $users = GirlsModel::where('originId','=', $accountid)->first();
        if (empty($users)) {
            
            $this->init();
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
            $out = curl_exec($ch);
            curl_close($ch);
            unset($ch);
            $dom = \hQuery::fromHTML($out);
            $ava = $dom->find('td > div > a > img')->attr('src');//картинка с аватарки
            $avaFile = '/avatars/'.$randomString.$accountid.'.jpg';
            $ava = file_put_contents(base_path().'/public'.$avaFile,file_get_contents($ava));
            
            $girls = new GirlsModel();
            $girls->idgirl = $randomString.$accountid;
            $girls->login = $accountlogin;
            $girls->password = $accountpass;
            $girls->ava = $avaFile;
            $girls->originId = $accountid;
            $girls->user_id = $user_id;
            $girls->save();

            $nickname = GirlsModel::where('originid','=', $accountid)->pluck('login');
            $statistics = new StatisticsModel();
            $statistics->user_id = $user_id;
            $statistics->idgirl = $accountid;
            $statistics->idgirlunic = $randomString.$accountid;
            $statistics->translator = '';
            $statistics->nickname = $nickname[0];
            $statistics->save();
            unlink(dirname(__FILE__) . '/cookie.txt');
            return json_encode(array('status'=>'success'));
        }else{
            return json_encode(array('status'=>'error','text'=>'Анкета уже зарегистрирована'));
        }
    }

public function deleteaccount($item){
        $girl = GirlsModel::where('idgirl', '=', $item)->first();
        $avaPath = base_path().'/public'.$girl->ava;
        if(file_exists($avaPath)){
            unlink($avaPath);
        }
        $girl->delete();
        StatisticsModel::where('idgirlunic', '=', $item)->delete();
        $blockId = substr($item, 3);
        BlockIdModel::where('idAccount', '=', $blockId)->delete();
        BroadcastsModel::where('idGirl', '=', $blockId)->delete();

        $countRecordResponseLetters = ResponseLettersModel::where('idGirl', '=', $blockId)->count();
        for($i=0; $i<=$countRecordResponseLetters; $i++){
            ResponseLettersModel::where('idGirl', '=', $blockId)->delete();
        }

        $countRecordSentLetter = SentLetterModel::where('idGirl', '=', $blockId)->count();
        for($i=0; $i<=$countRecordSentLetter; $i++){
            SentLetterModel::where('idGirl', '=', $blockId)->delete();
        }

        $countRecordSmiles = Smiles::where('idGirl', '=', $blockId)->count();
        for($i=0; $i<=$countRecordSmiles; $i++){
            Smiles::where('idGirl', '=', $blockId)->delete();
        }

        return redirect()->back();
    }


    public function editaccount($item){
        $allDataDBgirl= GirlsModel::where('idgirl', '=', $item)->get();
        $allDataDBTranslator= StatisticsModel::where('idgirlunic', '=', $item)->lists("translator")->toArray();
        $allDataDBNickname= StatisticsModel::where('idgirlunic', '=', $item)->lists("nickname")->toArray();
        $isactive= StatisticsModel::where('idgirlunic', '=', $item)->lists("isactive")->toArray();
        $allData = [
            'allData' => $allDataDBgirl,
            'allDataStatistics' => $allDataDBTranslator,
            'allDataNickname' => $allDataDBNickname,
            'isactive' =>$isactive
        ];
        return view('editAccount', $allData);
    }

    public function editAndSaveDb(){
        if(isset($_POST['accountid'])){
            $originid = $_POST['accountid'];
        }

        if(strlen($_POST['newpassword'])>0){
            if($_POST['newpassword'] == $_POST['confirmnewpassword']){
                $password= $_POST['newpassword'];
                GirlsModel::where('originid','=',$originid)->update(array('password' => $password));
            }else{
                return redirect()->back()->with('message', 'Пароли не совпадают');
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
        
        if($_POST['isActipeProfile']){

            switch( $_POST['isActipeProfile'] )
            {
                case 'active':
                    StatisticsModel::where('idgirl','=',$originid)->update(array('isactive' => 0));
                    break;
                case 'inactive':
                    StatisticsModel::where('idgirl','=',$originid)->update(array('isactive' => 1));
                    break;
                case 'lowlevel':
                    StatisticsModel::where('idgirl','=',$originid)->update(array('isactive' => 2));
                    break;
            }

        }
        
        return redirect()->back();
    }

    public function updateava(){
        $urlInit = "http://www.natashaclub.com/member.php";
        $ID = $_GET['accountid'];
        $PASSWORD = $_GET['password'];
        $post_data = http_build_query(array
        (
            'ID' => $ID,
            '_r' => '/member.php',
            'Password' => $PASSWORD
        ));
        $ch = curl_init($urlInit);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt'); // сохранять куки в файл
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
        $out = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $url = "http://www.natashaclub.com/member.php";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
        $out = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $dom = \hQuery::fromHTML($out);
        $ava = $dom->find('td > div > a > img')->attr('src');//картинка с аватарки
        
        $girl = GirlsModel::where('originid','=',$ID)->first();
        $avaFile = '/avatars/'.$girl->idgirl.'.jpg';
        if(file_put_contents(base_path().'/public'.$avaFile,file_get_contents($ava))){
            GirlsModel::where('originid','=',$ID)->update(array('ava' => $avaFile));
        }
        unlink(dirname(__FILE__) . '/cookie.txt');
        //return redirect('/accounts');
    }
}