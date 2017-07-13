<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\GirlsModel;
use App\SentLetterModel;
use App\ResponseLettersModel;
use App\BroadcastsModel;
use App\Smiles;
use Illuminate\Http\Request;
use Auth;
use App\BlacklistModels;
use App\StatisticsModel;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BlockIdModel;
use Illuminate\Support\Facades\DB;
class StatisticsController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        $this->middleware('auth');
        $idGirlArr="";
        //Выбераю ВСЕ ID которые закрепленны за зарегестрированным пользователем
        $idgirls = GirlsModel::where('user_id','=', Auth::user()->id)->lists('idgirl');
        
        //заблокированые анкеты амина
        $idBlockgirls = BlockIdModel::where('idAdmin','=', Auth::user()->id)->lists('idAccount');
//        $idBlockgirls = BlockIdModel::where('idAdmin','=', Auth::user()->id)->lists('idAccount');

        //Если зарегестрированый пользователь, добавил на вкладке "Аккаунты" хоть одну анкету
        $countFirstSentLettersArr = array();
        $CountSmilesArr = array();
        $countResponseLettersArr = array();
        $idGirl = "";
        $idGirlsFirstSentLetters = array();
        if(count($idgirls)>0){
            foreach($idgirls as $id){
                $idGirl[] = substr($id, 3);//массив ВСЕХ ID анкет как на сайте natashaclub.com
            }
            foreach($idGirl as $idSmiles){
                $idGirlsSmile[] = Smiles::where('idGirl','=',$idSmiles)->get();
            }

            foreach($idGirl as $idSentLetters){
                $idGirlsFirstSentLetters[] = SentLetterModel::where('idGirl','=',$idSentLetters)->get();
            }

            foreach($idGirl as $ResponseLetters){
                $idGirlsResponseLetters[] = ResponseLettersModel::where('idGirl','=',$ResponseLetters)->get();
            }
        }

        $countAllSmiles = 0;
//        $nextBroadcast = BroadcastsModel::all();
        if(count($idgirls)>0){
            $data = [
                'allRecords' => StatisticsModel::where('user_id','=', Auth::user()->id)->get(),
                'idGirl' => $idGirl,
                'idGirlkn' => $idGirl,
                'idBlockgirls' =>$idBlockgirls
            ];
        }else{
            $data = [
                'allRecords' => StatisticsModel::where('user_id','=', Auth::user()->id)->get(),
                'idGirl' => $idGirl,
                'idGirlkn' => $idGirl,

            ];
        }
        return view('statistics', $data);
    }

    public function addStatistics()
    {
        //Аутентификация на сайте
        $this->middleware('auth');
        //Все анкеты зареаного пользователя
        $user_id =Auth::user()->id;
        $allAddedAccounts = GirlsModel::where('user_id','=', $user_id)->lists('idGirl')->toArray();
        //Добавить можно только ту анкету которая которая уже есть во вкладке Аккаунты
        //Проверяем уникальный ID который пользователь вводит на вкладке статистика, если
        // этот ID был есть на вкладке Аккаунты и он есть в переменной $allAddedAccounts
        //Разрешаем добавить ID в статистику
        $statisticsidunic= $_POST['statisticsidunic'];
        if (in_array($statisticsidunic, $allAddedAccounts)) {
            $statisticsid = trim($_POST['statisticsid']);
            $nickname = GirlsModel::where('originid','=', $statisticsid)->pluck('login');
            $statisticstranslator = $_POST['statisticstranslator'];
            $girls = new StatisticsModel();
            $girls->user_id = $user_id;
            $girls->idgirl = $statisticsid;
            $girls->idgirlunic = $statisticsidunic;
            $girls->translator = $statisticstranslator;
            $girls->nickname = $nickname[0];
            $girls->save();
            return redirect('/statistics')->with('message', "Анкета добавлена");
        }else{
            return redirect('/statistics')->with('message', "Проверте вводимые данные");
        }
    }

