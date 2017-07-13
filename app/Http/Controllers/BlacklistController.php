<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\BlacklistModels;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class BlacklistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'allRecords' => BlacklistModels::where('user_id','=', Auth::user()->id)->get()
        ];
        return view('blacklist', $data);
    }

    public function addblacklist()
    {
        return view('addblacklist');
    }

    public function addblacklistitem()
    {
       $blackid = strip_tags($_POST['blackid']);//ID анкеты которую хотим обавить в черный список
        $user_id = strip_tags($_POST['user_id']);//ID зарегистрированного пользователя который обавляет анкету
        $desc= strip_tags($_POST['text']);//причина занесения в черный список
        $url = "http://www.natashaclub.com/".$blackid;//Страница с которой буем брать аватарку
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//возвращает веб-страницу
        $out = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $dom = \hQuery::fromHTML($out);
        $notVaid = $dom->find('div.DataDiv');
        if($notVaid!==null){
            $notVaid= $notVaid->text();//картинка с аватарки
            $find = "not availabl";
            $pos = strpos($notVaid, $find);
            if($pos) {
                $ava = 'http://searchpost.a262590r.bget.ru/public/files/noava.png';//картинка с аватарки
                $users = BlacklistModels::lists('idblack')->toArray();
                if (!in_array($blackid, $users)) {
                    $girls = new BlacklistModels();
                    $girls->idblack = $blackid;
                    $girls->desc = $desc;
                    $girls->ava = $ava;
                    $girls->user_id = $user_id;
                    $girls->save();
                    return redirect('/addblacklist')->with('message', "Анкета добавлена без фотографии");
                }else{
                    return redirect('/addblacklist')->with('message', 'Проверьте вводимые данные');
                }
           }else{
               $ava = $dom->find('td > div > a > img');
                if($ava!=null){
                    $ava = $ava->attr('src');
                    if($ava == '/templates/tmpl_nc/images_nc/pic_not_avail.gif'){
                       $ava = 'https://www.natashaclub.com'.$ava; 
                    }
                    $avaFile = '/avatars/blacklist/'.$blackid.'.jpg';
                    $avaCopy = file_put_contents(base_path().'/public'.$avaFile,file_get_contents($ava));                
                }

                if($avaCopy){
                   $users = BlacklistModels::lists('idblack')->toArray();
                    if (!in_array($blackid, $users)) {
                        $girls = new BlacklistModels();
                        $girls->idblack = $blackid;
                        $girls->desc = $desc;
                        $girls->ava = $avaFile;
                        $girls->user_id = $user_id;
                        $girls->save();
                        return redirect('/addblacklist')->with('message', "Анкета добавлена");
                    }
                }
               return redirect('/addblacklist')->with('message', "Проверьте вводимые данные. Анкета уже добавлена");
           }
        }else{
            $ava = $dom->find('td > div > a > img');
            if($ava!=null){
                $ava = $ava->attr('src');
                $avaFile = '/avatars/blacklist/'.$blackid.'.jpg';
                $avaCopy = file_put_contents(base_path().'/public'.$avaFile,file_get_contents($ava));                
            }
               
            if($avaCopy){
                $users = BlacklistModels::lists('idblack')->toArray();
                if (!in_array($blackid, $users)) {
                    $girls = new BlacklistModels();
                    $girls->idblack = $blackid;
                    $girls->desc = $desc;
                    $girls->ava = $avaFile;
                    $girls->user_id = $user_id;
                    $girls->save();
                    return redirect('/addblacklist')->with('message', "Анкета добавлена");
                }
            }else{
                return redirect('/addblacklist')->with('message', 'Проверьте вводимые данные');
            }
        }

    }

     //Уалить анкету из черного списка
    public function delete($item){
        BlacklistModels::where('id', '=', $item)->delete();
        return redirect('/blacklist');
    }

    public function edit($item){
       $allDataDB= BlacklistModels::where('id', '=', $item)->get();
        $allData = [
            'allData' => $allDataDB
        ];
        return view('blacklistedit', $allData);
    }

    //Обработка формы редактирования анкеты черного списка
    public function editAndSaveToDB(){
        $blackid = strip_tags($_POST['blackidkn']);//ID анкеты которую хотим обавить в черный список
        $user_id = strip_tags($_POST['user_id']);//ID зарегистрированного пользователя который обавляет анкету
        $desc= strip_tags($_POST['desc']);//причина занесения в черный список
        $url = "http://www.natashaclub.com/".$blackid;//Страница с которой буем брать аватарку
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//возвращает веб-страницу
        $out = curl_exec($ch);
        curl_close($ch);
        unset($ch);
        $dom = \hQuery::fromHTML($out);
        $notVaid = $dom->find('div.DataDiv');
        if($notVaid!==null){
            $notVaid= $notVaid->text();//картинка с аватарки
            $find = "not availabl";
            $pos = strpos($notVaid, $find);
            if($pos) {
                $ava = 'http://searchpost.a262590r.bget.ru/public/files/noava.png';//картинка с аватарки

                BlacklistModels::where('idblack','=',$blackid)->update(array('desc' => $desc, 'ava'=>$ava));
                return redirect()->back()->with('message', "Анкета coхранена без фотографии");
           }else{
                $ava = $dom->find('td > div > a > img');
                $ava=$ava->attr('src');//картинка с аватарки
                BlacklistModels::where('idblack','=',$blackid)->update(array('desc' => $desc, 'ava'=>$ava));
                return redirect()->back()->with('message', "Анкета coхранена");
           }
        }else{
            $ava = $dom->find('td > div > a > img');
            if($ava!==null){
                    $ava=$ava->attr('src');//картинка с аватарки
                    BlacklistModels::where('idblack','=',$blackid)->update(array('desc' => $desc, 'ava'=>$ava));
                    return redirect()->back()->with('message', "Анкета coхранена");
            }else{
                return redirect()->back()->with('message', 'Проверьте вводимые данные');
            }
        }
    }


}