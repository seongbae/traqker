@extends('canvas::admin.layouts.app')

@section('title', __('Projects'))
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="float-left">Projects</div>
                <div class="float-right">
                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> {{ __('Add Project') }}</a>
                </div>
            </div>
            <div class="card-body">
                {!! $html->table() !!}
                {!! $html->scripts() !!}
            </div>
            <div class="card-footer">
                <a href="/projects/archived" class="btn btn-link text-secondary"><i class="fas fa-archive" title="Archived Projects"></i></a>
            </div>
        </div>
    </div>
@endsection
