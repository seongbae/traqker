<div class="row">
   <div class="col-lg-12">
      <form action="/account/{{Auth::id()}}/profile" method="POST" enctype="multipart/form-data" style="display:inline;">
          {{ csrf_field() }}
           @method('PUT')
          <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{$user->name}}">
          </div>
          <div class="form-group">
              <label for="email">Email address</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{$user->email}}">
          </div>
          <div class="form-group">
              <img src="{{$user->photo_url}}" style="width:80px;">
              <input type="file" class="form-control-file" id="file" name="file">
          </div>
          <button type="submit" class="btn btn-success mr-2">Submit</button>
          
      </form>
   </div>
</div>