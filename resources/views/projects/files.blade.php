@extends('canvas::admin.layouts.app')

@section('title', __('Project'))
@section('content')

@include('projects.menus')

<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                {!! $html->table() !!}
                {!! $html->scripts() !!}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card  no-gutters" >
            <div class="card-header">
                <div class="float-left">
                    Upload Files
                </div>
            </div>
            <div class="card-body">
                <div id="dropzone" style="width:200px;">
                    <form class="dropzone needsclick" id="demo-upload" action="/attachments" enctype="multipart/form-data">
                        @csrf
                        <div class="dz-message needsclick">
                            <span class="note needsclick">Drop files here</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

    @push('scripts')

        <script  src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.js"></script>

        <script type="text/javascript">

            Dropzone.autoDiscover = false;
            var uploadedDocumentMap = {}

            var myDropzone = new Dropzone("#dropzone",{
                maxFilesize: 10,  // 3 mb
                acceptedFiles: ".jpeg,.jpg,.png,.pdf",
                url: "/attachments",
                data: {
                    id: {{$project->id}},
                    type: 'project',
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function (file, response) {
                    file.previewElement.innerHTML = "";
                    $('#attachments').append('<div class="attachment"><a href="/download/'+response.id+'">'+ response.label+'</a> ' +
                        '<form action="/attachments/'+response.id+'" method="POST" style="display:inline;">' +
                        '<input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'">' +
                        '<input type="hidden" name="_method" value="DELETE">' +
                        '<button type="submit" class="btn btn-outline text-muted" onclick="return confirm(\'Are you sure?\');"><i class="far fa-trash-alt fa-xs"></i></button>' +
                        '</form></div>')
                    uploadedDocumentMap[file.name] = response.name;
                },
                removedfile: function (file) {
                    file.previewElement.remove()
                    var name = ''
                    if (typeof file.file_name !== 'undefined') {
                        name = file.file_name
                    } else {
                        name = uploadedDocumentMap[file.name]
                    }
                    $('form').find('input[name="document[]"][value="' + name + '"]').remove()
                },
                error: function () {
                    console.log('error');
                },
                init: function () {
{{--                    @if(isset($task) && $task->attachments)--}}
{{--                    var files =--}}
{{--                    {!! json_encode($task->attachments) !!}--}}
{{--                        for (var i in files) {--}}
{{--                        var file = files[i]--}}
{{--                        //this.options.addedfile.call(this, file)--}}
{{--                        //file.previewElement.classList.add('dz-complete')--}}
{{--                        //$('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')--}}
{{--                        $('#attachments').append('<div class="attachment"><a href="/download/'+file.id+'">'+ file.label+'</a> ' +--}}
{{--                            '<form action="/attachments/'+file.id+'" method="POST" style="display:inline;">' +--}}
{{--                            '<input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'">' +--}}
{{--                            '<input type="hidden" name="_method" value="DELETE">' +--}}
{{--                            '<button type="submit" class="btn btn-outline text-muted" onclick="return confirm(\'Are you sure?\');"><i class="far fa-trash-alt fa-xs"></i></button>' +--}}
{{--                            '</form></div>')--}}
{{--                    }--}}
{{--                    @endif--}}
                }
            });

            myDropzone.on("sending", function(file, xhr, formData) {
                formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
                formData.append("id", {{$project->id}});
                formData.append("type", 'project');
            });




        </script>
@endpush
