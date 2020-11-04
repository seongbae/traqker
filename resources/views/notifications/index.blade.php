@extends('canvas::admin.layouts.app')

@section('title', __('Notifications'))
@section('content')
<div class="card">
    <div class="card-body">
        {!! $html->table(['class'=>'table table-hover','style'=>'cursor: pointer']) !!}
        {!! $html->scripts() !!}
    </div>
</div>
@endsection


@push('scripts')

<script>
    $(document).ready(function() {
        var table = $('#NotificationDatatable').DataTable();
         
        $('#NotificationDatatable tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();
            console.log(data['data']['link']);
            window.location.href = data['data']['link'];
        } );
    } );
</script>



@endpush