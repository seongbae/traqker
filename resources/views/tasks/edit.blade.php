@extends('canvas::admin.layouts.app')

@section('title', __('Edit Task'))
@section('content')
        <div class="row my-2">
            <div class="col-md">
                <h1>@yield('title')</h1>
            </div>
            <div class="col-md-auto mb-3 mb-md-0">
                @include('tasks.actions')
            </div>
        </div>

        <form method="post" action="{{ route('tasks.update', $task->id) }}" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="card">
                @include('tasks.fields')

                <div class="card-footer text-md-right border-top-0">
                    <button type="submit" name="submit" value="reload" class="btn btn-primary">{{ __('Update & Continue Edit') }}</button>
                    <button type="submit" name="submit" value="redirect" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
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
