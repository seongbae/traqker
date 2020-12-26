@extends('canvas::admin.layouts.app')

@section('title', __('Project'))
@section('content')

@include('projects.menus')

<div class="card">
    <div class="card-body">
        <div id="calendar" data-model="project" data-model-id="{{$project->id}}"></div>
    </div>
</div>

<div id="myModal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                <h4 id="modalTitle" class="modal-title">New Task</h4>
            </div>
            <div id="modalBody" class="modal-body">
                <form id="newTaskForm" name="newTaskForm" role="form">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Title:</label>
                        <input type="text" class="form-control" id="recipient-name">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Description:</label>
                        <textarea class="form-control" id="message-text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" data-dismiss="modal">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
    $(document).ready(function(){
        $("#newTaskForm").submit(function(event){
            submitForm();
            return false;
        });

        function submitForm(){
            $.ajax({
                type: "POST",
                url: "saveContact.php",
                cache:false,
                data: $('form#contactForm').serialize(),
                success: function(response){
                    $("#contact").html(response)
                    $("#contact-modal").modal('hide');
                },
                error: function(){
                    alert("Error");
                }
            });
        }
    });
    </script>

@endpush
