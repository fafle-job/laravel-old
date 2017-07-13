<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Searchpost</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>
    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('style/main.css') }}" />


    <link rel="stylesheet" href="{{ asset('style/style.css' )}}"/><!--стили ктаблице-->
    <link rel="stylesheet" type="text/css" href="{{ asset('style/calendar.css')}}" />
    <!--<script type="text/javascript" src="script/jquery-latest.js"></script>jquery-->
    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('script/jquery.tablesorter.combined.js')}}"></script><!--сортировка таблицы-->
    <script type="text/javascript" src="{{ asset('script/myTablesorter.js')}}"></script><!--сортировка таблицы-->
    <script language="javascript" type="text/javascript" src="{{ asset('script/calendar.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#login-trigger').click(function(){
                $(this).next('#login-content').slideToggle();
                $(this).toggleClass('active');

                if ($(this).hasClass('active')) $(this).find('span').html('&#x25B2;')
                else $(this).find('span').html('&#x25BC;')
            })
        });
        $(document).ready(function(){
            $('.calendarFrom').simpleDatepicker();  // Привязать вызов календаря к полю с CSS класом #calendar
            $('.calendarBy').simpleDatepicker();  // Привязать вызов календаря к полю с CSS класом #calendar
        });
    </script>
    <style>
        body {font-family: 'Lato';}
        .fa-btn {margin-right: 6px;}
    </style>
</head>
<body id="app-layout">
<header class="cf">
  <div class="content">
    <div class="searchpost-logo"><a href=""></a></div>
    <div class="href">
        <a href="/">главная</a>
            <span>/</span>
        <a href="/public/support">техподдержка</a>
    </div>
    <nav>
        <ul>
            <li id="login">
                <a id="login-trigger" href="#">
                   <i></i><span></span> авторизация
                </a>
                <div id="login-content">
                    <i></i>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}

                        <fieldset id="inputs">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="email" id="username" name="email" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input type="password"  id="password" name="password" placeholder="пароль">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        </fieldset>

                        <fieldset id="actions">
                            <button type="submit" class="btn btn-primary" id="submit" name="auth">
                                ВОЙТИ
                            </button>
                            <!-- <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a> -->
                            <a class="btn btn-link" href="{{ url('/password/reset') }}">забыли пароль?</a>
                        </fieldset>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
  </div>
</header>
<script type="text/javascript">
        $(document).ready(function(){
         if(window.location.hash == 'http://searchpost.a262590r.bget.ru/public/statistics'){
            $('.backgrount-cover').addClass(' other');
         }
        });
    </script>

<div class="backgrount-cover">
    <div class="content">
        <div>
            <p>
                SearchPost - лучшее аналитическое приложение, созданное для агентств, сотрудничающих с НаташаКлуб
            </p>
        </div>
    </div>
</div>
<div class="content">
    <nav class="navbar navbar-default">
        <div class="div-container">
            <h1>Восстановление пароля</h1>
            <div class="navbar-header">
                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Branding Image -->
                {{--<a class="navbar-brand" href="{{ url('/') }}">
                    Laravel
                </a>--}}
            </div>
        </div>
    </nav>
</div>
<div class="content">
    @if (Auth::guest())
        @yield('content')
    @else

    @endif
</div>

    <!-- JavaScripts -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
<footer>
    <div class="content">
        <div class="footer-content">
            <span>© 2015 Сopyright. Все права защищены. searchpost</span>
        </div>
    </div>
</footer>
</body>
</html>
