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

        var taskData = @json($tasks);

        var tasks = new Bloodhound({
            datumTokenizer:  Bloodhound.tokenizers.obj.whitespace('text'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            //identify: function(obj) { return obj.id; },
            local: $.map(taskData, function (item) {
                return {
                    value: item.value,
                    text: item.text
                };
            })
        });
        tasks.initialize();

        $('#dependencies').tagsinput({
            itemValue: 'value',
            itemText: 'text',
            allowDuplicates: false,
            typeaheadjs: [{
                minLength: 2,
                highlight: true
            },
                {
                    name: 'dependencies',
                    displayKey: 'text',
                    valueKey: 'value',
                    source: tasks.ttAdapter()
                }
            ]
        });

        var dependencies = @json($dependencies);

        if (dependencies.length > 0) {
            for (var i = 0; i < dependencies.length; i++){
                $('#dependencies').tagsinput('add', { "value": dependencies[i].value , "text": dependencies[i].text    });
            }
        }
    </script>
@endpush
