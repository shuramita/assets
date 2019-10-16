@extends('emails.layouts.layout')
@section('header')
    {{--<h1>--}}
        {{--<a href="{{$resource->links->home or ""}}">{{__('welcome')}}!</a>--}}
    {{--</h1>--}}
@endsection
@section('body')
    <div class="body">
        <p>Hello,</p>
        <p>Your Booking has been rejected</p>
        <p>{{$comment ?? ''}}</p>
    </div>
@endsection
