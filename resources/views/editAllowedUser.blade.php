@extends('panelViews::mainTemplate')
@section('page-wrapper')

<a href="{{ url('panel/AllowedAdmins/index') }}">Вернуться к списку админов</a>
@foreach($admin as $adm)
    <form role="form" method="POST" action="{{ url('/panel/AllowedAdmins/editUser/') }}" class="form-inline">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="exampleInputEmail">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail" value="{{$adm->email}}" name="email">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword">Имя</label>
            <input type="text" class="form-control" id="exampleInputPassword" value="{{$adm->name}}" name="name">
        </div>
        <input type="hidden" name="id" value="{{ $adm->id }}">
        <button type="submit" class="btn btn-default">Сохранить изменения</button>
    </form>
@endforeach
    @if(Session::has('message'))
        <div class="balcklist-message">{{ $value = Session::get('message')}}</div>
    @endif
@stop