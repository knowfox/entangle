@extends('concept.show')

@section('main-content')
    @parent

    @if ($concept->children()->count())
        <section class="timeline">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Date from</th>
                    <th>Date to</th>
                    <th>Title</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($concept->children as $event)
                    <tr>
                        <td class="text-nowrap" title="{{$event->weekday_from}}">{{$event->date_from}}</td>
                        <td class="text-nowrap" title="{{$event->weekday_to}}">{{$event->event->date_to}} @if ($event->event->date_to)@endif</td>
                        <td><a href="{{route('concept.show', [$event])}}">{{$event->title}}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>
    @endif

@endsection

@section('children')
@endsection
