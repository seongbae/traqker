<div class="row">
   <div class="col-md-3 col-sm-3">
      <div class="user-wrapper">
        <center>
         <img src="{{$user->photo}}" class="rounded-circle img-fluid p-4">
         <div class="mt-2">{{$user->name}}</div>
         
       </center>
      </div>
   </div>
   <div class="col-md-9 col-sm-9  user-wrapper">
    <div class="card">
             
        <table class="table table-user-information">
          <tbody>
            <tr>
              <td>Name:</td>
              <td>{{$user->name}}</td>
            </tr>
            <tr>
              <td>E-mail:</td>
              <td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>
            </tr>
            <tr>
              <td>Created</td>
              <td>{{ $user->created_at}}</td>
            </tr>
          </tbody>
        </table>
        
   </div>
  </div>
</div>