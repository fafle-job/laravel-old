<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StatisticsModel;
use Auth;
use App\BlockIdModel;
use App\GirlsModel;
use Yangqi\Htmldom\Htmldom;

class unreadmsgController extends Controller
{

    public function index()
    {
        //заблокированые анкеты амина
        $idBlockgirls = BlockIdModel::where('idAdmin','=', Auth::user()->id)->lists('idAccount');
        //Все анкеты, закрепленные за админом
        $allRecords = StatisticsModel::where('user_id','=', Auth::user()->id)->get();

        //Выбераю ВСЕ ID которые закрепленны за зарегестрированным пользователем
        $idgirls = GirlsModel::where('user_id','=', Auth::user()->id)->lists('idgirl');
        //Если зарегестрированый пользователь, добавил на вкладке "Аккаунты" хоть одну анкету
        $idGirl = "";
        if(count($idgirls)>0){
            foreach($idgirls as $id){
                $idGirl[] = substr($id, 3);//массив ВСЕХ ID анкет как на сайте natashaclub.com
            }
        }

        $data = [
            'allRecords' => $allRecords,
            'idBlockgirls' => $idBlockgirls,
        ];
        return view('unreadmsg', $data);
    }



    public function handlingUnreadmsg(){
        $idGirl = $_POST['idGirl'];
        $girlData = GirlsModel::where('originid','=', $idGirl)->get()->toArray();

        $login = $girlData[0]['login'];

        $urlInit = "http://www.natashaclub.com/member.php";
        $ID =  $_POST['idGirl'];
        $PASSWORD = $girlData[0]['password'];
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
        $url = "http://www.natashaclub.com/inbox.php";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
        $out = curl_exec($ch);
        curl_close($ch);
        unset($ch);

        $dom = \hQuery::fromHTML($out);
        //$links = $dom->find('td.panel > a');
        $tr = $dom->find('tr.table');

        //Ссылки на следующие страницы с письмами
        /*foreach ($links as $k=>$v){
            $href[]=$v->attr('href');
        }*/

        foreach ($tr as $k=>$v){
            if(strpos($v->html(), 'new.gif')){
                //trr - массив со строками , каждая строка массив
                $trr[]=explode(',',trim(str_replace("\r\n",'',$v->html())));
            }
        }

        $i=1;
        //Массив вида - ключ -это число когда пришло письмо
        if(isset($trr)){
            foreach ($trr as $k=>$v){
                preg_match_all("~\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d~", $v[0], $out);
                //$trr2[$out[0][0]][]= $v[0];
                $trr1[]= substr($out[0][0], 0, 10);
                $i++;
            }
        }else{
            $trr1=[];
        }


//----------------------------------------------------------------------------------------------------------------------

        $url2 = "http://www.natashaclub.com/inbox.php?page=2";
        $ch2 = curl_init($url2);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
        $out2 = curl_exec($ch2);
        curl_close($ch2);
        unset($ch2);

        $dom2 = \hQuery::fromHTML($out2);
        $tr2 = $dom2->find('tr.table');

        foreach ($tr2 as $k=>$v){
            if(strpos($v->html(), 'new.gif')){
                //trr - массив со строками , каждая строка массив
                $trr2[]=explode(',',trim(str_replace("\r\n",'',$v->html())));
            }
        }

        $i=1;
        //Массив вида - ключ -это число когда пришло письмо
        if(isset($trr2)){
            foreach ($trr2 as $k=>$v){
                preg_match_all("~\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d~", $v[0], $out);
                //$trr2[$out[0][0]][]= $v[0];
                $trr11[]= substr($out[0][0], 0, 10);
                $i++;
            }
        }else{
            $trr11=[];
        }



//----------------------------------------------------------------------------------------------------------------------
        unlink(dirname(__FILE__) . '/cookie.txt');

        return json_encode(
            array
            (
                //'href' => array_unique($href),
                'date' => array_merge($trr1, $trr11)
            )
        );
    }
}