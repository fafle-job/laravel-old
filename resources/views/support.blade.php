@extends('layouts.app2')

@section('content')
        @include('layouts.menu')
	<div class="container">
		<div class="panel-body">
			<div id="support-sp">
				@if( isset($message))
					{{$message}}
				@endif

				<p>Здесь текст о назначении этой страницы, ее цели и чему она может помочь</p>
				<form id="support" class="" role="form" method="POST" action="{{ url('/support/feedbackFormsHandling') }}">
					{!! csrf_field() !!}

					<input class="question-sp" placeholder="Имя" type="	text" name="nameSp" id="nameSp" value="">
					<div class="error-box"></div> <!-- Контейнер для ошибок -->

					<input class="question-sp" placeholder="Email" type="	text" name="emaiSp" id="emaiSp" value="">
					<div class="error-box"></div> <!-- Контейнер для ошибок -->

					<input class="question-sp" placeholder="Тема письма" type="	text" name="subject" id="subject" value="">
					<div class="error-box"></div> <!-- Контейнер для ошибок -->

					<textarea class="support-text-sp" rows="10" cols="45" name="text" id="textSp" placeholder="	Подробно опишите суть поставленного вопроса и возникшие проблемы"></textarea>
					<div class="error-box"></div> <!-- Контейнер для ошибок -->

					<p><input type="submit" value="Отправить" disabled = "disabled" id="submitSupport"></p>
				</form>
					<script type="text/javascript" src="{{ asset('script/validateFormSupport.js')}}"></script><!--Валидация формы при добавлении аккаунта-->
			</div>
		</div>
	</div>

@endsection