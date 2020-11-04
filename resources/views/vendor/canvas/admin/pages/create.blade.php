@extends('canvas::admin.layouts.app')

@section('content')

    {!! Form::open([
            'route' => 'admin.pages.store',
            'class'=>'form-horizontal'
            ]) !!}
    @include('canvas::admin.pages._partials.form', ['pageTitle'=>'Create Page', 'submitButtonText' => __('Save'), 'formsize'=>6])

    {!! Form::close() !!}

@stop