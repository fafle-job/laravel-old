<div class="test"></div>
@extends('layouts.app2')
	@section('content')
            @include('layouts.menu')
	    <a class="add-new-blck" href="./addblacklist">добавить анкету</a>
	    <div class="container">
			<div class="text-content">
				<br/>
		<p>Здесь можно добавить мужчину, с которым Вы или Ваши знакомые имели печальный опыт взаимодействия. Добавьте его в этот список, и все Ваши девушки будут получать уведомление, если случайно наткнутся на его анкету</p>
			</div>
	        <div class="panel-body blacklist">
	            <table>
	                <thead>
		                <tr>
		                    <th>№</th>
		                    <th>ID</th>
		                    <th>Фотография</th>
		                    <th>Причина занесения</th>
		                </tr>
	                </thead>
	                <tbody>
	                @if(isset($allRecords) and !$allRecords->isEmpty())
	                    @foreach($allRecords as $k=>$record)
	                        <tr>
	                            <td>{{$k+1}}</td>
	                            <td> <a style="display: block" href="./blacklist/edit/{{$record->id}}">{{$record->idblack}}</a> </td>
	                            <td><img src="{{$record->ava}}"></td>
	                            <td>

	                                <?php
        	                            $string = $record->desc;
        	                            if(strlen($string)>1000){
        	                                $string = substr($string, 0, 1000);
        	                            $string = rtrim($string, "!,.-");
        	                            $string = substr($string, 0, strrpos($string, ' '));
        	                            echo $string."… ";
        	                            }else{
        	                                echo $string;
        	                            }

	                                ?>

	                                <p><a style="display: block" href="./blacklist/delete/{{$record->id}}">Удалить</a></p>
	                            </td>


	                        </tr>
	                    @endforeach
	                @endif
	                </tbody>
	                <tfoot>

	                </tfoot>
	            </table>
	        </div>
	    </div>
	@endsection
</div>