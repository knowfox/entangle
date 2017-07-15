@extends('concept.show')

@section('main-content')
    @parent

    @if ($concept->children()->count())
        <section class="timeline">
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
                        <td class="text-nowrap">{{$event->date_from}} ({{$event->weekday_from}})</td>
                        <td class="text-nowrap">{{$event->event->date_to}} @if ($event->event->date_to)({{$event->weekday_to}})@endif</td>
                        <td>{{$event->title}}</td>
                    </tr>
                    <tr><td colspan="4">{!! $event->rendered_body !!}</td></tr>
                @endforeach
                </tbody>
            </table>
        </section>
    @endif

@endsection

@section('children')
@endsection
