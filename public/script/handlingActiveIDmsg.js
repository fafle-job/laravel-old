$(document).ready(function(){
    $('a#idgirl').on("click", function(e){

        $(".detail").remove();
        $(".toggle ").removeClass("piu");
        $(this).addClass("piu");
        e.preventDefault();
        //Позавчерашнее число
        var yesterday = moment().subtract(1, 'days').format('YYYY-MM-DD');
        var nowDay = moment().format('YYYY-MM-DD');
        var idGirl = $(this).text();
        $.ajax({
            type: 'POST',
            url: './handlingActiveIDmsg',
            data:'idGirl='+idGirl+'&yesterday='+yesterday+'&nowDay='+nowDay,
            beforeSend: function(){
                $(".myloader").addClass("loading")
            },
            complete: function(response, status){
                $(".myloader").removeClass("loading");
                var res =(response.responseText);

                console.log(res);
                var resRaw = JSON.parse(res);
                var resObj = resRaw.date;

                var arr2=[], i=0;
                for(var i=0; i<resObj.length; i++ ){
                    for (var j=i+1; j< resObj.length; j++){
                        if(resObj[i][3]!=resObj[j][3]){
                            continue
                        }else{
                            arr2.push(resObj[i]);
                            arr2.push(resObj[j]);
                        }
                    }
                }

                function unique(arr) {
                    var result = [];
                    nextInput:
                        for (var i = 0; i < arr.length; i++) {
                            var str = arr[i]; // для каждого элемента
                            for (var j = 0; j < result.length; j++) { // ищем, был ли он уже?
                                if (result[j] === str) continue nextInput; // если да, то следующий
                            }
                            result.push(str);
                        }
                    return result;
                }

                for(var i=0; i<arr2.length; i++ ){
                    for (var j=i+1; j< arr2.length; j++){
                        if(arr2[j][5] == arr2[i][5]){
                            arr2=unique(arr2);
                        }
                    }
                }


                Array.prototype.unique = function() {
                    var o = {}, i, l = this.length, r = [];
                    for(i=0; i<l;i++) o[this[i]] = this[i];
                    for(i in o) r.push(o[i]);
                    return r;
                };

                function in_array(value, array) {
                    for(var i=0; i<array.length; i++){
                        if(value == array[i]) return true;
                    }
                    return false;
                }

                var arr3=[];
                for(var i=0; i<=arr2.length; i++ ){
                    for (var j=i+1; j< arr2.length; j++){
                        if(arr2[j][3]==arr2[i][3]){
                            var date1 = new Date(arr2[i][5].replace(/(\d+)-(\d+)-(\d+) (\d+):(\d+):(\d+)/, '$1/$2/$3 $4:$5:$6'));
                            var date2 = new Date(arr2[j][5].replace(/(\d+)-(\d+)-(\d+) (\d+):(\d+):(\d+)/, '$1/$2/$3 $4:$5:$6'));
                            var date = date1-date2;
                            if(date<0){date=date*(-1)}
                            var endDate= date*1.666E-5;
                            if(endDate<=60){
                                if(!in_array(arr2[i], arr3))
                                arr3.push(arr2[i]);
                                //arr3.push(arr2[j]);
                            }
                        }
                    }
                }
                /*var arr4=[];
                for(var i=0; i<arr3.length; i++ ){
                    for (var j=i+1; j< arr3.length; j++){
                        /!*console.log(arr3[j][5]);
                        console.log('-');
                        console.log(arr3[i][5]);*!/
                        if(arr3[j][5] === arr3[i][5]){

                            unique(arr3[i]);
                            /!*delete  arr3[j][5];
                            delete  arr3[j][5];*!/
                        }
                        arr4=arr3;
                    }
                }*/
                //console.log(arr3);
                var yesterday = moment().subtract(1, 'days').format('YYYY-MM-DD');
                var nowDay = moment().format('YYYY-MM-DD');
                /*
                 for (var k in arr4) {
                 if(arr4[k][5].indexOf(nowDay) || arr4[k][5].indexOf(yesterday)) {
                 //console.log(arr4[k][5]);
                 //console.log(arr4[k][5]);
                 }else{
                 delete arr4[k];
                 }
                 }
                 console.log(arr4);*/
                //console.log(arr4);//////////////////////////////////////////////////////////////////////////////////////



                var res = response.responseText;
                var resObj = JSON.parse(res);
                var yesterday = moment().subtract(1, 'days').format('YYYY-MM-DD');
                var today = moment().format('YYYY-MM-DD');
                var y=0,t=0;

                resObj.date.forEach(function(item, i, arr) {
                    if(item==today)y++;
                });
                resObj.date.forEach(function(item, i, arr) {
                    if(item==yesterday)t++;
                });
                for(var i=0; i<arr3.length; i++){
                    $('a.piu').closest('tr').after('' +
                        '<tr class="detail">' +
                        '<td id="detail" colspan="1">'+arr3[i][3]+'</td>' +
                        '<td id="detail" colspan="2">'+arr3[i][5]+'</td>' +
                        '</tr>');
                }

                /* document.getElementById("today"+idGirl).innerHTML = t;
                 document.getElementById("yesterday"+idGirl).innerHTML = y;*/

            }

        });
    });
});