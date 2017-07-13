@extends('layouts.app2')
@section('text')

@endsection
@section('content')
{{--
    {!!  $girls !!}
--}}
    @include('layouts.menu')    
    <div class="container account">

	<div class="text-content" style="padding-top:25px;">
Пожалуйста, добавляйте только тех, девушек, которые принадлежат выбранному логину. Все остальные анкеты будут получать пожизненный бан
</div>
        <div class="panel-body account">
          <div class="left-panel">
            <form id="accountAdd" class="form-horizontal" role="form" method="POST" action="{{ url('/accounts/addaccont') }}">
                {!! csrf_field() !!}
                @if(Session::has('message'))
                    {{ $value = Session::get('message')}}
                @endif

                <div class="form-group{{ $errors->has('accountid') ? ' has-error' : '' }}">
                    <input placeholder="ВВЕДИТЕ ID АНКЕТЫ" type="text" class="form-control" name="accountid" id="id" value="{{ old('accountid') }}">
                    @if ($errors->has('accountid'))
                        <span class="help-block">
                            <strong>{{ $errors->first('accountid') }}</strong>
                        </span>
                    @endif
                    <div class="error-box"></div> <!-- Контейнер для ошибок -->
                </div>

                <div class="form-group{{ $errors->has('accountlogin') ? ' has-error' : '' }}">
                    <input placeholder="ВВЕДИТЕ ЛОГИН АНКЕТЫ" type="text" class="form-control" name="accountlogin" id="login" value="{{ old('accountlogin') }}">
                    @if ($errors->has('accountlogin'))
                        <span class="help-block">
                            <strong>{{ $errors->first('accountlogin') }}</strong>
                        </span>
                    @endif
                    <div class="error-box"></div> <!-- Контейнер для ошибок -->

                </div>

                <div class="form-group{{ $errors->has('accountpass') ? ' has-error' : '' }}">
                    <input placeholder="ВВЕДИТЕ ПАРОЛЬ АНКЕТЫ" type="password" class="form-control" name="accountpass"  id="pwd" value="{{ old('accountpass') }}">
                    @if ($errors->has('accountpass'))
                        <span class="help-block">
                            <strong>{{ $errors->first('accountpass') }}</strong>
                        </span>
                    @endif
                    <div class="error-box"></div> <!-- Контейнер для ошибок -->

                </div>

                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}"></p>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit">
                            Добавить
                        </button>
                    </div>
                </div>
            </form>
            <script type="text/javascript" src="{{ asset('script/validateFormAccountsAdd.js')}}"></script><!--Валидация формы при добавлении аккаунта-->
          </div>
              <div class="right-panel payment-method">
                @if(isset($allRecords) and !$allRecords->isEmpty())
                    @foreach($allRecords as $record)
                        <div class="anct-girls">
                            <div class="anct-girl-img">
                                <a  href="./accounts/delete/{{$record->idgirl}}"><button class="delete-girl"><i>X</i></button></a>
                                   <img src="<?php echo (stripos($record->ava,'natasha')!==false?'" class="update':$record->ava); ?>">
                            </div>
                            <p>{{$record->password}}</p>
                            <p>{{$record->login}}</p>
                            <a class="ids-add" href="./accounts/edit/{{$record->idgirl}}">{{$record->idgirl}}</a>
                        </div>
                    @endforeach
                @endif
                <div class="account-nav" style="
    width: 100%;
    height: 70px;
    overflow: hidden;
    text-align: center;
">
                    <?php echo $allRecords->render(); ?>
                </div>
              </div>
        </div>
    </div>

@endsection