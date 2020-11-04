@extends('canvas::admin.layouts.app')

@section('content')

            <div class="card">
                <div class="card-body">
                	<a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Create</a>
                    {{$dataTable->table()}}
                </div>
            </div>
        
</div>
@endsection

@push('scripts')
    {{$dataTable->scripts()}}
@endpush