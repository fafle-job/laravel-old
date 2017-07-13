@extends('layouts.appreset')

<!-- Main Content -->
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 sp">
            <div class="panel panel-default">
                <div class="panel-heading"></div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} sp">
                            <label class="col-md-4 control-label">Для восстановления пароля введите свой адрес электронной почты. Вам прийдет письмо с сылкой для сброса пароля</label>

                            <div class="col-md-6 sp">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Ваш адрес электронной почты">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary sp">
                                    отправить
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
