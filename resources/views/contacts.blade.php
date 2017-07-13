@extends('layouts.app2')

@section('content')
    @include('layouts.menu')
    @if(Session::has('message'))
       <div class="balcklist-message">{{ $value = Session::get('message')}}</div>
    @endif
    <form role="form" method="POST" action="{{ url('/addblacklist/add') }}" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="container">
        <div class="panel-body">
            <div class="left-panel blacklist">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <input class="blacklist" placeholder="УКАЖИТЕ ИМЯ" type="text" name="name" value="{{ old('name') }}" id="name">
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                    <div class="error-box"></div> <!-- Контейнер для ошибок -->
                </div>
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}"></p>



                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input class="blacklist" placeholder="УКАЖИТЕ EMAIL" type="text" name="email" value="{{ old('email') }}" id="email">
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    <div class="error-box"></div> <!-- Контейнер для ошибок -->
                </div>

            </div>
            <div class="right-panel blacklist">
                <textarea rows="10" cols="45" name="text" placeholder="Кратко опишите причину занесения" id = "text"></textarea>
                <div class="error-box"></div> <!-- Контейнер для ошибок -->
                <p><input type="submit" value="Добавить"></p>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('script/validateFormBlackListAdd.js')}}"></script><!--Валидация формы при добавлении аккаунта-->
    </form>
@endsection