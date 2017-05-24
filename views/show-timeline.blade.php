@extends('concept.show')

@section('main-content')
    @parent

    @if ($concept->children()->count())
        <?php
        $events = $concept->children()
            ->leftJoin('entangle_events', 'entangle_events.concept_id', '=', 'concepts.id')
            ->select('concepts.*', 'entangle_events.*')
            ->orderBy('entangle_events.date_from', 'desc')
            ->get();
        ?>
        <table class="table">
            <tbody>
            @foreach ($events as $event)
                <tr>
                    <td>{{$event->id}}</td>
                    <td>{{$event->date_from}}</td>
                    <td>{{$event->title}}</td>
                </tr>
                <tr><td colspan="3">{{$event->body}}</td></tr>
            @endforeach
            </tbody>
        </table>
    @endif

@endsection