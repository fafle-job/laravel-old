$(document).ready(function(){
// Теперь  с помощью AJAX
    $('#updateavakn').on("click", function(e){
        $.ajax({
            type: 'get',
            url: './accounts/updateava',

            /*data: 'dateFrom='+ dateFrom+'&calendarBy=' + calendarBy +
            "&userID="+document.getElementById("userID").value+'&idOneGirl='+idOneGirl,*/
            data: "accountid="+document.getElementById("accountid").value+"&password="+document.getElementById("password").value,
            complete: function(response, status){
                //var res = JSON.parse(response.responseText);
                /*var sentLetters = res.oneGirlsFirstSentLetters//Количество отправленных первых писем
                var girlsSmiles =  res.oneGirlsSmile//Количество отправленных улыбок
                var responseLetters = res.oneGirlsResponseLetters//Количество ответов на входящие письма
                var counrSentSmilesIdTr= document.getElementById("counrSentSmilesIdTr"+idOneGirl).innerHTML = girlsSmiles;//Количество отправленных улыбок в TR
                var counrSentLettersIdTr= document.getElementById("countSentettersIdTr"+idOneGirl).innerHTML = sentLetters;//Количество отправленных первых писем в TR
                var countResponseLettersIdTr= document.getElementById("countResponseLettersIdTr"+idOneGirl).innerHTML = responseLetters;//Количество ответов на входящие письма в TR
                //console.log($(this).parent().find('.calendarFrom'));*/
                window.location.reload()
                //console.log(response.responseText);
            }
        });
    });
});