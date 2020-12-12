@extends('canvas::admin.layouts.app')

@section('title', __('Edit Project'))
@section('content')
    <div class="container">
        <form method="post" action="{{ route('projects.update', $project) }}">
            @csrf
            @method('patch')

            <div class="card">
                <div class="card-header">
                    Edit Project
                </div>
                @include('projects.fields', ['action'=>'edit'])

                <div class="card-footer text-md-right border-top-0">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Update & Continue Edit') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
        <form method="post" action="{{ route('projects.destroy', $project) }}" id="delete_project_{{ $project->id }}_form" class="d-none">
            @csrf
            @method('delete')
        </form>
    </div>
@endsection


@push('scripts')
    <script type="text/javascript">

        var data = @json($availableUsers);
        console.log(data);

        var users = new Bloodhound({
            datumTokenizer:  Bloodhound.tokenizers.obj.whitespace('text'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            //identify: function(obj) { return obj.id; },
            local: $.map(data, function (item) {
                return {
                    value: item.value,
                    text: item.text
                };
            })
        });
        users.initialize();

        $('#users').tagsinput({
            itemValue: 'value',
            itemText: 'text',
            typeaheadjs: [{
                minLength: 2,
                highlight: true
            },
                {
                    name: 'users',
                    displayKey: 'text',
                    valueKey: 'value',
                    source: users.ttAdapter()
                }
            ]
        });

        {{--var projectUsers = @json($projectUsers);--}}

        {{--for (var i = 0; i < projectUsers.length; i++){--}}
        {{--    $('#users').tagsinput('add', { "value": projectUsers[i].value , "text": projectUsers[i].text    });--}}
        {{--}--}}

        {{--function validateSelection() {--}}
        {{--    if(data.indexOf($(this).val()) === -1)--}}
        {{--        alert('Error : element not in list!');--}}
        //}
    </script>
@endpush
