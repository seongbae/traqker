@extends('canvas::admin.layouts.app')

@section('title', __('Edit Section'))
@section('content')
    @include('teams.menus')
    <div class="container">
        <form method="post" action="{{ route('wikipages.update', ['type'=>'teams','slug'=>$team->slug,'wikiPage'=>$wikiPage]) }}">
            @csrf
            @method('patch')
            <div class="card">
                <div class="card-header">
                    Edit Page
                </div>
                <div class="list-group-item py-3">
                    <div class="row">
                        <label for="name" class="col-form-label col-md-2">Name</label>
                        <div class="col-md">
                            <input type="text" name="title" id="title" class="form-control" value="{{$wikiPage->title}}" required>
                        </div>
                    </div>
                </div>
                <div class="list-group-item py-3">
                    <div class="row">
                        <label for="name" class="col-form-label col-md-2">Content</label>
                        <div class="col-md">
                            <textarea name="content" id="content" rows="8"  required="required" class="form-control" placeholder="Message...">{{$wikiPage->content}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-md-right border-top-0">
                    @if (!$wikiPage->initial_page)
                    <a href="#" class="btn {{ !request()->ajax() ? 'btn-primary' : 'btn-link text-secondary p-1' }}" title="{{ __('Delete') }}"
                       onclick="event.preventDefault(); if (confirm('{{ __('Delete This Page?') }}')) $('#delete_wikipage_form').submit();">
                        <i class="far fa-trash-alt {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
                    </a>
                    @endif
                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Update & Continue Edit') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
        <form method="post" action="{{ route('wikipages.destroy', ['type'=>$type,'slug'=>$slug,'wikiPage'=>$wikiPage]) }}" id="delete_wikipage_form" class="d-none">
            @csrf
            @method('delete')
        </form>
    </div>
@endsection


@push('scripts')

<script>
    $(document).ready(function(){
        $("#ManyTable").on("click", "#DeleteButton", function() {
           $(this).closest("tr").remove();
        });
    });
</script>

@endpush
