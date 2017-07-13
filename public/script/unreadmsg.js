$(document).ready(function(){
    $('a#idgirl').on("click", function(e){

        $(".detail").remove();
        $(".toggle ").removeClass("piu");
        $(this).addClass("piu");
        e.preventDefault();

        var idGirl = $(this).text();
        /*$(this).removeClass("piu");
        $(this).addClass("piu");*/
        $.ajax({
            type: 'POST',
            url: './handlingUnreadmsg',
            data:'idGirl='+idGirl,
            beforeSend: function(){
                $(".myloader").addClass("loading")
            },
            complete: function(response, status){

                $(".myloader").removeClass("loading");
                //alert(idGirl);
                console.log(response.responseText);
                var res = response.responseText;
                var resObj = JSON.parse(res);
                //console.log(resObj.date);
                //var resObj = JSON.parse(res);

                var yesterday = moment().subtract(1, 'days').format('YYYY-MM-DD');
                var today = moment().format('YYYY-MM-DD');
                var y=0,t=0;

                resObj.date.forEach(function(item, i, arr) {
                    if(item==today)y++;
                });
                resObj.date.forEach(function(item, i, arr) {
                    if(item==yesterday)t++;
                });
                console.log(y);
                console.log(t);

                document.getElementById("today"+idGirl).innerHTML = t;
                document.getElementById("yesterday"+idGirl).innerHTML = y;

            }

        });
        //console.log(yesterday);
        //$(this).removeClass("piu");




    });
});