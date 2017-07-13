<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BlockIdModel;
use App\StatisticsModel;
use Auth;
use App\GirlsModel;

class ActiveIDController extends Controller
{
    public function index()
    {
        //заблокированые анкеты амина
        $idBlockgirls = BlockIdModel::where('idAdmin', '=', Auth::user()->id)->lists('idAccount');
        //Все анкеты, закрепленные за админом
        $allRecords = StatisticsModel::where('user_id', '=', Auth::user()->id)->get();

        //Выбераю ВСЕ ID которые закрепленны за зарегестрированным пользователем
        $idgirls = GirlsModel::where('user_id', '=', Auth::user()->id)->lists('idgirl');
        //Если зарегестрированый пользователь, добавил на вкладке "Аккаунты" хоть одну анкету
        $idGirl = "";
        if (count($idgirls) > 0) {
            foreach ($idgirls as $id) {
                $idGirl[] = substr($id, 3);//массив ВСЕХ ID анкет как на сайте natashaclub.com
            }
        }

        $data = [
            'allRecords' => $allRecords,
            'idBlockgirls' => $idBlockgirls,
        ];
        return view('activeid', $data);
    }


    public function handlingActiveIDmsg()
    {
        $idGirl = $_POST['idGirl'];
        $yesterday = $_POST['yesterday'];
        $nowDay = $_POST['nowDay'];
        $girlData = GirlsModel::where('originid', '=', $idGirl)->get()->toArray();

        //Авторизация на сайте и запись кук
        $urlInit = "http://www.natashaclub.com/member.php";
        $ID = $_POST['idGirl'];
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

        //Первая страница из почтового ящика, беру колличество страниц в почтовом ящике и парсю её
        $url = "http://www.natashaclub.com/outbox.php";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
        $out = curl_exec($ch);
        curl_close($ch);
        unset($ch);

        $dom = \hQuery::fromHTML($out);
        $links = $dom->find('td.panel > a');
        //$tr = $dom->find('tr.table');

        //Ссылки на следующие страницы с письмами
        foreach ($links as $k => $v) {
            $href[] = $v->attr('href');
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //////////////////////////////////Ссылка на последнюю страницу//////////////////////////////////////////////////
        $lastPage = $href[count($href) - 1];
        $lastPage2 = (substr($lastPage, strpos($lastPage, 'page=') + 5));
        //////////////////////////////Колличество страниц в почтовом ящике/////////////////////////////////////////////
        $lastPageEnd = substr($lastPage2, 0, strpos($lastPage2, '&'));
        //////////////////////////////Массив из сылок по которым пройдеться CURL////////////////////////////////////////
        for ($i = 1; $i <= $lastPageEnd; $i++) {
            $arrUrl[] = 'http://www.natashaclub.com/outbox.php?page=' . $i;
        }
        $tasks=[];
        foreach ($arrUrl as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
            $html = curl_exec($ch);
            if (strpos($html, $yesterday) or strpos($html, $nowDay)) {
                $tasks[]= $html;
                curl_close($ch);
            }else{
                break;
            }
        }

        $dom = \hQuery::fromHTML($tasks[0]);
        $tr = $dom->find('tr.table');
        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!




////////////////////////////////Беру данные из страницы и делаю массив который мне нужен////////////////////////////////
        foreach ($tr as $k => $v) {
            //trr - массив со строками , каждая строка массив
            $trr[] = explode(',', trim(str_replace("\r\n", '', $v->html())));
        }

        $i = 0;
        //Массив вида - сирока из почтового ящика аккаунта - это элемент массива
        foreach ($trr as $k => $v) {
            //preg_match_all("~\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d~", $v[0], $out);
            //$trr2[$out[0][0]][]= $v[0];
            foreach ($v as $kk => $vv) {
                if (strpos($vv, '"profile')) {
                    $arrRows[$i][] = $vv;
                    $i++;
                }
            }
        }

        foreach ($arrRows as $k => $v) {
            foreach ($v as $kk => $vv) {
                $newArrRows[$kk][] = explode("td", $vv);
            }
        }


        foreach ($newArrRows as $v) {
            foreach ($v as $kk => $vv) {
                unset($vv[0]);
                unset($vv[1]);
                unset($vv[2]);
                unset($vv[4]);
                unset($vv[6]);
                unset($vv[7]);
                if (isset($vv[8])) {
                    unset($vv[8]);
                }
                $vv[3] = substr($vv[3], 43);
                $index = strpos($vv[3], '<');
                $vv[3] = substr($vv[3], 0, $index);
                $date = [];
                preg_match('~\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d~', $vv[5], $date);
                $vv[5] = $date[0];
                if ((strpos($vv[5], $nowDay)!==false) || (strpos($vv[5], $yesterday))!==false) {
                    $arrayWithToAndWhen[] = $vv;
                }else{
                    continue;
                }

            }
        }



//$arrayWithToAndWhen - массив в котором кому и когда отправлялись письма
////////////////////////////////Закончил////////////////////////////////////////////////////////////////////////////////
        unlink(dirname(__FILE__) . '/cookie.txt');

        return json_encode(
            array
            (
                'date' => $arrayWithToAndWhen,

            )
        );

//----------------------------------------------------------------------------------------------------------------------


//----------------------------------------------------------------------------------------------------------------------

    }
}