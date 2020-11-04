    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title">{{ $pageTitle }}</h4>
                <div class="row">
                    <div class="col-lg-8 col-sm-12 col-xs-12">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ isset($page) ? $page->name : old('name') }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Content</label>
                                <textarea class="form-control" id="body" name="body" placeholder="Enter Page Content" rows="20">{{ isset($page) ? $page->body : old('body') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="name">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ isset($page) ? $page->meta_title : old('name') }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" placeholder="" rows="3">{{ isset($page) ? $page->meta_description : old('body') }}</textarea>
                            </div>
                    </div>
                    <div class="col-lg-4">
                            <div class="form-group">
                                <label for="name">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="Enter Slug" value="{{ isset($page) ? $page->slug : old('slug') }}">
                            </div>
                            <div class="form-group">
                                <label for="name">URL</label>
                                <span>
                                    @if (isset($page)) 
                                    {{ $page->uri }} <a href="{{$page->url}}" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                    @endif
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="name">Parent</label>
                                <select name="parent_id" class="form-control">
                                    <option value="">No parent</option>
                                    @foreach($pages as $parentPage)
                                    <option value="{{$parentPage->id}}" {{ isset($page) ? $parentPage->id == $page->parent_id ? 'selected' : '' : ''}}>{{$parentPage->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-success mr-2">Save</button>
                            <a href="{{ URL::previous() }}" class="btn btn-dark">Cancel</a>
                            @if (isset($page))
                            <button type="button" class="btn btn-danger ml-2" onclick="return deletePage();">Delete</button>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>