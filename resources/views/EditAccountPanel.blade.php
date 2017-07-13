@extends('panelViews::mainTemplate')
@section('page-wrapper')
    <div class="container" >
        <div class="panel-body" >
            <div>
                <form role="form" method="POST" action="{{ route('posteditacount.adminpanel', $item) }}">
                    {!! csrf_field() !!}
                    {{--////////////////////////////////////////////////////--}}
                    <?php
/*                        //echo "<pre>";
                        var_dump($flag);
                    */?>
                    @foreach($allData as $k=>$v)
                        <div class="container">
                            <div class="paneol-body">
                                <div class="img-girl">
                                    <img src="{{$v->ava}}">
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <span>Логин</span><input type="text" value="{{$v->login}}" name="login" id="login" disabled="disabled">
                                <br/>
                                <span>Пароль</span><input type="text" value="{{$v->password}}" name="password" id="password" disabled="disabled">
                                <input type="hidden" value="{{$v->originId}}" name="accountid" id="accountid" >
                                <input type="hidden" value="{{$v->user_id}}" name="idAdmin" id="idAdmin" >
                                <br/>
                                <?php
                                if(isset($allDataStatistics[0])){ ?>
                                <span>Переводчик</span><input disabled="disabled" type="text" name="translator" value= <?php  echo($allDataStatistics[0]); ?> >
                                <?php } ?>
                                <br/>
                                <?php
                                if(isset($allDataNickname[0]) && strlen($allDataNickname[0])>0){ ?>
                                <span>Nickname</span><input type="text" name="nickname" value= <?php echo($allDataNickname[0]); ?> disabled="disabled">
                                <?php } ?>
                                <hr/>
                                @if(Session::has('message'))
                                    <div>{{ $value = Session::get('message')}}</div>
                                @endif
                                <hr/>
                                <input type="text" value="" name="newlogin" placeholder="Новый логин"><Br/>
                                <input type="text" value="" name="newpassword" placeholder="Новый пароль">
                                <input type="text" value="" name="confirmnewpassword" placeholder="Повторите новый пароль"><Br/>
                                <input type="text" value="" name="newnickname" placeholder="Новый Nickname"><Br/>
                                <input type="text" value="" name="newtranslater" placeholder="Новый Перевочик"><Br/>
                                <div class="block-ankete">
                                    <?php
                                        if(count($flag)<0 || $flag==NULL){//значит анкета заблокирована
                                    ?>
                                    <input type="checkbox" name="unblock" value="{{ $item }}" ><i> Разблокировать анкету</i><Br/>
                                    <?php }?>
                                    <?php
                                        if(count($flag)>0){//значит анкета разблокирована
                                        ?>
                                        <input type="checkbox" name="block" value="{{ $item }}" ><i>Заблокировать анкету</i><Br/>
                                    <?php }?>
                                </div>
                                <?php
/*                                    if(!isset($flag) ){
                                    */?><!--
                                <p><input type="checkbox" name="unblock" value="{{ $item }}" >Разблокировать анкету<Br>
                                --><?php /*}*/?>
                                {{--<input type="button" value="Обновить аву" id="updateavakn">--}}
                                <input type="submit" value="Обновить данные">
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>
@stop