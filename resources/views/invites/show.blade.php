@extends('canvas::admin.layouts.app')

@section('title', __('Invite'))
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Invite
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item py-3">
                        <div class="row">
                            <div class="col-md-2 text-secondary">
                                Name
                            </div>
                            <div class="col-md">

                            </div>
                        </div>
                    </div>
                   <div class="list-group-item py-3">
                        <div class="row">
                            <div class="col-md-2 text-secondary">
                                Created
                            </div>
                            <div class="col-md">
                                {{ $invite->created_at->format('Y-m-d') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer  text-md-right border-top-0">
            <form method="post" action="{{ route('invites.update', $invite) }}">
                @csrf
                @method('put')

                <button type="submit" name="submit" value="decline" class="btn btn-danger">{{ __('Decline') }}</button>
                <button type="submit" name="submit" value="accept" class="btn btn-primary">{{ __('Accept') }}</button>
            </form>
            </div>
        </div>
    </div>
@endsection
