@extends('layout')

@section('meta')
    <link id="favicon" rel="shortcut icon" type="image/png" href="{{ route('index') }}/storage/images/{{{ $municipality->id }}}.gif" />
    <title>{{{ $municipality->name }}}</title>
    <meta name="description" content="Vyhľadávanie v databáze obcí"/>
    <meta property="og:site_name" content="Databaza obci">
    <meta property="og:url" content="{{ route('index') }}/{{{ $municipality->url_id }}}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{{ $municipality->name }}}">
    <meta property="og:description" content="Vyhľadávanie v databáze obcí">
    <meta property="og:image" content="{{ route('index') }}/storage/images/{{{ $municipality->id }}}.gif">
@stop

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/show.css') }}">
@stop

@section('content')
    <div class="detail_head">
        <p style="font-size: 50px; font-weight: lighter">Detial obce</p>
    </div>
    <div class="container municipality_data">
        <div class="row" >
            <div class="col-lg-6 municipality_column">
                <table>
                    <thead>
                        <th></th>
                        <th></th>
                    </thead>
                    <tbody>
                        @if (isset($municipality->mayor))
                            <tr>
                                <td class="text_bold"><p>Meno starostu:</p></td>
                                <td><p>{{{ $municipality->mayor }}}</p></td>
                            </tr>
                        @endif
                        @if (isset($municipality->street))
                            <tr>
                                <td class="text_bold"><p>Adresa obecného úradu:</p></td>
                                <td><p>{{{ $municipality->street }}}, {{{ $municipality->zip }}} {{{ $municipality->city_name }}}</p></td>
                            </tr>
                        @endif
                        @if (isset($municipality->phone))
                            <tr>
                                <td class="text_bold">Telefón:<p></p></td>
                                <td><p>{{{$municipality->phone}}}</p></td>
                            </tr>
                        @endif
                        @if (isset($municipality->fax))
                            <tr>
                                <td class="text_bold"><p>Fax:</p></td>
                                <td><p>{{{ $municipality->fax }}}</p></td>
                            </tr>
                        @endif
                        @if (isset($emails))
                            <tr>
                                <td class="text_bold"><p>Email:</p></td>
                                <td>
                                    <p>
                                        @if (!empty($emails))
                                            @foreach ($emails as $email)
                                                <a href="mailto:{{$email}}">{{$email}}</a>,
                                            @endforeach
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endif
                        @if (isset($municipality->web))
                            <tr>
                                <td class="text_bold">
                                   <p>Web:</p>
                                </td>
                                <td>
                                    <p>
                                        <a href="http://{{{$municipality->web }}}" target="_blank">{{{ $municipality->web }}}</a>
                                    </p>
                                </td>
                            </tr>
                        @endif
                        @if (isset($municipality->latitude))
                            <tr>
                                <td class="text_bold"><p>Zemepisné súradnice:</p></td>
                                <td><p> {{ $municipality->latitude }} {{ $municipality->longitude }}</p></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-lg-6" style="background-color: white">
                <img src="{{ route('index') }}/storage/images/{{{ $municipality->id }}}.gif" class="coan_of_arms">
                <h2 class="municipality_show_name">{{{ $municipality->name }}}</h2>
            </div>
        </div>
    </div>
@stop


