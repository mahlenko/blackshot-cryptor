@extends('web.layouts.default')
@section('content')
    <h1>404!</h1>
    <h3>Упс! Что-то пошло не так.</h3>
    <p>
        Страница которую вы пытаетесь открыть больше нет или еще не создана.<br>
        Попробуйте начать с <a href="{{ route('home') }}">главной страницы</a> сайта.
    </p>
@endsection