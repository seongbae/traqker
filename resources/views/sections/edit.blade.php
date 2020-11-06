@extends('canvas::admin.layouts.app')

@section('title', __('Edit Section'))
@section('content')
    <div class="container">
        <form method="post" action="{{ route('sections.update', $section->id) }}">
            @csrf
            @method('patch')

            <div class="card">
                <div class="card-header">
                    Edit Section
                </div>
                <div class="list-group-item py-3">
                    <div class="row">
                        <label for="name" class="col-form-label col-md-2">Name</label>
                        <div class="col-md">
                            <input type="text" name="name" id="name" class="form-control" value="{{$section->name}}">
                        </div>
                    </div>
                </div>

                <div class="card-footer text-md-right border-top-0">
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
