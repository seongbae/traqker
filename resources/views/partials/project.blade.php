<div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 d-flex align-items-stretch mb-4">
    <div class="card" style="min-height:160px;width:100%">
        <div class="card-body">
            <div class="project-title my-1">
                <strong><a href="{{ route('projects.show', ['project'=>$project])}}" >{{$project->name}}</a></strong>
            </div>

            <div class="project-description">{{Helper::limitText($project->description, 200)}}</div>
        </div>
        <div class="card-footer bg-white text-right p-2">
            @foreach($project->members as $member)
                <img src="{{ $member->photo }}" alt="{{ $member->name }}" title="{{ $member->name }}" class="rounded-circle profile-small mr-1" >
            @endforeach
        </div>
    </div>
</div>
