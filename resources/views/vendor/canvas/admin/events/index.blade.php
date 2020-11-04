@extends('admin.layouts.app')

@section('content')

            <div class="card">
                <div class="card-body">
                    {{$dataTable->table()}}
                </div>
            </div>
        
</div>
@endsection

@push('scripts')
    {{$dataTable->scripts()}}
@endpush