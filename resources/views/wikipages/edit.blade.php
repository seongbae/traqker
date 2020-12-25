@extends('canvas::admin.layouts.app')

@section('title', __('Edit Section'))
@section('content')
    @include('teams.menus')
        <form method="post" action="{{ route('wikipages.update', ['type'=>$type,'slug'=>$slug,'wikiPage'=>$wikiPage->id]) }}">
            @csrf
            @method('patch')
            <div class="card pt-2">
                <div class="container">
                    <div class="card-body">
                        <div class="row my-3">
                            <div class="col-lg-12">
                                <div class="float-left">
                                    <h1>Editing {{ $wikiPage->title }}</h1>
                                </div>
                                <div class="float-right">
                                    <a href="{{ route('wikipages.create',['type'=>$type,'slug'=>$team->slug]) }}" class="btn btn-primary btn-sm">New</a>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-lg-12">
                                <input type="text" name="title" id="title" class="form-control" value="{{$wikiPage->title}}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <textarea name="content" id="content" rows="8"  required="required" class="form-control" placeholder="Message...">{{$wikiPage->content}}</textarea>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-md">
                                <input type="text" name="change_description" id="change_description" class="form-control" value="">
                            </div>
                        </div>
                        <div class="row  my-3 text-right">
                            <div class="col-md">
                                <a href="#" class="btn {{ !request()->ajax() ? 'btn-primary' : 'btn-link text-secondary p-1' }}" title="{{ __('Delete') }}"
                                   onclick="event.preventDefault(); if (confirm('{{ __('Delete This Page?') }}')) $('#delete_wikipage_form').submit();">
                                    <i class="far fa-trash-alt {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
                                </a>
                                <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Update & Continue Edit') }}</button>
                                <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </form>
    <form method="post" action="{{ route('wikipages.destroy', ['type'=>$type,'slug'=>$slug,'wikiPage'=>$wikiPage]) }}" id="delete_wikipage_form" class="d-none">
        @csrf
        @method('delete')
    </form>

@endsection


@push('scripts')

@endpush
