$(document).ready(function(){
    // Устанавливаем обработчик потери фокуса для всех полей ввода текста

    //statisticstranslator
    
    $('input#idGirl, input#unicId').unbind().blur( function(){
        // Для удобства записываем обращения к атрибуту и значению каждого поля в переменные
        var id = $(this).attr('id');
        var val = $(this).val();
        // После того, как поле потеряло фокус, перебираем значения id, совпадающие с id данного поля
        switch(id)
        {
            // Проверка поля "Имя"
            case 'idGirl':
                var rv_name = /^[0-9]{10}$/; // используем регулярное выражение
                // Eсли длина 10 символов, оно не пустое и удовлетворяет рег. выражению,
                // то добавляем этому полю класс .not_error,
                // и ниже в контейнер для ошибок выводим слово " Принято", т.е. валидация для этого поля пройдена успешно
                if( val != '' && rv_name.test(val))
                {
                    $(this).addClass('not_error');
                    $(this).next('.error-box').text('Принято')
                        .css('color','green')
                        .animate({'paddingLeft':'10px'},400)
                        .animate({'paddingLeft':'5px'},400);
                }
                // Иначе, мы удаляем класс not-error и заменяем его на класс error, говоря о том что поле содержит ошибку валидации,
                // и ниже в наш контейнер выводим сообщение об ошибке и параметры для верной валидации
                else
                {
                    $(this).removeClass('not_error').addClass('error');
                    $(this).next('.error-box').html('Поле обязательно для заполения и должо содержать 10 символов')
                        .css('color','red')
                        .animate({'paddingLeft':'10px'},400)
                        .animate({'paddingLeft':'5px'},400);
                }
                break;


            // Проверка поля "Имя"
            case 'unicId':
                var rv_unicid = /\w\w\w[0-9]{10}/; // используем регулярное выражение
                //var rv_name = /^[0-9]{10}$/; // используем регулярное выражение
                // Eсли длина 10 символов, оно не пустое и удовлетворяет рег. выражению,
                // то добавляем этому полю класс .not_error,
                // и ниже в контейнер для ошибок выводим слово " Принято", т.е. валидация для этого поля пройдена успешно
                // if( val != '' && rv_name.test(val))
                if( val != '' && rv_unicid.test(val))
                {
                    $(this).addClass('not_error');
                    $(this).next('.error-box').text('Принято')
                        .css('color','green')
                        .animate({'paddingLeft':'10px'},400)
                        .animate({'paddingLeft':'5px'},400);
                }
                // Иначе, мы удаляем класс not-error и заменяем его на класс error, говоря о том что поле содержит ошибку валидации,
                // и ниже в наш контейнер выводим сообщение об ошибке и параметры для верной валидации
                else
                {
                    $(this).removeClass('not_error').addClass('error');
                    $(this).next('.error-box').html('Поле обязательно для заполения')
                        .css('color','red')
                        .animate({'paddingLeft':'10px'},400)
                        .animate({'paddingLeft':'5px'},400);
                }
                break;
        } // end switch(...)

    }); // end blur()

    // Теперь отправим наше письмо с помощью AJAX
    $('form#addIdStatistic').submit(function(e){
        // Запрещаем стандартное поведение для кнопки submit
        e.preventDefault();
        // После того, как мы нажали кнопку "Отправить", делаем проверку,
        // если кол-во полей с классом .not_error равно 3 (так как у нас всего 3 поля), то есть все поля заполнены верно,
        // выполняем наш Ajax сценарий и отправляем письмо адресату
        if($('.not_error').length == 2)
        {
            // Eще одним моментом является то, что в качестве указания данных для передачи обработчику send.php,
            // мы обращаемся $(this) к нашей форме,
            // и вызываем метод .serialize().
            // Это очень удобно, т.к. он сразу возвращает сгенерированную строку с именами и значениями выбранных элементов формы.
            $.ajax({
                //url: 'http://localhost/kn/beget/public/accounts/addaccont',
                url: './statistics/addStatistics',
                type: 'post',
                data: $(this).serialize(),
                beforeSend: function(xhr, textStatus){
                    $('form#addIdStatistic :input').attr('disabled','disabled');
                },
                error: function(response){
                    //alert(response.responseText);
                    alert("Проверте вводимые данные");
                    //window.location = "http://localhost/kn/beget/public/accounts";
                },
                success: function(response){
                    //console.log(response);
                    if(response.status == 'success'){
                        //window.history.back();
                        //console.log(response.responseText);
                        window.location = "/public/statistics";
                    }
                    else{
                        //alert("Проверте данные");
                        //console.log(response.responseText);
                        window.location = "./statistics";
                    }
                },
                complete:function(xhr, textStatus){
                    $('form#addIdStatistic :input').prop('disabled', false);
                }
            }); // end ajax({...})
        }
        // Иначе, если количество полей с данным классом не равно значению 3, мы возвращаем false,
        // останавливая отправку сообщения в невалидной форме
        else
        {
            return false;
        }
    }); // end submit()
}); // end script