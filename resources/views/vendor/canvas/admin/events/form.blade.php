 <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputEmail3"  name="name" value="{{ $event != null ? $event->name : null }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                      <textarea name="description" class="form-control">{{ $event ? $event->description : null}}</textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Date</label>
                    <div class="col-sm-10">
                      <input type="datetime-local" class="form-control" name="datetime" id="inputPassword3" value="{{ $event ? $event->date_start : null}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputPassword3" name="address" value="{{ $event ? $event->address : null}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Price</label>
                    <div class="col-sm-10">
                      <input type="number" class="form-control" name="price" id="inputPassword3" value="{{ $event ? $event->price : null}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">External Link</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputPassword3" name="external_link" value="{{ $event ? $event->external_link : null}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Image</label>
                    <div class="col-sm-10">
                        @if ($event)
                          <img src="{{$event->image}}" style="width:200px;">
                        @endif
                       <input id="profile_image" type="file" class="form-control" name="image_url">
                        
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Tags</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="tags" name="tags" data-role="tagsinput" value="{{ $event != null ? $event->getTags() : null }}">
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info">Save</button>
                  <button type="submit" class="btn btn-default">Cancel</button>
                </div>