//ОБРАБОТКА - ОТПРАВЛЕННЫХ УЛЫБОК И ОБНОВЛЕНИЕ ДАТЫ СЛЕДУЮЩЕГО БРОАДКАСТА
    public function handlingSentSmiles(){
        $this->middleware('cors');
        $smilesAndData = $_POST['smiles'];//строка с датой и колличеством отправленных смайлов
        $idGirl = trim($_POST['idGirl']);//id анкеты
        if(isset($_POST['updateDateNextBroadcast']) and $_POST['updateDateNextBroadcast']!='available'){
            $updateDateNextBroadcast = trim($_POST['updateDateNextBroadcast']);//id анкеты
            $dbBroadcastId = BroadcastsModel::where('idGirl','=',$idGirl)->lists('idgirl')->toArray();
            if (in_array($idGirl, $dbBroadcastId)){
                BroadcastsModel::where('idGirl','=',$idGirl)->update(array('dateNextBroadcast' => $updateDateNextBroadcast));
            }else{
                BroadcastsModel::insert(
                    array('idGirl' => $idGirl, 'dateLastBroadcast'=>"Broadcast был до установки приложения", 'dateNextBroadcast' =>$updateDateNextBroadcast, 'countsentMessageBroadcast'=>"Broadcast был до установки приложения")
                );
            }
        }elseif($_POST['updateDateNextBroadcast']=='available'){
            BroadcastsModel::insert(
                array('idGirl' => $idGirl, 'dateLastBroadcast'=>"Broadcast был до установки приложения", 'dateNextBroadcast' =>"Доступен", 'countsentMessageBroadcast'=>"Broadcast был до установки приложения")
            );
        }
        //Проверяем есть ли вообще отосланые улыбки
        if($smilesAndData!="undefined"){
            $smilesAndData = substr($smilesAndData, 10);
            $patern = "/(\b\d{1,3}), ((2016|2017|2018|2019|2020)-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2})\b/";
            preg_match_all($patern, $smilesAndData, $matches, PREG_SET_ORDER);
            $testdateSmiles = Smiles::where('idGirl','=',$idGirl)->lists('dateSmiles')->toArray();
            //Проверяем нет ли отосланых улыбок с такой же датой как в БД, если нету значит улыбка
            //недавно отсылалась и ее необходимо занести в БД
            foreach($matches as $k=>$v){
                if (!in_array(trim($v[2]), $testdateSmiles)) {
                    Smiles::insert(
                        array('idGirl' => $idGirl, 'quantitySmiles' =>$v[1], 'dateSmiles' =>$v[2])
                    );
                }
            }
        }
    }

    //Обработка ППЕРВЫХ ОТПРАВЛЕННЫХ ПИСЕМ
    public function handlingFirstLetterSent(){
        $this->middleware('cors');
        $idGirl = trim($_POST['idGirl']);//id анкеты
        $idToSent = trim($_POST['idToSent']);//ID комуотсылали письмо
        //Берем из БД ID всех кому ранее отсылали письма
        //если idToSent нет в БД хаписываем его
        $idToSentDB = SentLetterModel::where('idGirl','=',$idGirl)->lists('idToSent')->toArray();
        if (!in_array($idToSent, $idToSentDB)) {
            SentLetterModel::insert(
                array('idGirl' => $idGirl, 'quantitySentLetters' => 1, 'dateSentLetters' => Carbon::now(), 'idToSent' => $idToSent)
            );
        }
    }

    //Обработка БРОАДКАСТОВ
    public function handlingBroadcasts(){
        $this->middleware('cors');
        $idGirl = trim($_POST['idGirl']);//id анкеты
        if(isset($_POST['dateNextBroadcast'])){
            $dateNextBroadcast = trim($_POST['dateNextBroadcast']);//дата следующего броадкаста
        }else{
            $dateNextBroadcast = "-";
        }
        $countLettersBroadcast = trim($_POST['countLettersBroadcast']);//Колличество писем отправленных броадкастом
        $dbBroadcastId = BroadcastsModel::where('idGirl','=',$idGirl)->lists('idgirl')->toArray();
        if (!in_array($idGirl, $dbBroadcastId)){
            BroadcastsModel::insert(
                array('idGirl' => $idGirl, 'dateLastBroadcast'=>Carbon::now(), 'dateNextBroadcast' =>"21", 'countsentMessageBroadcast'=>$countLettersBroadcast)
            );
        }else{
            BroadcastsModel::where('idGirl','=',$idGirl)->update(array('dateNextBroadcast' =>$dateNextBroadcast, 'countsentMessageBroadcast'=>$countLettersBroadcast));
        }
    }

//Обработка ответов на входящие письма
    public function handlingresponseletters(){
        $this->middleware('cors');
        $idGirl = trim($_POST['idGirl']);//id анкеты
        $count = $_POST['count'];
        ResponseLettersModel::insert(
            array('idGirl' => $idGirl, 'quantityResponseLetters' => $count, 'dateResponseLetters' => Carbon::now())
        );
    }

    private function detail($arr){
        $userID = $_POST['userID'];
        $IDGir = $_POST['idGir'];
        //Обработка вводимой пользователем даты
        $dateFrom = $_POST['dateFrom'];////строка в формате Число.Месяц.Год
        $dateFromArr = explode('.', $dateFrom);//Разбиваем по точке и загоняем в массив
        $dateFromDay = $dateFromArr[0];//число
        $dateFromMonth = $dateFromArr[1];//мисяц
        $dateFromYear = $dateFromArr[2];//год
        $dateFromDB = $dateFromYear . "-" . $dateFromMonth . "-" . $dateFromDay;//окончательная строка которую отправляем в БД

        $dateBy = $_POST['DateTo'];//строка в формате Число.Месяц.Год
        $dateByArr = explode('.', $dateBy);//Разбиваем по точке и загоняем в массив
        $dateByDay = $dateByArr[0];//число
        $dateByMonth = $dateByArr[1];//мисяц
        $dateByYear = $dateByArr[2];//год
        $dateByDB = $dateByYear . "-" . $dateByMonth . "-" . $dateByDay;//окончательная строка которую отправляем в БД



        $oneGirlsFirstSentLetters = count(SentLetterModel::where('idGirl', '=', $IDGir)
            ->where('dateSentLetters', '>=', $dateFromDB." 00:00:00")
            ->where('dateSentLetters', '<=', $dateByDB." 23:59:59")
            ->lists('quantitySentLetters')
            ->toArray());
        $oneGirlsResponseLetters = count(ResponseLettersModel::where('idGirl', '=', $IDGir)
            ->where('dateResponseLetters', '>=', $dateFromDB." 00:00:00")
            ->where('dateResponseLetters', '<=', $dateByDB." 23:59:59")
            ->lists('quantityResponseLetters')
            ->toArray());

        $oneGirlsSmileArr = array_sum(Smiles::where('idGirl', '=', $IDGir)
            ->where('dateSmiles', '>=', $dateFromDB." 00:00:00")
            ->where('dateSmiles', '<=', $dateByDB." 23:59:59")
            ->lists('quantitySmiles')
            ->toArray());

        $IDGir = $_POST['idGir'];
        //ДЕТАЛИЗАЦИЯ по дням
        foreach($arr as $k=> $value){
            //3333333333333333333333333333333333333333333333333333333333333333333333333333333333
            $oneGirlsFirstSentLettersDetail[] = SentLetterModel::where('idGirl', '=', $IDGir)
                ->where('dateSentLetters', '>=', $value." 00:00:00")
                ->where('dateSentLetters', '<=', $value." 23:59:59")
                ->get();
            //22222222222222222222222222222222222222222222222222222222222222
            $oneGirlsResponseLettersDetail[] = ResponseLettersModel::where('idGirl', '=', $IDGir)
                ->where('dateResponseLetters', '>=', $value." 00:00:00")
                ->where('dateResponseLetters', '<=', $value." 23:59:59")
                ->get();

            //данные за каждый день(отправленные улыбки)!11111111111111111111111111111111
            $oneGirlsSmileArrDetailquantitySmiles[] = Smiles::where('idGirl', '=', $IDGir)
                ->where('dateSmiles', '>=', $value." 00:00:00")
                ->where('dateSmiles', '<=', $value." 23:59:59")
                ->get();
        }

//Start
//3333333333333333333333333333333333333333333333333333333333333333333333333333
        //Выбераем только дату и колличество смайлов
        $quantityFirstRaw = array();
        $dateFirst = array();
        $FirstByDate = array();
        $knknFirst = array();

        foreach($oneGirlsFirstSentLettersDetail as $k1=>$v1){
            foreach($v1 as $k2=>$v2){
                $quantityFirstRaw[] = $v2['quantitySentLetters'];
                $dateFirst[] = $v2['dateSentLetters'];
            }
        }


        //Созаем массив клчи ата, значения клличество смайлов
        $knFirst[]=array_fill_keys($dateFirst, $quantityFirstRaw);

        foreach($knFirst as $k=>$v){
            $knFirst[$k][] = array_shift($v);
        }
        array_pop($knFirst[0]);

        $iFirst=0;
        foreach($knFirst[0] as $k=>$v){
            $knknFirst[$k][] = $v[$iFirst];
            $iFirst++;
        }



        foreach($arr as $v){
            $countFirst =0;
            foreach($knknFirst as $kk=>$vv){
                $positionFirst = strpos($kk, $v);
                if($positionFirst !==false){
                    $countFirst+=$vv[0];
                }
            }
            $FirstByDate[$v][] = $countFirst;
        }
//End
//3333333333333333333333333333333333333333333333333333333333333333333333333333


//Start
//22222222222222222222222222222222222222222222222222222222222222222222222222222
        //Выбераем только дату и колличество смайлов
        $quantityResponseRaw = array();
        $dateResponse = array();
        $responseByDate = array();
        $knknResponse = array();
        $knResponse = array();

        foreach($oneGirlsResponseLettersDetail as $k1=>$v1){
            foreach($v1 as $k2=>$v2){
                $quantityResponseRaw[] = $v2['quantityResponseLetters'];
                $dateResponse[] = $v2['dateResponseLetters'];
            }
        }


        //Созаем массив клчи ата, значения клличество смайлов
        $knResponse[]=array_fill_keys($dateResponse, $quantityResponseRaw);

        foreach($knResponse as $k=>$v){
            $knResponse[$k][] = array_shift($v);
        }
        array_pop($knResponse[0]);

        $iResponse=0;
        foreach($knResponse[0] as $k=>$v){
            $knknResponse[$k][] = $v[$iResponse];
            $iResponse++;
        }




        foreach($arr as $v){
            $countResponse =0;
            foreach($knknResponse as $kk=>$vv){
                $positionResponse = strpos($kk, $v);
                if($positionResponse !==false){
                    $countResponse+=$vv[0];
                }
            }
            $responseByDate[$v][] = $countResponse;
        }
//End
//2222222222222222222222222222222222222222222222222222222222222222222222222222222



//Start
//1111111111111111111111111111111111111111111111111111111111111111111111111111111
        //Выбераем только дату и колличество смайлов
        $quantitySmilesRaw = [];
        $dateSmiles = [];
        foreach($oneGirlsSmileArrDetailquantitySmiles as $k1=>$v1){
            foreach($v1 as $k2=>$v2){
                $quantitySmilesRaw[] = $v2['quantitySmiles'];
                $dateSmiles[] = $v2['dateSmiles'];
            }
        }


        //Созаем массив клчи ата, значения клличество смайлов
        $kn[]=array_fill_keys($dateSmiles, $quantitySmilesRaw);

        foreach($kn as $k=>$v){
            $kn[$k][] = array_shift($v);
        }
        array_pop($kn[0]);

        $knkn = [];
        $i=0;
        foreach($kn[0] as $k=>$v){
            $knkn[$k][] = $v[$i];
            $i++;
        }


        $smilesByDate = array();
        foreach($arr as $v){
            $smiles =0;
            foreach($knkn as $kk=>$vv){
                $position = strpos($kk, $v);
                if($position !==false){
                    $smiles+=$vv[0];
                }
            }
            $smilesByDate[$v][] = $smiles;
        }
//End
//1111111111111111111111111111111111111111111111111111111111111111111111111111111
        $oneGirlsSmileArrDetail1[]=$smilesByDate;
        $oneGirlsResponseLettersDetail2[]=$responseByDate;
        $oneGirlsFirstSentLettersDetail3[]=$FirstByDate;
        $broadcast = BroadcastsModel::where('idGirl', '=', $IDGir)->get();
        return json_encode(
            array
            (
                'oneGirlsFirstSentLetters' => $oneGirlsFirstSentLetters,
                'oneGirlsResponseLetters' => $oneGirlsResponseLetters,
                'oneGirlsSmile' => $oneGirlsSmileArr,
                'oneGirlsFirstSentLettersDetail' => $oneGirlsFirstSentLettersDetail3,
                'oneGirlsResponseLettersDetail' => $oneGirlsResponseLettersDetail2,
                'oneGirlsSmileArrDetail' => $oneGirlsSmileArrDetail1,
                'broadcast' => $broadcast
            )
        );
    }

