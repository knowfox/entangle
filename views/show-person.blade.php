@extends('concept.show')

@section('main-content')

    <section class="fields">
        <table class="table">
            <tbody>
            <tr>
                <th>Slug</th>
                <td>{{$concept->slug or '-'}}</td>
            </tr>
            <tr>
                <th>Firstname</th>
                <td>{{$concept->config->firstname or '-'}}</td>
            </tr>
            <tr>
                <th>Lastname</th>
                <td>{{$concept->config->lastname or '-'}}</td>
            </tr>
            <tr>
                <th>E-Mail</th>
                <td>{{$concept->config->email or '-'}}</td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td>{{$concept->config->mobile or '-'}}</td>
            </tr>
            </tbody>
        </table>

    </section>
    @parent

@endsection

@section('siblings')
@endsection

@section('config')
@endsection

