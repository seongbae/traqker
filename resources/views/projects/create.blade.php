@extends('canvas::admin.layouts.app')

@section('title', __('Create Project'))
@section('content')
    <div class="container">
        <form method="post" action="{{ route('projects.store') }}">
            @csrf

            <div class="card">
                <div class="card-header">
                    Create Project
                </div>
                @include('projects.fields', ['action'=>'create'])

                <div class="card-footer text-md-right border-top-0">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary btn-sm">{{ __('Create & Add Another') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary btn-sm">{{ __('Create') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function(){
            $("#team_id").change(function () {
                var end = this.value;
                var teamId = $('#team_id').val();

                $.ajax({
                    method: 'GET',
                    url: '/get_projects/'+teamId,
                    data: {},
                    success: function( response ){
                        //console.log( response );

                        var $el = $("#parent_id");
                        $el.empty(); // remove old options
                        $el.append($("<option></option>"));
                        $.each(response, function(key,value) {
                            $el.append($("<option></option>")
                                .attr("value", value).text(key));
                        });
                    },
                    error: function( e ) {
                        console.log(e);
                    }
                });
            });
        });
    </script>

@endpush
