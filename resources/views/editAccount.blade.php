@extends('layouts.app2')
@section('content')
    <div class="panel-heading account">
        <a href="{{route('indexAccount')}}">Аккаунты</a>
        <a href="./payment">Оплата</a>
        <a href="{{route('indexBlacklist')}}">Черный список</a>
    </div>
    <div class="container account margin-acc-red">
        <form role="form" method="POST" action="{{ route('editAndSaveDb') }}">
            <div class="row">
            {!! csrf_field() !!}
            @foreach($allData as $k=>$v)
                <div class="acc-left-panel">
                    <div class="anct-girls">
                        <div class="anct-girl-img">
                            <img src="{{$v->ava}}">
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-5">
                            <span>Логин</span>
                            <input type="text" value="{{$v->login}}" name="login" id="login" disabled="disabled">
                        </div>
                        <div class="col-md-5">
                            <input type="text" value="" name="newlogin" placeholder="Новый логин">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <span>Пароль</span>
                            <input type="text" value="{{$v->password}}" name="password" id="password" disabled="disabled">
                        </div>
                        <div class="col-md-5">
                            <input type="text" value="" name="newpassword" placeholder="Новый пароль">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5"></div>
                        <div class="col-md-5">
                            <input type="text" value="" name="confirmnewpassword" placeholder="Повторите новый пароль">
                        </div>
                        <input type="hidden" value="{{$v->originId}}" name="accountid" id="accountid">
                    </div>
                    <div class="row">
                        <div class="col-md-5 translate-marg-acc">
                            <?php
                            if(isset($allDataStatistics[0])){ ?>
                                <span>Переводчик</span><input disabled="disabled" type="text" name="translator" value= <?php  echo($allDataStatistics[0]); ?> >
                            <?php } ?>
                        </div>
                        <div class="col-md-5">
                            <input type="text" value="" name="newtranslater" placeholder="Новый переводчик">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <?php
                                if(isset($allDataNickname[0]) && strlen($allDataNickname[0])>0){ ?>
                                <span>Nickname</span><input disabled="disabled" type="text" name="nickname" value= <?php echo($allDataNickname[0]); ?> >
                            <?php } ?>
                        </div>
                        <div class="col-md-5">
                            <input type="text" value="" name="newnickname" placeholder="Новый Nickname">
                        </div>
                    </div>
                    <div class="row">

                        <input type="radio" name="isActipeProfile" value="active" id="activeProfile" {{$isactive[0]==0?"checked":""}}> <label for="activeProfile"  class="text-primary">Активный профиль</label>

						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="isActipeProfile" value="lowlevel" id="lowlevelProfile" {{$isactive[0]==2?"checked":""}}> <label for="lowlevelProfile" class="text-warning">Малоактивный профиль</label>

                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="isActipeProfile" value="inactive" id="inactiveProfile" {{$isactive[0]==1?"checked":""}}><label for="inactiveProfile" class="text-danger">Неактивный профиль</label>

                    </div>
                        @if(Session::has('message'))
                            <div class="allert-acc-search">{{ $value = Session::get('message')}}</div>
                        @endif
                        <div class="row buttongroup-acc-redact">
                          <p>
                            <input type="button" value="Обновить фото" id="updateavakn">
                            <input type="submit" value="Сохранить">
                          </p>
                        </div>
                </div>





            @endforeach
            </div>
        </form>
        <script type="text/javascript" src="{{asset('script/validateFormEditAccounts.js')}}"></script>
    </div>
@endsection