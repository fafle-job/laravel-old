@extends('panelViews::mainTemplate')
@section('page-wrapper')
    @if(Auth::guard('panel')->user()->id==1)
        <form role="form" method="POST" action="{{ url('/panel/addAdmin/') }}" class="form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="exampleInputEmail">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail" placeholder="Введите E-mail" name="email">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword">Имя</label>
                <input type="text" class="form-control" id="exampleInputPassword" placeholder="Введите имя" name="name">
            </div>
            <button type="submit" class="btn btn-default">Добавить админа</button>
        </form>
        @if(Session::has('message'))
            <div class="balcklist-message">{{ $value = Session::get('message')}}</div>
        @endif
<br>
        @if(isset($allAdmins))
            <table class="table  table-bordered table-hover ">
                <tr>
                    <th>№</th>
                    <th>E-mail</th>
                    <th>Имя</th>
                    <th>Редактировать</th>
                </tr>
                <?php $i=1;?>
                @foreach($allAdmins as $allAdmins)
                    @if($allAdmins->id!=1)
                        <tr>
                            <td><?php echo $i;?></td>
                            <td>

                                @if($allAdmins->activated==0)
                                    {{$allAdmins->email}}
                                @endif
                                @if($allAdmins->activated==1)
                                        {{substr($allAdmins->email,0,-5)}}
                                @endif

                            </td>
                            <td>{{$allAdmins->first_name}}</td>
                            <td>
                                @if($allAdmins->activated==0)
                                    <a href="./blockAdminPanel/{{$allAdmins->id}}">Заблокировать</a>
                                @endif
                                @if($allAdmins->activated==1)
                                    <a href="./blockAdminPanel/{{$allAdmins->id}}">Разблокировать</a>
                                @endif
                                <a href="./deleteAdminPanel/{{$allAdmins->id}}" onclick='return confirm("Вы уверены,что хотите удалить админа?")'>Удалить</a>
                            </td>
                        </tr>
                        <?php $i++;?>
                    @endif
                @endforeach
            </table>
        @else
            <h1>Добавте разрешенных администраторов </h1>
        @endif
    @endif
@stop