$(document).ready(function(){
//Очистить поля ввода даты за несколько дней
    $('#clearCalendar').on("click", function(e){
        //Вчерашнее число
        $('#datepickerFrom').val("");
        $('#datepickerTo').val("");
    });
//Очистить поле ввода даты за один жень
    $('#clearCalendarOneday').on("click", function(e){
        //Вчерашнее число
        $('#datepickerOneDay').val("");
    });

//Заполняем период за один день
    /*$('#updateDateOneDay').on("click", function(e){
        var yesterday = moment().subtract(1, 'days').format('DD.MM.YYYY');
        $('#datepickerTo').val(yesterday);
        $('#datepickerFrom').val("");
    });*/

    arr7 = [];
    arr14 = [];
    arr30 = [];
    customarr = [];
//Заполняем период за 7 дней
    /*$('#updateDateSevenDays').on("click", function(e){
        arr7.length = 0;
        arr14.length = 0;
        arr30.length = 0;
        /!* //Вчерашнее число
         var yesterdayRaw = new Date(new Date() - 24*3600*1000).toLocaleString();
         var yesterday =yesterdayRaw.substr(0, yesterdayRaw.length - 10)*!/
        // 7 дней назад
        /!*var endDateRaw = new Date(new Date() - ((24*3600*1000)*7)).toLocaleString();
         var endDate =endDateRaw.substr(0, endDateRaw.length - 10)*!/

        //Вчерашнее число
        var yesterday = moment().subtract(1, 'days').format('DD.MM.YYYY');
        $('#datepickerTo').val(yesterday);
        // 7 дней назад
        var endDate = moment().subtract(7, 'days').format('DD.MM.YYYY');
        $('#datepickerFrom').val(endDate);
        //alert(endDate);
        /!*$('#datepickerFrom').val(endDate);
         $('#datepickerTo').val(yesterday);*!/
        var start = moment().subtract(7, 'days');
        var end   = moment().subtract(1, 'days');
        var range = moment().range(start, end);

        //alert('hi');
        range.by('days', function(moment) {
            //console.log(moment.format('DD.MM.YYYY'));
            arr7.push(moment.format('YYYY-MM-DD'));
        });
    });*/

//Заполняем период за 14 дней
    /*$('#updateDateFourteenDays').on("click", function(e){
        arr7.length = 0;
        arr14.length = 0;
        arr30.length = 0;
        //Вчерашнее число
        var yesterday = moment().subtract(1, 'days').format('DD.MM.YYYY');
        $('#datepickerTo').val(yesterday);
        // 7 дней назад
        var endDate = moment().subtract(13, 'days').format('DD.MM.YYYY');
        $('#datepickerFrom').val(endDate);

        var start = moment().subtract(14, 'days');
        var end   = moment().subtract(1, 'days');
        var range = moment().range(start, end);

        range.by('days', function(moment) {
            arr14.push(moment.format('YYYY-MM-DD'));
        });

    });*/

//Заполняем период за 30
    /*$('#updateDateThirtyDays').on("click", function(e){
        arr7.length = 0;
        arr14.length = 0;
        arr30.length = 0;
        //Вчерашнее число
        var yesterday = moment().subtract(1, 'days').format('DD.MM.YYYY');
        $('#datepickerTo').val(yesterday);
        // 7 дней назад
        var endDate = moment().subtract(29, 'days').format('DD.MM.YYYY');
        $('#datepickerFrom').val(endDate);

        var start = moment().subtract(30, 'days');
        var end   = moment().subtract(1, 'days');
        var range = moment().range(start, end);

        //alert('hi');
        range.by('days', function(moment) {
            //console.log(moment.format('DD.MM.YYYY'));
            arr30.push(moment.format('YYYY-MM-DD'));
        });
    });*/
	$('#datepickerOneDay').focus(function(){
        $('#datepickerFrom').val("");
        $('#datepickerTo').val("");
    });

    $('#datepickerFrom').focus(function(){
        $('#datepickerOneDay').val("");
    });

    $('#datepickerTo').focus(function(){
        $('#datepickerOneDay').val("");
    });

    //Обновление за 1 день
    $('a#idgirl').on("click", function(e){

        var translaterDetail = $(this).parent().next().next().next().next().next().next().next().next().text();
        //Уаление строк с преиущей етализации
        $(".detail").remove();
        $(".toggle ").removeClass("piu");

        e.preventDefault();
        var idGir = $(this).text();

        if($('#datepickerOneDay').val().length>1 && $('#datepickerTo').val().length<1 && $('#datepickerFrom').val().length<1){
            var currentDate = $('#datepickerOneDay').val();
            $.ajax({
                type: 'POST',
                url: './statistics/updateStatisticsByDate',
                data:'userID='+document.getElementById('userID').value+
                '&currentDate='+currentDate+
                '&idGir='+idGir,
                complete: function(response, status){
                    var res = response.responseText;
                    //var resObj = eval("(" + res + ")");
                    var resObj = JSON.parse(res);
                    if(resObj.broadcast[0]!==undefined){
                        var sentLettersAllGirls = resObj.oneGirlsFirstSentLetters;//Количество отправленных первых писем ОБЬЕКТ
                        var responseLettersAllGirls = resObj.oneGirlsResponseLetters;//Количество отправленных улыбок ОБЬЕКТ
                        var girlsSmilesAllGirls = resObj.oneGirlsSmile;//Количество ответов на входящие письма ОБЬЕКТ
                        document.getElementById("countResponseLetters"+idGir).innerHTML = responseLettersAllGirls;
                        document.getElementById("countSetnLetters"+idGir).innerHTML = sentLettersAllGirls;
                        document.getElementById("countSentSmiles"+idGir).innerHTML = girlsSmilesAllGirls;

                        document.getElementById("dateLastBroadcast"+idGir).innerHTML = resObj.broadcast[0].dateLastBroadcast;
                        document.getElementById("countsentMessageBroadcast"+idGir).innerHTML = resObj.broadcast[0].countsentMessageBroadcast;
                        document.getElementById("dateNextBroadcast"+idGir).innerHTML = resObj.broadcast[0].dateNextBroadcast;
                    }else{
                        var sentLettersAllGirls = resObj.oneGirlsFirstSentLetters;//Количество отправленных первых писем ОБЬЕКТ
                        var responseLettersAllGirls = resObj.oneGirlsResponseLetters;//Количество отправленных улыбок ОБЬЕКТ
                        var girlsSmilesAllGirls = resObj.oneGirlsSmile;//Количество ответов на входящие письма ОБЬЕКТ

                        document.getElementById("countResponseLetters"+idGir).innerHTML = responseLettersAllGirls;
                        document.getElementById("countSetnLetters"+idGir).innerHTML = sentLettersAllGirls;
                        document.getElementById("countSentSmiles"+idGir).innerHTML = girlsSmilesAllGirls;

                        document.getElementById("dateLastBroadcast"+idGir).innerHTML ='Broadcast был до установки приложения';
                        document.getElementById("countsentMessageBroadcast"+idGir).innerHTML ='Broadcast был до установки приложения';
                        document.getElementById("dateNextBroadcast"+idGir).innerHTML = 'Broadcast был до установки приложения';
                    }
                }
            });
            //Оюновление данных по дате за несколько дней
        }else if($('#datepickerFrom').val().length>1 && $('#datepickerTo').val().length>1){
            var dateFrom = $('#datepickerFrom').val();
            var DateTo = $('#datepickerTo').val();

            //ОБРАБТКА ВВЕЕНОЙ ПОЛЬЗОВАТЕЛЕМ ДАТЫ
            if(arr7.length==0 && arr14.length==0 && arr30.length==0){
                //C какого дня
                var from = $('#datepickerFrom').val();
                var arrfrom = from.split('.');
                //ПО какой день
                var to = $('#datepickerTo').val();
                var arrto = to.split('.');
                //Разница в днях период
                var start   = moment([arrfrom[2], arrfrom[1], arrfrom[0]]).subtract(1, 'month');
                var end = moment([arrto[2], arrto[1], arrto[0]]).subtract(1, 'month');
                var range = moment().range(start, end);
				if(end.diff(start)<0 || end.isSame(start)){
                    alert("Не правильный интервал");
                    return false;
                }
                //Формируем массив с датами
                range.by('days', function(moment) {
                    customarr.push(moment.format('YYYY-MM-DD'));
                });
            }
            //Вчерашнее число
            //var yesterday = moment().subtract(1, 'days').format('DD-MM-YYYY');
            //$('#datepickerTo').val()

            $(this).addClass("piu");

            $.ajax({
                type: 'POST',
                url: './statistics/updateStatisticsByDate',
                data:'userID='+document.getElementById('userID').value+
                '&dateFrom='+dateFrom+
                '&DateTo='+DateTo+
                '&idGir='+idGir+
                '&arr7='+arr7+
                '&arr14='+arr14+
                '&arr30='+arr30+
                '&customarr='+customarr,
                beforeSend: function(){
                    $(".myloader").addClass("loading")
                },
                complete: function(response, status){
                    $(".myloader").removeClass("loading");
                    var res = response.responseText;
                    //var resObj = eval("(" + res + ")");
                    var resObj = JSON.parse(res);
                    var lengtLoop = resObj.oneGirlsResponseLettersDetail.length;
                    console.log(resObj.oneGirlsResponseLettersDetail);
                    var arr;
                    if(arr7.length>0){
                        arr = arr7;
                    }
                    if(arr14.length>0){
                        arr = arr14;
                    }
                    if(arr30.length>0){
                        arr = arr30;
                    }
                    if(customarr.length>0){
                        arr = customarr;
                    }
                    var ttt="ttt";
                    //alert(arr);
                    for(var i=0; i<arr.length; i++){
                        if(resObj.broadcast[0]!==undefined){
                            var countsentMessageBroadcast = resObj.broadcast[0].countsentMessageBroadcast;
                            document.getElementById("countsentMessageBroadcast"+idGir).innerHTML = countsentMessageBroadcast;
                            var dateLastBroadcast = resObj.broadcast[0].countsentMessageBroadcast;
                            document.getElementById("dateLastBroadcast"+idGir).innerHTML = dateLastBroadcast;
                            $('a.piu').closest('tr').after('' +
                                '<tr class="detail">' +
                                '<td>'+" "+'</td>' +
                                '<td>'+" "+'</td>' +
                                '<td id="dateDetail">'+arr[i]+'</td>' +
                                '<td id="sentSmilesDetail">'+resObj.oneGirlsSmileArrDetail[0][arr[i]][0]+'</td>' +
                                '<td id="sentLettersDetail">'+resObj.oneGirlsFirstSentLettersDetail[0][arr[i]][0]+'</td>' +
                                '<td id="responseLettersDetail">'+resObj.oneGirlsResponseLettersDetail[0][arr[i]][0]+'</td>' +
                                '<td id="lastBroadcastDetail">'+resObj.broadcast[0].dateLastBroadcast+'</td>' +
                                '<td id="sentettersToBroadcastDetail">'+resObj.broadcast[0].countsentMessageBroadcast+'</td>' +
                                '<td id="nextBroadcastDetail">'+resObj.broadcast[0].dateNextBroadcast+'</td>' +
                                '<td id="translaterDetail">'+translaterDetail+'</td>' +
                                '</tr>');
                        }else{
                            var countsentMessageBroadcast = "Broadcast был до установки приложения";
                            document.getElementById("countsentMessageBroadcast"+idGir).innerHTML = countsentMessageBroadcast;
                            var dateLastBroadcast = "Broadcast был до установки приложения";
                            document.getElementById("dateLastBroadcast"+idGir).innerHTML = dateLastBroadcast;
                            $('a.piu').closest('tr').after('' +
                                '<tr class="detail">' +
                                '<td>'+" "+'</td>' +
                                '<td>'+" "+'</td>' +
                                '<td id="dateDetail">'+arr[i]+'</td>' +
                                '<td id="sentSmilesDetail">'+resObj.oneGirlsSmileArrDetail[0][arr[i]][0]+'</td>' +
                                '<td id="sentLettersDetail">'+resObj.oneGirlsFirstSentLettersDetail[0][arr[i]][0]+'</td>' +
                                '<td id="responseLettersDetail">'+resObj.oneGirlsResponseLettersDetail[0][arr[i]][0]+'</td>' +
                                '<td id="lastBroadcastDetail">'+'Broadcast был до установки приложения'+'</td>' +
                                '<td id="sentettersToBroadcastDetail">'+'Broadcast был до установки приложения'+'</td>' +
                                '<td id="nextBroadcastDetail">'+'Broadcast был до установки приложения'+'</td>' +
                                '<td id="translaterDetail">'+translaterDetail+'</td>' +
                                '</tr>');
                        }}

                    customarr.length = 0;
                    arr.length = 0; // очистить всё

                    var sentLettersAllGirls = resObj.oneGirlsFirstSentLetters;//Количество отправленных первых писем ОБЬЕКТ
                    var responseLettersAllGirls = resObj.oneGirlsResponseLetters;//Количество отправленных улыбок ОБЬЕКТ
                    var girlsSmilesAllGirls = resObj.oneGirlsSmile;//Количество ответов на входящие письма ОБЬЕКТ

                    var dateNextBroadcast = resObj.broadcast[0].dateNextBroadcast;
                    document.getElementById("countResponseLetters"+idGir).innerHTML = responseLettersAllGirls;
                    document.getElementById("countSetnLetters"+idGir).innerHTML = sentLettersAllGirls;
                    document.getElementById("countSentSmiles"+idGir).innerHTML = girlsSmilesAllGirls;
                    document.getElementById("dateNextBroadcast"+idGir).innerHTML = dateNextBroadcast;

                }
            });
        }
        else {

            alert("Выберете период");



            /*$("#dialog").dialog({
             autoOpen: false, // диалоговое окно будет открыто при вызове метода dialog("open")
             show: "blind",   // эффект для отображения окна
             hide: "explode"  // эффект для скрытия окна
             });

             $("#dialog").dialog("open");*/


        }
    });

}); // end script