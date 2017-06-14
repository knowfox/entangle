@extends('concept.show')

@section('main-content')
    @parent

    @if ($concept->children()->count())
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date from</th>
                    <th>Date to</th>
                    <th>Title</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($concept->children as $event)
                <tr>
                    <td><a href="{{route('concept.show', [$event])}}">{{$event->id}}</a></td>
                    <td class="text-nowrap">{{$event->date_from}}</td>
                    <td class="text-nowrap">{{$event->date_to}}</td>
                    <td>{{$event->title}}</td>
                </tr>
                <tr><td colspan="4">{!! $event->rendered_body !!}</td></tr>
            @endforeach
            </tbody>
        </table>
    @endif

@endsection

@section('children')
@endsection
