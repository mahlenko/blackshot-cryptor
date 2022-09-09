@extends('web.layouts.default', ['first_screen' => 'first-screen-fluid'])
@section('content')
    {!! $page->translateOrDefault(app()->getLocale())->body !!}
@endsection
