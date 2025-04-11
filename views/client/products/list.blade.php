@extends('client.layouts.main')

@section('content')
    <div>
        <pre>{{print_r($products)}}</pre>
        @foreach ($products as $item)
            <span>{{$item['name']}}</span>
        @endforeach
    </div>
@endsection