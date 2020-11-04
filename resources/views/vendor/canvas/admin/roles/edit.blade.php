@extends('canvas::admin.layouts.app')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <h4 class="card-title">Edit Role</h4>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <form action="/admin/users/roles/{{$role->id}}" method="POST" style="display:inline;">
                        {{ csrf_field() }}
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Title" value="{{$role->name}}">
                        </div>
                        <div class="form-group">
                            <label for="name">Permissions</label>
                            <br/>
                            @foreach (\Spatie\Permission\Models\Permission::all() as $permission)
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="{{$permission->name}}" name="rolepermission[]" value="{{$permission->id}}" 
                                @if ($role->hasPermissionTo($permission->name))
                                checked
                                @endif

                                @if ($role->name == 'Administrator'))
                                onclick="swal('Admin permissions cannot be updated.');return false;"
                                @endif
                                >
                                <label class="custom-control-label" for="{{$permission->name}}">{{$permission->name}}</label>
                            </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-success mr-2">Submit</button>
                        <a href="{{ URL::previous() }}" class="btn btn-dark mr-2">Cancel</a>
                    </form>
                    @if ($role->name != 'Administrator')
                    <form action="/admin/roles/{{$role->id}}" method="POST" style="display:inline;">
                        {{ csrf_field() }}
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
@endpush

@push('styles')
@endpush