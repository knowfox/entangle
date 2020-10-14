@extends('knowfox::concept.show')

@section('main-content')

    <section class="fields">
        <table class="table">
            <tbody>

            @php
            $config = $concept->config;
            $config->slug = $concept->slug;
            @endphp

            @foreach ($config as $key => $value)
                <tr>
                    <th>{{ ucfirst($key) }}</th>
                    <td>{{ is_object($value) ? Symfony\Component\Yaml\Yaml::dump($value) : $value }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </section>
    @parent

@endsection

@section('siblings')
@endsection

@section('config')
@endsection
