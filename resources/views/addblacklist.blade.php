@extends('layouts.app2')

@section('content')
    @include('layouts.menu')
    <a class="back-new-blck" href="./blacklist">назад к списку</a>

    <form role="form" method="POST" action="{{ url('/addblacklist/add') }}" enctype="multipart/form-data">
        {!! csrf_field() !!}

        <div class="container">
            <div class="panel-body">
@if(Session::has('message'))
			<div class="balcklist-message text-justify">{{ $value = Session::get('message')}}</div>
				<br>
				<br>
		@endif

                <div class="left-panel blacklist">
                    <div class="form-group{{ $errors->has('blackid') ? ' has-error' : '' }}">
                        <input class="blacklist" placeholder="УКАЖИТЕ ID МУЖЧИНЫ" type="text" name="blackid" value="{{ old('blackid') }}" id="blackid">
                        @if ($errors->has('blackid'))
                            <span class="help-block">
                            <strong>{{ $errors->first('blackid') }}</strong>
                        </span>
                        @endif
                        <div class="error-box"></div> <!-- Контейнер для ошибок -->
                    </div>
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}"></p>

                    {{--<div class="file-upload">
                        <button type="button">добавить</button>
                        <div>загрузите его фото</div>
                        <input type="file" name="userfile" id="file">
                    </div>
                    <div class="blacklist-img">
                        <img id="balcklist-img" src="" alt="">
                    </div>--}}
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
    @if(isset($allRecords))
        {{$allRecords}}
    @endif
    <div class="text-content blacklist">
        <p>
            Приложение выдает уведомление о том, что мужчина, профайл 
которого открыла девушка, находится в черном списке агентства.
Профайл может быть занесен туда по любой причине владельцем доступа:
мужчина слишком конфликтный, требовательный, неадекватный, пошлый,
любит почитать анкету, а затем вернуть деньги за переписку без объяснения 
причины, или напротив же, придумывая то, чего не было.
Нажмите «добавить анкету, укажите ID мужчины и кратко причину занесения
Все девушки, подключенные к программе, увидят у себя уведомление о том, что мужчина находится в черном списке и причину его занесения туда.
        </p>
        <p>
            
        </p>
    </div>
    <!-- Скрипт отображения фото до его загрузки -->
    {{--<script>
        var showFile = ( function() {
            var fr = new FileReader;
            fr.onload = function(e) {
                document.getElementById("balcklist-img").src = e.target.result;
            };
            return function(e) {
                var files = e.target.files, file, i;
                for (i = 0; file = files[i]; i++) {
                    if (!file.type.match('image.*')) continue;
                    fr.readAsDataURL(file);
                }
            }
        } )()
        document.getElementById('file').addEventListener('change', showFile, false);
    </script>--}}
@endsection