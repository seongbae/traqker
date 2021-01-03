@extends('canvas::admin.layouts.app')

@section('title', __('Invite'))
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                You have been invited to join a team.
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item py-3">
                        <div class="row">
                            <div class="col-md-2 text-secondary">
                                Team
                            </div>
                            <div class="col-md">
                                <strong>{{ $invite->team->name }}</strong>
                            </div>
                        </div>
                    </div>
                   <div class="list-group-item py-3">
                        <div class="row">
                            <div class="col-md-2 text-secondary">
                                Invited by
                            </div>
                            <div class="col-md">
                                {{ $invite->fromUser->name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer  text-md-right border-top-0">
            <form method="post" action="{{ route('invites.update', $invite) }}">
                @csrf
                @method('put')

                <button type="submit" name="submit" value="decline" class="btn btn-danger btn-sm">{{ __('Decline') }}</button>
                <button type="submit" name="submit" value="accept" class="btn btn-primary btn-sm">{{ __('Accept') }}</button>
            </form>
            </div>
        </div>
    </div>
@endsection
