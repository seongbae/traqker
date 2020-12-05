@extends('canvas::admin.layouts.app')

@section('content')
<div class="search-result-box card">
    <div class="card-header"><b>{{ $searchResults->count() }} results found for "{{ request('query') }}"</b></div>
    <div class="card-body">
        <ul class="nav nav-tabs tabs-bordered">
        @foreach($searchResults->groupByType() as $type => $modelSearchResults)
            <li class="nav-item"><a href="#{{$type}}" data-toggle="tab" aria-expanded="{{$loop->index == 0 ? 'true':'false'}}" class="nav-link {{$loop->index == 0 ? 'active':''}}">{{ ucfirst($type) }}     <span class="badge badge-success ml-1">{{$modelSearchResults->count()}}</span></a></li>
        @endforeach
        </ul>
        <div class="tab-content">
        @foreach($searchResults->groupByType() as $type => $modelSearchResults)
            <div class="tab-pane {{$loop->index == 0 ? 'active':''}}" id="{{$type}}">
                <div class="row">
                    <div class="col-md-12">
                    @foreach($modelSearchResults as $searchResult)
                        <div class="search-item">
                            <h4 class="mb-1"><a href="{{ $searchResult->url }}">{{ $searchResult->title }}</a></h4>
                            <div class="font-13 text-success mb-3">{{ $searchResult->url }}</div>
                            <p class="mb-0 text-muted">{{ $searchResult->description }}</p>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
</div>
@stop
