el = document.getElementsByClassName('GreenText'); // тот элемент, после которого хотим запустить
var nickName = $(el).html();
var idkn = $(el).next().next().html();
if (nickName === undefined && idkn === undefined) {//если элемент не загрузился
    try_el = function () {
        el = document.getElementsByClassName('GreenText');
        nickName = $(el).html();
        idkn = $(el).next().next().html();
        if (nickName !== undefined) {
            clearInterval(interv);
            start(nickName, idkn);
        }
    };
    interv = setInterval(try_el, 0);
} else {
    start(nickName, idkn);
}

statistics();
function statistics() {
    obj = {};
    obj['values'] = [];
    var alias = window.location.href;
//----------------------------------------------------------------------------------------------------------------------
    //ОБРАБОТКА - ОТПРАВЛЕННЫХ УЛЫБОК И ОБНОВЛЕНИЕ ДАТЫ СЛЕДУЮЩЕГО БРОАДКАСТА
    //Посылаем AJAX POST чтобы получить данные о отправленных улыбках
    var res_communicator = my_find_messages("ajaxaction=cc.ShowHide&what=ShowVKissed", 'https://www.natashaclub.com/ajax.action.php');
    //ID анкеты
    //Данные которые пришли POSTом
    obj.res_communicator = res_communicator.responseText;
    var updateDateNextBroadcast = $((my_find_messages("", "https://www.natashaclub.com/search.php")).responseText).find("span.BroadcastSpan").text();
    updateDateNextBroadcast = updateDateNextBroadcast.match(/\d+/g);
    var flagUpdate;
    if (updateDateNextBroadcast != null) {
        flagUpdate = 1;
    } else {
        flagUpdate = 0;
    }
    //Обновление даты следующаго броадкаста
    //ID анкеты
    
    var query_t = my_find_messages("", 'https://www.natashaclub.com/member.php');
    var idGirl = ($((query_t).responseText).find('a.l_profile_edit').attr('href')).substring(47);
//    idGirl = '1000433755';
    obj = {};
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = showAsyncRequestComplete; // Implemented elsewhere.
    //Даные отправляемые POSTом
    var sentString = "";
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = showAsyncRequestComplete; // Implemented elsewhere.
    //Даные отправляемые POSTом
    if (flagUpdate == 1) {
        sentString = "idGirl=" + idGirl + "&" + "smiles=" + smilesAndData(res_communicator.responseText) + "&" + "updateDateNextBroadcast=" + updateDateNextBroadcast;
    } else {
        sentString = "idGirl=" + idGirl + "&" + "smiles=" + smilesAndData(res_communicator.responseText) + "&" + "updateDateNextBroadcast=available";
    }
    //console.log(smilesAndData(res_communicator.responseText));
    xhr.open("POST", 'https://www.agency-stat.com.ua/statistics/handlingSentSmiles', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(sentString);
    // Завершение асинхронного запроса
    function showAsyncRequestComplete()
    {
        // только при состоянии "complete"
        if (xhr.readyState == 4) {
            //console.log(xhr.responseText);
            //console.log("--------------------------------------------------------------------------------");
            //console.log("sentString---------");
            //console.log(sentString);
        }
    }
//----------------------------------------------------------------------------------------------------------------------




//----------------------------------------------------------------------------------------------------------------------
//ОБРАБОТКА ПЕРВЫХ ОТПРАВЛЕННЫХ ПИСЕМ


    var patternreferer = /\d*\w*\.html/;
    var referrer = document.referrer;
    var firstIndexReferer = referrer.search(patternreferer);

    var strpathname = window.location.pathname + window.location.search;
    var patternpathname = /compose\.php\?ID\=/;
    var secondIndexPathname = strpathname.search(patternpathname);

    $("td input.no").on('click', function () {
        var pattern = /\?ID=\w+/;
        if (firstIndexReferer != -1 && secondIndexPathname != -1) {

            //ID куда отправляем письмо первый раз
            var idToSent = window.location.search.substr(4);
            //ID анкеты
            var idGirl = ($((my_find_messages("", 'https://www.natashaclub.com/member.php')).responseText).find('a.l_profile_edit').attr('href')).substring(47);
            obj = {};
            var xhr = new XMLHttpRequest();
            //Даные отправляемые POSTом
            sentString = "idToSent=" + idToSent + "&" + "idGirl=" + idGirl;

            xhr.open("POST", 'https://www.agency-stat.com.ua/statistics/handlingFirstLetterSent', true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(sentString);
            function showAsyncRequestComplete() {
                xhr.onreadystatechange = showAsyncRequestComplete; // Implemented elsewhere.
                // Завершение асинхронного запроса
                //console.log(xhr.responseText);
                // только при состоянии "complete"
                if (xhr.readyState == 4) {

                    //console.log("--------------------------------------------------------------------------------");
                    //console.log(idToSent);
                    //console.log("--------------------------------------------------------------------------------");
                    //console.log("sentString---------");
                    //console.log(sentString);
                }
            }
        }
    });
//----------------------------------------------------------------------------------------------------------------------



//----------------------------------------------------------------------------------------------------------------------
//Обработка BROADCAST события
    var dateNextBroadcast = $((my_find_messages("", "https://www.natashaclub.com/search.php")).responseText).find("span.BroadcastSpan").text();
    dateNextBroadcast = dateNextBroadcast.match(/\d+/g);
    var flag;
    if (dateNextBroadcast != null) {
        flag = 1;
    } else {
        flag = 0;
    }
    //$(document).on('click', function () {
    $("form input.no").on('click', function () {
        if (window.location.pathname == "/search_result.php") {
            //Колличество отосланных писем по  броадкасту
            var countLettersBroadcast = ($("table.text2 tbody tr").eq(1).text()).substring(0, 3);
            //ID анкеты
            var idGirl = ($((my_find_messages("", 'https://www.natashaclub.com/member.php')).responseText).find('a.l_profile_edit').attr('href')).substring(47);
            obj = {};
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = showAsyncRequestComplete; // Implemented elsewhere.
            //Даные отправляемые POSTом
            var sentString = "";
            if (flag == 1) {
                sentString = "countLettersBroadcast=" + countLettersBroadcast + "&" + "idGirl=" + idGirl + "&" + "dateNextBroadcast=" + dateNextBroadcast;
            } else {
                sentString = "countLettersBroadcast=" + countLettersBroadcast + "&" + "idGirl=" + idGirl;
            }
            xhr.open("POST", 'https://www.agency-stat.com.ua/statistics/handlingBroadcasts', true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(sentString);
            // Завершение асинхронного запроса
            function showAsyncRequestComplete() {
                // только при состоянии "complete"
                if (xhr.readyState == 4) {

                }
            }
        }
    });
//----------------------------------------------------------------------------------------------------------------------



//----------------------------------------------------------------------------------------------------------------------
//ОБРАБОТКА ответов нв входяшие письма
    var pattern = /inbox\.php\?message\=/;
    var referrer = document.referrer;
    var firstIndex = referrer.search(pattern);

    var str = window.location.pathname + window.location.search;
    var pattern2 = /compose\.php\?ID\=\d*\w*\&message\=/;
    var secondIndex = str.search(pattern2);
    //console.log(secondIndex);
    $("td input.no").on('click', function () {
        if (secondIndex != -1 && firstIndex != -1) {
            //ID куда отправляем письмо первый раз
            var idToSent = window.location.search.substr(4);
            //ID анкеты
            var idGirl = ($((my_find_messages("", 'https://www.natashaclub.com/member.php')).responseText).find('a.l_profile_edit').attr('href')).substring(47);
            obj = {};
            var xhr = new XMLHttpRequest();
            //Даные отправляемые POSTом
            sentString = "count=1&idGirl=" + idGirl;
            xhr.open("POST", 'https://www.agency-stat.com.ua/statistics/handlingresponseletters', true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(sentString);
            function showAsyncRequestComplete() {
                xhr.onreadystatechange = showAsyncRequestComplete; // Implemented elsewhere.
                // Завершение асинхронного запроса
                // только при состоянии "complete"
                if (xhr.readyState == 4) {
                    //console.log("--------------------------------------------------------------------------------");
                    //console.log(idToSent);
                    //console.log("--------------------------------------------------------------------------------");
                    //console.log("sentString---------");
                    //console.log(sentString);
                }
            }
        }
    });
//----------------------------------------------------------------------------------------------------------------------
    function smilesAndData(html) {
        var arr = new Array();//!!!!!!!!!!!!!!!!!111
        var Rows = $(html).find('tr.table');
        var str;
        for (var i = 0; i < Rows.length; i++) {
            var rawSmiles = $(Rows[i]).find('td:eq(2)').text();
            var start = rawSmiles.indexOf("р");
            var end = rawSmiles.indexOf(")");
            var smiles = " " + (rawSmiles.substr(0, start)).trim();
            var data1 = $(Rows[i]).find('td:eq(3)').text();
            if (data1.search(/2015/))
                ;
            var data = data1 + "|";
            str += smiles + "," + " " + data;
        }
        return str;
    }

    function my_find_messages(row, link) {
        var res = $.ajax({
            url: link,
            type: 'post',
            async: false,
            data: row
        });
        return res;
    }
}

function start(nickName, idkn) {
    var obj = {};
    obj['values'] = [];
    var alias = window.location.href;
    if (nickName !== undefined) {
        var show_message = false;
        var inbox = 'http://www.natashaclub.com/inbox.php';
        var outbox = 'http://www.natashaclub.com/outbox.php';
        var res_inbox = find_messages(nickName, inbox);
        var res_outbox = find_messages(nickName, outbox);
        var total_messages = Number(cleaning(res_inbox.responseText)) + Number(cleaning(res_outbox.responseText));
        if (total_messages > 0) {
            show_message = true;
        }

        //----------------------------------------------------------------------------------------------------------
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = showAsyncRequestComplete; // Implemented elsewhere.
        //Даные отправляемые POSTом
        var sentString = "";
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = showAsyncRequestComplete; // Implemented elsewhere.
        //Даные отправляемые POSTом
        var originIDRaw = $("a.l_profile_edit").attr("href");
        var originID = originIDRaw?originIDRaw.substr(-10):'';
        sentString = "nickNameBlack=" + nickName + "&" + "idGirlBlack=" + idkn + "&" + "originID=" + originID;
        xhr.open("POST", 'https://www.agency-stat.com.ua/statistics/handlingBlackListInExtensions', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(sentString);
        // Завершение асинхронного запроса
        function showAsyncRequestComplete()
        {
            // только при состоянии "complete"
            if (xhr.readyState == 4) {
                //var str = xhr.responseText.split(",");

                var str = JSON.parse(xhr.responseText);
                if(str){
                    var idblack = str.idblack; 
                    var desc = str.desc;
                }
                obj.idblack = idblack;
                obj.desc = desc;

                obj.res_inbox = res_inbox.responseText;
                obj.res_outbox = res_outbox.responseText;
                obj.name = nickName;
                
                if (show_message) {
                    show_msg(obj);
                }
            }

        }

        //----------------------------------------------------------------------------------------------------------
        obj.res_inbox = res_inbox.responseText;
        obj.res_outbox = res_outbox.responseText;
        obj.name = nickName;
        chrome.runtime.sendMessage({status: 1});
    }

    chrome.runtime.onMessage.addListener(
            function (request, sender, sendResponse) {
                sendResponse({obj});
            }
    );

    function find_messages(nickName, link) {
        var res = $.ajax({
            url: link,
            type: 'post',
            async: false,
            data: "filterID=" + nickName
        });
        return res;
    }

    function my_find_messages(row, link) {
        var res = $.ajax({
            url: link,
            type: 'post',
            async: false,
            data: row
        });
        return res;
    }

    function cleaning(html) {
        return $(html).find(".small:last").find(".panel:last b:first").html();
    }

    function show_msg(obj) {

        var modal = $('#searchModal');
        if (modal.length == 0) {
            modal = $('<div>', {id: 'searchModal', class: 'modal'}).append(
                    $('<div>', {class: 'modal-content'})
                    .append($('<span>', {class: 'close', text: "x"}))
                    .append($('<h2>', {id: 'origin',html:'ВНИМАНИЕ! С прользователем &laquo;<span></span>&raquo; уже был обмен письмами!'}))
                    .append($('<div>', {id: 'onestep'}))
                    .append($('<div>', {style: 'clear: both'}))
                    .append($('<div>', {id: 'backStatistiks'})
                            .append($('<h2 >', {id: 'backStatistiksh'})))
                    .append($('<div>', {style: 'clear: both'}))
                    );
        }

        var name = obj.name;
        var total_messages = Number(cleaning(obj.res_inbox)) + Number(cleaning(obj.res_outbox));
        var message = '';
            var message_title = '';

        var messageBlack = "<p class='blackStatistic'> Внимание! ID в черном списке </p>";
        if (obj.idblack === undefined) {
            obj.desc = "";
            messageBlack = "";
        }

        if (total_messages > 0) {
            message_title = 'Внимание! с прользователем "<span>' + obj.name + '</span>" была переписка на сайте!';
            message = '<p class="true">Всего сообщений: ' + total_messages + '</p>' + '<div class="list_wrap"><div class="left"><p>Последние Входящие:</p>' + tow_last_messages(obj.res_inbox, true) + '</div><div class="middle_hr"></div>' + '<div class="right"><p>Последние Исходящие:</p>' + tow_last_messages(obj.res_outbox) + '</div><div style="clear: both;"></div></div>';
        } else {
            message_title = 'С прользователем "<span>' + obj.name + '</span>" не было переписки на сайте!';
            message = '<p class="false">С ' + obj.name + ' не было переписки</p>';
        }

        modal.find('#origin').html(message_title);
        modal.find('#onestep').append(message);
        modal.find('#backStatistiks').append(obj.desc);
        modal.find('#backStatistiksh').append(messageBlack);

        $('body').append(modal);

        modal.show();

        $('.close').on('click', function () {
            modal.hide();
        });

        $(modal).on('click', function (e) {
            modal.hide();
        });
    }


    function tow_last_messages(html, inbox) {
        var res = $(html).find(".small").slice(-1).html();
        res = $(res).get(0);
        $(res).find('tr').slice(0, 3).remove();
        var count_tr = res.getElementsByTagName('tr').length;
        count_tr = (count_tr > 4) ? count_tr - 3 : ((inbox) ? 3 : 1);
        $(res).find('tr').slice(-count_tr).remove();
        $(res).find('img').remove();
        $(res).find('input').remove();
        return $(res).html();
    }
}