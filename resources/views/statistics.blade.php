@extends('layouts.app2')
@section('content')
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('style/jquery-ui.min.css')}}" />
    <script language="javascript" type="text/javascript" src="{{ asset('script/moment.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/moment-range.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/json2.js')}}"></script>

    {{--<script language="javascript" type="text/javascript" src="{{ asset('script/jquery-ui-1.10.3/ui/jquery.ui.core.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/jquery-ui-1.10.3/ui/jquery.ui.widget.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/jquery-ui-1.10.3/ui/jquery.ui.mouse.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/jquery-ui-1.10.3/ui/jquery.ui.draggable.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/jquery-ui-1.10.3/ui/jquery.ui.position.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/jquery-ui-1.10.3/ui/jquery.ui.resizable.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/jquery-ui-1.10.3/ui/jquery.ui.dialog.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/jquery-ui-1.10.3/ui/jquery.ui.effect.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/jquery-ui-1.10.3/ui/jquery.ui.effect-blind.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/jquery-ui-1.10.3/ui/jquery.ui.effect-explode.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/jquery-ui-1.10.3/ui/jquery.ui.button.js')}}"></script>--}}


    <script language="javascript" type="text/javascript" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script>
        $(function(){
            /*$('#showHidden').click(function() {
             if ($('#box').is(':visible')) {
             $('#box').hide();
             } else {
             $('#box').show();
             }
             });*/
            $('.showHidden').click(function() {
                $('.detail').toggle();
            });
        });
    </script>
    <script>
        $(function () {
            $('#datepickerFrom').datepicker($.extend({
                        inline: true,
                        changeYear: true,
                        changeMonth: true,
                        dateFormat: "dd.mm.yy"
                    },
                    $.datepicker.regional['ru']
            ));
            $('#datepickerTo').datepicker($.extend({
                        inline: true,
                        changeYear: true,
                        changeMonth: true,
                        dateFormat: "dd.mm.yy"
                    },
                    $.datepicker.regional['ru']
            ));
			 $('#datepickerOneDay').datepicker($.extend({
                        inline: true,
                        changeYear: true,
                        changeMonth: true,
                        dateFormat: "dd.mm.yy"
                    },
                    $.datepicker.regional['ru']
            ));
        });

        jQuery(function ($) {
            $.datepicker.regional['ru'] = {
                closeText: 'Закрыть',
                prevText: '&#x3c;Пред',
                nextText: 'След&#x3e;',
                currentText: 'Сегодня',
                monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
                    'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                monthNamesShort: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
                    'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
                dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
                dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                weekHeader: 'Нед',
                /*dateFormat: 'dd.mm.yy',*/
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['ru']);
        });
    </script>
            @include('layouts.menu')	
			<div class="text-content">
				<br/>
				<p>Уважаемое агентство, для того, что данная программа работала, всем клиенткам (или переводчикам) Вашего агентства необходимо установить себе в браузер Google Chrome расширение, которое можно добавить по этой <a href="https://chrome.google.com/webstore/detail/search-posts-v2/pdnpleagicbeojeddkdfpllldbkakljb" target="_blank">ссылке</a>.</p>

				<p>Пока что программа работает только с этим браузером, потому просим использовать сайт NatashaClub только в нем.</p>

				<p>Как только мы увидим, что все работает, как нужно, получим и изучим все Ваши отзывы и пожелания, мы адаптируем приложение под все популярные браузеры.</p>
				<p>Спасибо, что Вы с нами.</p>
			</div>


    <div class="container" >
        <div class="myloader"></div>
        <input type="hidden" value="{{Auth::user()->id}}" id="userID">

        <div class="row">
            <div class="col-md-12">
                <form method="post" action="">
                    <div class="row button-group-static">
                        <div class="col-md-12">
                            {{--<p1> <input type="button" value="Обновить за 1 день" name="updateDateOneDay" id="updateDateOneDay"></p1>
                            <p1> <input type="button" value="Обновить за 7 дней" name="updateDateSevenDays" id="updateDateSevenDays"></p1>
                            <p1> <input type="button" value="Обновить за 14 дней" name="updateDateFourteenDays" id="updateDateFourteenDays"></p1>
                            <p1> <input type="button" value="Обновить за 30 дней" name="updateDateThirtyDays" id="updateDateThirtyDays"></p1>--}}
                        </div>
                        <div class="col-md-12">
							<p>Укажите дату, если Вас интересует статистика за конкретный день</p>
                            <p1><input class="mergeinput" name="dateByOneday" type="text" id="datepickerOneDay" autocomplete="off"></p1>
                            <p1> <input type="button" value="Очистить период" name="clearCalendarOneDay" id="clearCalendarOneday"></p1>
                            <br>
							<p>Укажите период</p>
                            <p1>Период с - <input name="dateFrom" type="text" id="datepickerFrom" autocomplete="off"></p1>
                            <p1> пo - <input name="dateBy" type="text" id="datepickerTo" autocomplete="off"></p1>
                            <p1> <input type="button" value="Очистить период" name="clearCalendar" id="clearCalendar"></p1>
                        </div>
                    </div>
                </form>
                <table id="example" width="100%" border="3">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>ID</th>
                        <th>Nickname</th>
                        <th>Количество отправленных улыбок</th>
                        <th>Количество отправленных первых писем</th>
                        <th>Количество ответов на входящие</th>
                        <th>Дата последнего Broadcast</th>
                        <th>Количество писем отправленных в последний Broadcast</th>
                        <th>Следующий Broadcast возможен через</th>
                        <th>Переводчик</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($allRecords) and !$allRecords->isEmpty())
                        @foreach($allRecords as $k=>$record)
                            <?php
                            if(count($idBlockgirls)>0){
                            foreach($idBlockgirls as $kk =>$vv){
                            if($idBlockgirls[$kk]==$record->idgirl){

                            ?>

                            <tr class="{{$record->isactive===0?"bg-info":($record->isactive===1?"bg-danger":($record->isactive===2?"bg-warning":"11"))}}" >
                                <td>
                                    <a href="#" class="toggle">{{$k+1}}</a>
                                </td>
                                <td>
                                    <a href="#" class="toggle" id="idgirl">{{$record->idgirl}}</a>
                                </td>
                                <td class="showHidden">
                                    {{$record->nickname}}
                                </td>
                                <td id="countSentSmiles{{$record->idgirl}}">
                                    <?php
                                    $count = 0;
                                    echo $count;
                                    ?>
                                </td>
                                <td id="countSetnLetters{{$record->idgirl}}">
                                    <?php
                                    $countFirstLetter = 0;//
                                    echo $countFirstLetter;
                                    ?>
                                </td>
                                <td id="countResponseLetters{{$record->idgirl}}">
                                    <?php
                                    $countResponse = 0;
                                    echo $countResponse;
                                    ?>
                                </td>
                                <td class="dateLastBroadcast" id="dateLastBroadcast{{$record->idgirl}}">

                                    0
                                </td>
                                <td class="countsentMessageBroadcast" id="countsentMessageBroadcast{{$record->idgirl}}">
                                    0

                                </td>
                                <td class="dateNextBroadcast" id="dateNextBroadcast{{$record->idgirl}}">

                                    0
                                </td>
                                <td>
                                    {{$record->translator}}
                                </td>
                            </tr>
                            <?php }}}?>
                        @endforeach
                    @endif
                    </tbody>
                </table>

                <script>
                    $(document).ready(function() {
                        $('#example').DataTable( {
                            "paging":   false,
                            "info":     false,
                        } );
                    } );
                </script>
            </div>
        </div>
        <br/>
        <br/>
        <br/>
        <br/>
        <div class="text-content">
            <p>

            </p>
            <p>

            </p>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('script/updateStatisticsByDate.js')}}"></script><!--Валидация формы при добавлении аккаунта-->
@endsection