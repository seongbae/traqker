@extends('canvas::admin.layouts.app')

@section('title', __('Edit Section'))
@section('content')
    @include('teams.menus')

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                    <h1>{{ $wikipage->title }}</h1>
                        <div class="text-muted text-sm">Last edited by {{$wikipage->lastUpdatedBy->name}} {{(new \Carbon\Carbon($wikipage->updated_at))->diffForHumans()}}</div>
                    </div>
                    <div>
                    <a href="{{ route('wikipages.edit',['type'=>$type,'slug'=>$team->slug,'wikiPage'=>$wikipage]) }}" class="btn btn-secondary btn-sm">Edit</a>
                    <a href="{{ route('wikipages.create',['type'=>$type,'slug'=>$team->slug]) }}" class="btn btn-primary btn-sm">New</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            @markdown($wikipage->content)
                        </div>
                        <div class="col-lg-2">
                            <div class="card">
                                <div class="card-header">
                                    Pages
                                </div>
                                <div class="card-body">
                                    @foreach($wikipage->wikiable->wikiPages as $page)
                                        <a href="{{route('wikipages.show', ['type'=>$page->simple_type,'slug'=>$page->wikiable->slug,'wikiPage'=>$page])}}">{{$page->title}}</a><br>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-md-right border-top-0">

                </div>
            </div>
@endsection


@push('scripts')

<script>
    $(document).ready(function(){
        $("#ManyTable").on("click", "#DeleteButton", function() {
           $(this).closest("tr").remove();
        });
    });
</script>

@endpush
