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
	  <div class="searchpost-logo"><a href="/"></a></div>
    <div class="href">
        <a href="/">главная</a>
            <span>/</span>
        <a href="/support">техподдержка</a>
    </div>
    <nav>
        @if (Auth::guest())
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
                            <a class="btn btn-link" href="{{ url('/password/reset') }}">забыли пароль?</a>
                        </fieldset>
                    </form>
                </div>
            </li>
        </ul>
        @else
            <ul id="login-user-sp">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    {{ Auth::user()->email }} <span class="caret"></span>
                </a>

                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                </ul>
            </li>
            </ul>
        @endif
    </nav>
  </div>
</header>
<div class="backgrount-cover other">
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
        @if (Auth::guest())
                <div class="div-container">
                    <h1>РЕГИСТРАЦИЯ</h1>
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
                @else
                @endif

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                @if (Auth::guest())
                    {{--  --}}
                @else
                    <h1>Добро пожаловать, <i>{{ Auth::user()->email }}</i></h1>
				@yield('text')
                @endif
                <!-- Left Side Of Navbar -->
                {{--<ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">Home</a></li>
                </ul>--}}
                <!-- Right Side Of Navbar -->
            </div>
    </nav>
</div>
<div class="content">
    @if (Auth::guest())
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
            {!! csrf_field() !!}
			<div class="text-content">
<p>Пожалуйста, используйте реальный логин Вашей админ-панели, так как все остальные логины - будут не допущены к регистрации. Пароль можно придумать на Ваше усмотрение, но не менее 12 символов (буквы латиницей, заглавные и прописные, цифры)</p>
			</div>
            <!-- <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Name</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div> -->
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} login">
                <label class="col-md-4 control-label"></label>
                <div class="col-md-6">
                    <input type="email" class="form-control" name="email" placeholder="логин" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} password">
                <label class="col-md-4 control-label"></label>
                <div class="col-md-6">
                    <input type="password" class="form-control" name="password" placeholder="пароль">
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }} confirm-password">
                <label class="col-md-4 control-label"></label>
                <div class="col-md-6">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="повторить пароль">
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group captcha">
                <label class="col-md-4 control-label"></label>
                <div class="col-md-6">
                    {!! app('captcha')->display() !!}
                </div>
            </div>
            <div class="form-group button-captcha">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        ЗАРЕГИСТРИРОВАТЬСЯ
                    </button>
                </div>
            </div>
        </form>
        <div class="text-content">
            <p>
Программа выполняет две главные цели.
Первая -позволяет владельцу агентства видеть реальный объем проделанной работы как по каждому переводчику отдельно, так  по всему агентству в целом, за любой временной диапазон. Подсчет кол-ва отправленных "улыбок", первых писем, ответов на входящие письма, кол-во и возможность отправки писем broadcast.
Вторая - программа не дает девушкам, установившим себе приложение, отправить первое презентационное письмо в середине переписки, а также связаться с мужчиной, ID которого занесено в черный список агентства.

Разработка расширений в процессе

            </p>
        </div>
    @else
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- JavaScripts -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
<footer>
    <div class="footer-content">
            <span>&copy; <?php echo date('Y');?> Сopyright. Все права защищены. searchpost</span>

			<span class="pull-right">Created by <a href="http://netville.com.ua/" target="_blank">Netville</a></span>
        </div>
</footer>
</body>
</html>