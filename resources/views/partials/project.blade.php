<div class="text-center my-auto mr-2">
    <div class="card card-block d-flex" style="height:120px;width:120px;">
        <div class="card-body align-items-center d-flex justify-content-center">
            <a href="{{ route('projects.show', ['project'=>$project])}}">{{$project->name}}</a>
        </div>
    </div>
</div>
