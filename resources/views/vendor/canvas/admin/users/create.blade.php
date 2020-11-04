@extends('canvas::admin.layouts.app')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <h4 class="card-title">Add New User</h4>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <form action="/admin/users" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Password</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm Password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Role</label>
                            <select class="form-control" id="role" name="role">
                                @foreach($roles as $role)
                                <option value="{{$role->name}}" {{$role->name=='member' ? 'selected':''}}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="file">Picture</label>
                            <input type="file" class="form-control-file" id="file" name="file">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="send_email" name="send_email" value="check">
                                <label class="custom-control-label" for="send_email">Send E-mail to user</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input" id="include_password" name="include_password" value="check">
                                <label class="custom-control-label" for="include_password">Include password</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mr-2">Submit</button>
                        <a href="{{ URL::previous() }}" class="btn btn-dark">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop