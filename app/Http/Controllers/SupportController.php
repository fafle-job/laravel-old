<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ContactFormRequest;
use App\Http\Controllers\Controller;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        /*$data = [
            'allRecords' => GirlsModel::where('user_id','=', Auth::user()->id)->get()
        ];
        return view('accounts', $data);*/
        return view('support');
    }

    public function feedbackFormsHandling(ContactFormRequest $request){

        /*if(!isset($hasError)) {
            $emailTo = 'name@yourdomain.com'; //Сюда введите Ваш email
            $body = "Name: $name \n\nEmail: $email \n\nSubject: $subject \n\nComments:\n $comments";
            $headers = 'From: My Site <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
            mail($emailTo, $subject, $body, $headers);
            $emailSent = true;
            }*/
        $name = $request->get('nameSp');
        \Mail::send('emails.contact',
            array(
                'name' => $request->get('nameSp'),
                'email' => $request->get('emaiSp'),
                'subject' => $request->get('subject'),
                'user_message' => $request->get('textSp')
            ), function($message)
            {
                $message->from('support@agency-stat.ru');
                $message->to('service@netville.com.ua', 'Admin')->subject("Subject");
            });

        /*return \Redirect::route('support')->with('message', 'Thanks for contacting us!')*/;

        $data = [
            'message' =>"Ваш запрос отправлен"
        ];
        return view('support', $data);


        /*if(!empty($_POST['nameSp']) and !empty($_POST['emaiSp']) and !empty($_POST['subject'])
            and !empty($_POST['text'])){
            $nameSp = trim(strip_tags($_POST['nameSp']));
            $emaiSp = trim(strip_tags($_POST['emaiSp']));
            $subject = trim(strip_tags($_POST['subject']));
            $text = trim(strip_tags($_POST['text']));

            mail('kolya.nerush@mail.ru', $subject,$text,
                'Вам написал: '.$nameSp.
                '<br />Его почта: '.$emaiSp.
                '<br />Его сообщение: '.$text,
                "Content-type:text/html;charset=utf-8");
        }
        else {

        }*/

        /*$data = [
            'message' =>"Ваш запрос отправлен"
        ];
        return view('support', $data);*/
    }
}