$(document).ready(function () {
    var name = '';
    chrome.tabs.query({active: true, currentWindow: true}, function (tabs) {
        chrome.tabs.sendMessage(tabs[0].id, {sender: "popup"}, function (response) {
            console.log(response)
            name = response.obj.name;
            var total_messages = Number(cleaning(response.obj.res_inbox)) + Number(cleaning(response.obj.res_outbox));
            var message = '';
            var message_title = '';

            var messageBlack = "<p class='blackStatistic'> Внимание! ID в черном списке </p>";
            if (response.obj.idblack === undefined) {
                response.obj.desc = "";
                messageBlack = "";
            }

            if (total_messages > 0) {
                message_title = 'Внимание! с прользователем "<span>' + response.obj.name + '</span>" была переписка на сайте!';
                message = '<p class="true">Всего сообщений: ' + total_messages + '</p>' + '<div class="left"><p>Последние Входящие:</p>' + tow_last_messages(response.obj.res_inbox, true) + '</div><div class="middle_hr"></div>' + '<div class="right"><p>Последние Исходящие:</p>' + tow_last_messages(response.obj.res_outbox) + '</div>';
            } else {
                message_title = 'С прользователем "<span>' + response.obj.name + '</span>" не было переписки на сайте!';
                message = '<p class="false">С ' + response.obj.name + ' не было переписки</p>';
            }
            
            $('#origin').html(message_title);
            $('#onestep').append(message);
            $('#backStatistiks').append(response.obj.desc);
            $('#backStatistiksh').append(messageBlack);
        });
    });

    $('body').on('click', 'a', function (e) {
        var link = e.target.href;
        link = link.replace(/chrome-extension:\/\/\w+/, "http://www.natashaclub.com");
        chrome.tabs.create({url: link});
        return false;
    });

    function cleaning(html) {
        return $(html).find(".small:last").find(".panel:last b:first").html();
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

});
