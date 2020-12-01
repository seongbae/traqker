@extends('canvas::admin.layouts.app')

@section('title', __('Edit Task'))
@section('content')
    <div class="container">
        <form method="post" action="{{ route('tasks.update', $task->id) }}" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="card">
                <div class="card-header">
                    Edit Task
                </div>
                @include('tasks.fields')

                <div class="card-footer text-md-right border-top-0">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Update & Continue Edit') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection


@push('scripts')
    <script type="text/javascript">

        var data = @json($users);

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

        $('#assignees').tagsinput({
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

        var assignees = @json($assignees);

        for (var i = 0; i < assignees.length; i++){
            $('#assignees').tagsinput('add', { "value": assignees[i].value , "text": assignees[i].text    });
        }

        function validateSelection() {
            if(data.indexOf($(this).val()) === -1)
                alert('Error : element not in list!');
        }
    </script>
@endpush
