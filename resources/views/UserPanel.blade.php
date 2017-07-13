@extends('panelViews::mainTemplate')
@section('page-wrapper')
    <script type="text/javascript" src="{{ asset('script/jquery.tablesorter.combined.js')}}"></script><!--сортировка таблицы-->
    <script type="text/javascript" src="{{ asset('script/myTablesorter.js')}}"></script>
    <div class="container" >
        {{--<input type="hidden" value="{{Auth::user()->id}}" id="userID" >--}}
        <div class="panel-body" >
            <div>

                <?php

                ?>

                @if(Session::has('message'))
                    {{ $value = Session::get('message')}}
                @endif
                <form>
                    @if(isset($allUsers))
                        <table id="table2" class="tablesorter" >
                            <thead>
                            <tr>
                                <th><a id="idLink" href="#" onclick="return false">№</a></th>
                                <th><a id="idLink" href="#" onclick="return false">E-mail</a></th>
                                <th><a id="idLink" href="#" onclick="return false">Редактирование</a></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($allUsers as $k=>$records)
                                <tr>
                                    <td><a href="#" class="toggle">{{$k+1}}</a></td>
                                    <td><a href="#" class="toggle">{{$records->email}}</a></td>
                                    <td>
                                        <a onclick="return del(this);" href="{{ route('delete.adminpanel', ['id'=>$records->id]) }}">Удалить</a>
                                        <br/>
                                        <?php
                                        $countGirls = 0;
                                        foreach($allGirls as $girl){
                                            if($records->id == $girl->user_id){
                                                $countGirls++;
                                            }
                                        }
                                        if($countGirls>0){?>
                                        <a href="{{ route('blockAllID.adminpanel', ['id'=>$records->id]) }}">Разблокировать все анкеты</a>
                                        <br/>
                                        <a href="{{ route('UnblockAllID.adminpanel', ['id'=>$records->id]) }}">Заблокировать все анкеты</a>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td></td>
                                </tr>

                                <?php
                                foreach($allGirls as $girl){
                                if($records->id == $girl->user_id){?>
                                <tr class="tablesorter-childRow">
                                    <td>{{ $girl->login }}</td>
                                    <td>{{ $girl->password }}</td>
                                    <td>{{ $girl->originId }}</td>
                                    <td>
                                        <a href="{{ route('editacount.adminpanel', ['id'=>$girl->originId]) }}">Редактировать</a>
                                        <br/>
                                        <a href="{{ route('deleteacount.adminpanel', ['id'=>$girl->originId]) }}">Удалить</a>
                                    </td>
                                </tr>
                                <?php
                                }
                                }
                                ?>
                            </tbody>
                            @endforeach
                        </table>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <script>
        function del(Element){
            var isDel = confirm("Вы уверены?");
            if(isDel){
                window.location.href = $(this).attr('href');
            }else {
                return false;
            }
        }
    </script>
    {{--<script type="text/javascript" src="{{ asset('script/updateStatisticsByDate.js')}}"></script>--}}
@stop