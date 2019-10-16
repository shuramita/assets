@extends('emails.layouts.layout')
@section('header')
    {{--<h1>--}}
        {{--<a href="{{$resource->links->home or ""}}">{{__('welcome')}}!</a>--}}
    {{--</h1>--}}
@endsection
@section('body')
    <div class="body">
        <p>Hello Landlord,</p>
        <p>New booking Request for your venue.</p>
    </div>
@endsection
