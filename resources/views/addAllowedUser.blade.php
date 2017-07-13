@extends('panelViews::mainTemplate')
@section('page-wrapper')
    <a href="{{ url('panel/AllowedAdmins/index') }}">Вернуться к списку админов</a>
        <form role="form" method="POST" action="{{ url('/panel/addUser  ') }}" class="form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="exampleInputEmail">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail" placeholder="Введите E-mail" name="email">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword">Имя</label>
                <input type="text" class="form-control" id="exampleInputPassword" placeholder="Введите имя" name="name">
            </div>
            <button type="submit" class="btn btn-default">Добавить админа</button>
        </form>
    @if(Session::has('message'))
        <div class="balcklist-message">{{ $value = Session::get('message')}}</div>
    @endif
@stop