<div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 d-flex align-items-stretch mb-4">
    <div class="card" style="min-height:160px;width:100%">
        <div class="card-body">
            <p class="card-text text-center"><a href="{{ route('projects.show', ['project'=>$project])}}" >{{$project->name}}</a></p>
        </div>
        <div class="card-footer bg-white text-right p-2">
            @foreach($project->members as $member)
                <img src="/storage/{{ $member->photo }}" alt="{{ $member->name }}" title="{{ $member->name }}" class="img-circle elevation-2 " style="width:24px;">
            @endforeach
        </div>
    </div>
</div>