//Обновление таблицы со статистикой, по дате
    public function updateStatisticsByDate(){
        //return $_POST;
        $this->middleware('auth');
        if(isset($_POST['dateFrom']) && isset($_POST['DateTo'])){
            if(!empty($_POST['arr7']) || !empty($_POST['arr14']) || !empty($_POST['arr30'])|| !empty($_POST['customarr'])) {
                //return $_POST['arr30'];

                if(!empty($_POST['arr7'])){
                    $arrRaw7 = $_POST['arr7'];
                    $arr7= explode(',', $arrRaw7 );
                    return $this->detail($arr7);
                }

                if(!empty($_POST['arr14'])){
                    $arrRaw14 = $_POST['arr14'];
                    $arr14= explode(',', $arrRaw14 );
                    return $this->detail($arr14);
                }
                if(!empty($_POST['arr30'])){
                    $arrRaw30 = $_POST['arr30'];
                    $arr30= explode(',', $arrRaw30 );
                    return $this->detail($arr30);
                }
                if(!empty($_POST['customarr'])){
                    $customarrRaw = $_POST['customarr'];
                    $csutomarr= explode(',', $customarrRaw );
                    return $this->detail($csutomarr);
                }
                //else{return "fuck";}
            }else{

            }
        }
        //Обновление за 1 день
        if(isset($_POST['currentDate'])){
            $IDGir = $_POST['idGir'];//Разбиваем по точке и загоняем в массив
            //Обработка даты
            //Текущая ата
            $currentDate = $_POST['currentDate'];
            /*$coma = strpos($currentDate, ',');//позиция в которой нахоится запятая
            $dateOneDay=substr($currentDate, 0, $coma);//Дата в формате ДД.ММ.ГГГГ*/
            $dateOneDayArr = explode('.', $currentDate);//Разбиваем по точке и загоняем в массив
            //return $dateOneDayArr ;
            $dateOneDayDay = $dateOneDayArr[0];//число
            $dateOneDayMonth = $dateOneDayArr[1];//мисяц
            $dateOneDayYear = $dateOneDayArr[2];//год
            $dateOneDayDB = $dateOneDayYear."-".$dateOneDayMonth."-".$dateOneDayDay;//окончательная строка которую отправляем в БД
            $broadcast = BroadcastsModel::where('idGirl', '=', $IDGir)->get();
            $oneGirlsFirstSentLetters = count(SentLetterModel::where('idGirl','=',$IDGir)
                ->whereBetween('dateSentLetters', array($dateOneDayDB. " 00:00:00", $dateOneDayDB. " 23:59:59"))
                ->lists('quantitySentLetters')
                ->toArray());
            $oneGirlsResponseLetters = count(ResponseLettersModel::where('idGirl','=',$IDGir)
                ->whereBetween('dateResponseLetters', array($dateOneDayDB. " 00:00:00", $dateOneDayDB. " 23:59:59"))
                ->lists('quantityResponseLetters')
                ->toArray());

            $oneGirlsSmileArr = array_sum(Smiles::where('idGirl','=',$IDGir)
                ->whereBetween('dateSmiles', array($dateOneDayDB. " 00:00:00", $dateOneDayDB. " 23:59:59"))
                ->lists('quantitySmiles')
                ->toArray());

            return json_encode(
                array
                (
                    'oneGirlsFirstSentLetters' =>$oneGirlsFirstSentLetters,
                    'oneGirlsResponseLetters' => $oneGirlsResponseLetters,
                    'oneGirlsSmile' => $oneGirlsSmileArr,
                    'broadcast' => $broadcast
                )
            );
        }
    }

    //Обработка черного списка, если в БД есть nickname
    // который пришел от chrome extension, отсылаем обратно отзыв
    // кторый записан в БД
    public function handlingBlackListInExtensions(){
        if(isset($_POST['nickNameBlack'])){
            $nickNameBlack = trim($_POST['nickNameBlack']);//nickName тогого кого ищем в чером списке
            $idGirlBlack = trim(substr(trim($_POST['idGirlBlack']), 8, 10));//ид анкеты тогого кого ищем в чером списке
            $originID = $_POST['originID'];
            $idgirlRes = GirlsModel::where('originId',$originID)->first();//Если анкета занесена в Searc post
            if($idgirlRes){
                $res = BlacklistModels::where('idblack', $idGirlBlack)->first();
            }else{
                $res = "Анкеты нет в SearchPost";
            }
            return json_encode($res, JSON_UNESCAPED_UNICODE) ;
        }else{
            return "error2";
        }
    }
}