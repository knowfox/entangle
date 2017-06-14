@extends('concept.show')

@section('main-content')
    <table class="table">
        <tbody>
            @if ($concept->event->date_to)
                <tr>
                    <th>From</th><td>{{$concept->event->date_from}}</td>
                </tr>
                <tr>
                    <th>Until</th><td>{{$concept->event->date_to}}</td>
                </tr>
            @else
                <tr>
                    <th>On</th><td>{{$concept->event->date_from}}</td>
                </tr>
            @endif
        </tbody>
    </table>

    @parent
@endsection

@section('edit-concept')
    <hr>
    <h4>Event</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Date from</label>
                <input type="date" class="form-control" name="event[date_from]" value="{{$concept->event->date_from}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Date to</label>
                <input type="date" class="form-control" name="event[date_to]" value="{{$concept->event->date_to}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Duration</label>
                <input type="number" class="form-control" name="event[duration]" value="{{$concept->event->duration}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Unit</label>
                @include('partials.select', [
                    'name' => 'event[duration_unit]',
                    'selected' => $concept->event->duration_unit,
                    'options' => config('knowfox.duration_units')
                ])
            </div>
        </div>
    </div>
    <hr>
@endsection