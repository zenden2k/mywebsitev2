<h1>В этот день умерли</h1>
@foreach($people as $human)
    {{$human['title']}} ( {{$human['born']}} - {{$human['dead']}})<br>
@endforeach
