<div class="row">
   <div class="col-lg-12">
      <form action="/account/{{Auth::id()}}/password" method="POST" style="display:inline;">
          {{ csrf_field() }}
          <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="name" name="password" placeholder="Password">
          </div>
          <div class="form-group">
              <label for="password_confirm">Password Confirm</label>
              <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Password">
          </div>
          <button type="submit" class="btn btn-success mr-2">Submit</button>
          
      </form>
   </div>
</div>