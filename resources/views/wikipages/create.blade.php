@extends('canvas::admin.layouts.app')

@section('title', __('Edit Section'))
@section('content')
    @include('teams.menus')
    <div class="container">
        <form method="post" action="{{ route('wikipages.store', ['type'=>$type,'slug'=>$slug]) }}">
            @csrf
            <div class="card">
                <div class="card-header">
                    New Page
                </div>
                <div class="list-group-item py-3">
                    <div class="row">
                        <label for="name" class="col-form-label col-md-2">Name</label>
                        <div class="col-md">
                            <input type="text" name="title" id="title" class="form-control" value="" required>
                        </div>
                    </div>
                </div>
                <div class="list-group-item py-3">
                    <div class="row">
                        <label for="name" class="col-form-label col-md-2">Content</label>
                        <div class="col-md">
                            <textarea name="content" id="content" rows="8"  required="required" class="form-control" placeholder="Message..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-md-right border-top-0">
                    <input type="hidden" name="initial_page" value="{{$initial_page}}">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Update & Continue Edit') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </div>
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
