<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>
    <h2>Welcome to use Blade</h2>
    {{-- 注释，并且不会在最终html里显示 --}}
    <h2>Blade 中的 @{{ }} 表达式的返回值将被自动传递给 PHP 的 htmlentities 函数进行处理，以防止 XSS 攻击。</h2>
    <h2>__DIR__ and __FILE__在blade视图里使用的话，会指向cache目录</h2>
    <hr/>

    <h2>展示数据--普通变量</h2>
    <h2>@{{$a}} = {{$a}}</h2>
    <h2>@{{time()}} = {{time()}}</h2>
    <h2>@{{ isset($name) ? $name : 'Default' }} = {{ isset($name) ? $name : 'Default' }}</h2>
    <h2>@{{ isset($a) ? $name : 'Default' }} = {{ isset($a) ? $a : 'Default' }}</h2>
    <h2>@{{ $name or 'Default' }} = {{ $name or 'Default' }}</h2>
    <h2>@{{ $a or 'Default' }} = {{ $a or 'Default' }}</h2>
    <h2>htmlentities转义：@{{ $ahhh }} = {{ $ahhh}}</h2>
    <h2>禁止htmlentities转义：@{!! $ahhh !!} = {!! $ahhh !!}</h2>

    <hr/>
    <h2>判断</h2>
   {{-- <pre>
        @@if($x == 1)
            <h2>1 -- @{{ $x }} = {{$x}}</h2>
        @@elseif($x == 2)
            <h2>2 -- @{{ $x }} = {{$x}}</h2>
        @@else
            <h2>3 -- @{{ $x }} = {{$x}}</h2>
        @@endif
    </pre>--}}
    @if($x == 1)
        <h2>1 -- @{{ $x }} = {{$x}}</h2>
    @elseif($x == 2)
        <h2>2 -- @{{ $x }} = {{$x}}</h2>
    @else
        <h2>3 -- @{{ $x }} = {{$x}}</h2>
    @endif

    @unless($x == 2)
        <h2>1 -- @{{ $x }} = {{$x}}</h2>
    @endunless

    <hr/>
    <h2>循环@@for</h2>
    @for ($i = 0; $i < 3; $i++)
        <h2>The current value is {{ $i }}</h2>
    @endfor

    <hr/>
    <h2>循环@@foreach</h2>
    @foreach ($users as $k => $user)
        <p>This is user {{$k }}  => {{ $user }}</p>
    @endforeach

    <hr/>
    <h2>循环@@forelse</h2>
    @forelse ($users2 as $user)
        <li>{{ $user->name }}</li>
    @empty
        <p>No users</p>
    @endforelse

    <hr/>
    <h2>循环@@while</h2>
    @while ($x > 0)
        {{ $x-- }} -- loop<br/>
    @endwhile
</body>
</html>