@extends('laadmin::template')

@section('content')
    <register :data="{{ json_encode($data) }}"></register>
@endsection
