$(document).ready(function(){
    // Устанавливаем обработчик потери фокуса для всех полей ввода текста
    $('input#id, input#login, input#pwd').unbind().blur( function(){
        // Для удобства записываем обращения к атрибуту и значению каждого поля в переменные
        var id = $(this).attr('id');
        var val = $(this).val();
        // После того, как поле потеряло фокус, перебираем значения id, совпадающие с id данного поля
        switch(id)
        {
            // Проверка поля "Имя"
            case 'id':
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
                    $(this).next('.error-box').html('Поле оно обязательно для заполения и должо содержать 10 символов')
                        .css('color','red')
                        .animate({'paddingLeft':'10px'},400)
                        .animate({'paddingLeft':'5px'},400);
                }
                break;


            // Проверка поля "Имя"
            case 'login':
                //var rv_name = /^[0-9]{10}$/; // используем регулярное выражение
                // Eсли длина 10 символов, оно не пустое и удовлетворяет рег. выражению,
                // то добавляем этому полю класс .not_error,
                // и ниже в контейнер для ошибок выводим слово " Принято", т.е. валидация для этого поля пройдена успешно
                // if( val != '' && rv_name.test(val))
                if( val != '' )
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

            // Проверка поля "Имя"
            case 'pwd':
                //var rv_name = /^[0-9]{10}$/; // используем регулярное выражение
                // Eсли длина 10 символов, оно не пустое и удовлетворяет рег. выражению,
                // то добавляем этому полю класс .not_error,
                // и ниже в контейнер для ошибок выводим слово " Принято", т.е. валидация для этого поля пройдена успешно
                if( val != '')
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
    $('form#accountAdd').submit(function(e){
        // Запрещаем стандартное поведение для кнопки submit
        e.preventDefault();
        // После того, как мы нажали кнопку "Отправить", делаем проверку,
        // если кол-во полей с классом .not_error равно 3 (так как у нас всего 3 поля), то есть все поля заполнены верно,
        // выполняем наш Ajax сценарий и отправляем письмо адресату
        if($('.not_error').length == 3)
        {
            // Eще одним моментом является то, что в качестве указания данных для передачи обработчику send.php,
            // мы обращаемся $(this) к нашей форме,
            // и вызываем метод .serialize().
            // Это очень удобно, т.к. он сразу возвращает сгенерированную строку с именами и значениями выбранных элементов формы.
            $.ajax({
                url: '/accounts/addaccont',
                type: 'post',
                data: $(this).serialize(),
                beforeSend: function(xhr, textStatus){
                    $('form#accountAdd :input').attr('disabled','disabled');
                },
                error: function(response){
                    pos = response.responseText.indexOf('Duplicate entry');
                    if(pos == -1){
                        alert("Проверте вводимые данные");
                    }else{
                        alert('Анкета уже зарегистрирована');
                    }
                },
                success: function(response){
                    if(response.status == 'success'){
                        window.location = "/accounts";
                    }else{
                        if(response.status == 'error'){
                            alert(response.text);
                        }
                    }
                },
                complete:function(xhr, textStatus){
                    $('form#accountAdd :input').prop('disabled', false);;
                },
                dataType: "json"
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