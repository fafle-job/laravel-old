@extends('layouts.app2')
@section('content')
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('style/jquery-ui.min.css')}}" />
    <script language="javascript" type="text/javascript" src="{{ asset('script/moment.js')}}"></script>
    <script language="javascript" type="text/javascript" src="{{ asset('script/moment-range.js')}}"></script>

    <script language="javascript" type="text/javascript" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script>
        $(function(){
            $('.showHidden').click(function() {
                $('.detail').toggle();
            });
        });
    </script>
    
    @include('layouts.menu')
    <div class="container" >
        <div class="myloader"></div>
        <input type="hidden" value="{{Auth::user()->id}}" id="userID">

        <div class="row">
            <div class="col-md-12">
                <table id="example" width="100%" border="3">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nickname</th>
                        <th>Переводчик</th>
                        <th>Сегодня</th>
                        <th>Вчера</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($allRecords) and !$allRecords->isEmpty())
                        @foreach($allRecords as $k=>$record)
                            <?php
                            if(count($idBlockgirls)>0){
                            foreach($idBlockgirls as $kk =>$vv){
                            if($idBlockgirls[$kk]==$record->idgirl){
                            ?>
                            <tr class="{{$record->isactive===0?"bg-info toggle":($record->isactive===1?"bg-danger toggle":($record->isactive===2?"bg-warning toggle":"toggle"))}}" >
                                <td>
                                    <a href="#" class="toggle" id="idgirl">{{$record->idgirl}}</a>
                                </td>
                                <td class="showHidden">
                                    {{$record->nickname}}
                                </td>
                                <td>
                                    {{$record->translator}}
                                </td>
                                <td id="today{{$record->idgirl}}">
                                    0
                                </td>
                                <td id="yesterday{{$record->idgirl}}">
                                    0
                                </td>
                            </tr>
                            <?php }}}?>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <div id="dialog" title="222">
                    <p></p>
                </div>
                <script>
                    $(document).ready(function() {
                        $('#example').DataTable( {
                            "paging":   false,
                            "info":     false,
                        } );
                    } );
                </script>
            </div>
        </div>
        <br/>
        <br/>
        <br/>
        <br/>
        <div class="text-content">
            <p>

            </p>
            <p>

            </p>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('script/unreadmsg.js')}}"></script><!--Валидация формы при добавлении аккаунта-->
@endsection