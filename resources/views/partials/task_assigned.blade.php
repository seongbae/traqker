@foreach($users as $user)
<img src="{{$user->photo}}" alt="{{$user->name}}" title="{{$user->name}}" class="rounded-circle profile-small mr-1">
@endforeach
