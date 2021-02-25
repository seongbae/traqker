@extends('canvas::admin.layouts.app')

@section('title', __('Team'))
@section('content')

@include('teams.menus')

<div class="card">
    <div class="card-body">
        <div id="calendar" data-model="team" data-model-id="{{$team->id}}"></div>
    </div>
</div>

<div id="calendarModal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="newTaskForm" name="newTaskForm" role="form">
            <div class="modal-header">
                <h5 id="modalTitle" class="modal-title">New Task</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div id="modalBody" class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="name" placeholder="Title">
                    </div>
                    <div class="form-group">
                        <input type="date" name="start_on" id="start_on" class="form-control">
                        <input type="date" name="due_on" id="due_on" class="form-control">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" id="description" placeholder="Description"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submitButton">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
    $(document).ready(function(){

        $('#calendarModal').on('shown.bs.modal', function (e) {
            $('#calendarModal #name').focus();
        });

        $('#submitButton').click( function() {
            submitForm();
        });

        function submitForm(){
            var name = $('#calendarModal #name').val();
            var description = $('#calendarModal #description').val();
            var start = $('#calendarModal #start_on').val();
            var due = $('#calendarModal #due_on').val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    name: name,
                    description: description,
                    start_on: start,
                    due_on: due
                },
                type: 'POST',
                url: '/tasks',
                success: function (data) {
                    // console.log(calendar);
                    // calendar.addEvent({
                    //     title: name,
                    //     start: start,
                    //     end: due,
                    //     allDay: true
                    // });
                }
            });
        }
    });
    </script>

@endpush
