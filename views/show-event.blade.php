@extends('concept.show')

@section('main-content')
    @parent

    {{$concept->event()->first()}}
@endsection