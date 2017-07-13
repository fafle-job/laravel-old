@extends('panelViews::mainTemplate')
@section('page-wrapper')

    <div class="container" >
        <div class="panel-body" >
            @if(Session::has('message'))
                <div class="balcklist-message">{{ $value = Session::get('message')}}</div>
            @endif
            <div class="row"> <a href="{{ route('addUser') }}" class='btn'>Добавить админа</a></div>

            @if(isset($allAllowedAdmins))
                <table class="table  table-bordered table-hover ">
                    <tr>
                        <th>№</th>
                        <th>E-mail</th>
                        <th>Имя</th>
                        <th>Редактировать</th>
                    </tr>
					<?php $i=1;?>
                    @foreach($allAllowedAdmins as $allAllowedAdmin)                        
                        <tr>
                            <td><?php echo $i;?></td>
                            <td>{{$allAllowedAdmin->email}}</td>
                            <td>{{$allAllowedAdmin->name}}</td>
                            <td>
                                <a href="./editUser/{{$allAllowedAdmin->id}}">Редактировть</a>
                                <a href="./deleteUser/{{$allAllowedAdmin->id}}" onclick='return confirm("Вы уверены,что хотите удалить админа?")'>Удалить</a>
                            </td>
                        </tr>
                        <?php $i++;?>
                    @endforeach
                </table>
            @else
                <h1>Добавте разрешенных администраторов </h1>
            @endif

        </div>
    </div>
@stop