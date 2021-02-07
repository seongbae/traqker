<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Debugbar;
use Cache;
use Auth;
use Yajra\Datatables\Datatables;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Alert;
use Seongbae\Canvas\DataTables\UsersDataTable;
use Seongbae\Canvas\Http\Controllers\CanvasController;
use File;
use Storage;
use Seongbae\Canvas\Traits\UploadTrait;
use Seongbae\Canvas\Events\NewUserCreated;

class UsersController extends CanvasController
{

    use UploadTrait;

    const USER_IMG_STORAGE_PATH = 'app/public/users';
    const USER_IMG_ASSET_PATH = 'storage/users';

    public function index($offset, $limit)
    {
        $users = User::all();

        return UserResource::collection($users);
    }

    public function getUserTeamMembers(User $user, $offset=0, $limit=20)
    {
        $users = User::with('teams')->whereHas('teams', function($query) use ($user) {
            $query->whereIn('teams.id', $user->teams->pluck('id')->toArray());
        })
            ->where('users.id', '!=', $user->id)
            ->orderBy('users.name')->get();

        return UserResource::collection($users->skip($offset)->take($limit));
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function create()
    {
        $roles = Role::all()->sortBy('order');

        return view('canvas::admin.users.create')
            ->with('roles', $roles);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|unique:users,email',
        ]);

        $user = new User;
        $user->name = $request->get('name');
        $user->email =$request->get('email');
        $user->password = Hash::make($request->get('password'));

        if ($request->get('timezone'))
            $user->timezone = $request->get('timezone');
        else
            $user->timezone = 'US/Central'; // set default timezone

        if ($request->file('file'))
            $user->photo_url = $this->uploadOne($request->file('file'), 'users', 'public');

        if ($request->get('timezone'))
            $user->timezone = $request->get('timezone');

        $user->save();

        if ($request->get('role'))
            $user->syncRoles($request->get('role'));

        // Send notification if checked
        if ($request->get('send_email'))
        {
            $args = [];

            if ($request->get('include_password'))
                $args = array('password'=>$request->get('password'));

            event(new NewUserCreated($user, $args));
        }

        flash()->success('User successfully created', 'Success');

        return redirect()->route('admin.users.index');
    }

    public function show(Request $request, User $user)
    {
        if( $request->is('api/*') || $request->ajax())
            return new UserResource($user);
        else
            return view('tasks.show', compact('task'));
    }

    public function edit($id)
    {
        $user = User::find($id);

        $roles = Role::all()->sortBy('order');

        return view('canvas::admin.users.edit')
            ->with('user', $user)
            ->with('roles', $roles);
    }

    public function update(Request $request, User $user)
    {

        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|unique:users,email,'.$user->id,
        ]);

        if (trim($request->get('name')))
            $user->name = trim($request->get('name'));

        if (trim($request->get('email')))
            $user->email = trim($request->get('email'));

        if ($request->get('password') && $request->get('password_confirm') && $request->get('password') != '')
            $user->password = Hash::make(trim($request->get('password')));

        if ($request->get('role'))
            $user->syncRoles($request->get('role'));

        if ($request->get('timezone'))
            $user->timezone = $request->get('timezone');

        if ($request->file('file'))
            $user->photo_url = $this->uploadOne($request->file('file'), 'users', 'public');

        $user->save();

        if( $request->is('api/*') || $request->ajax())
            return new UserResource($user);
        else
        {
            flash()->success('User successfully updated', 'Success');
            return redirect()->back();
        }
    }

    public function updatePassword(Request $request, User $user)
    {
        $this->validate($request, [
            'current_password'=>'required',
            'new_password'=>'required'
        ]);

         if (Hash::check($request->current_password, $user->password))
        {
            if ($request->get('new_password') && $request->get('new_password') != '')
                $user->password = Hash::make(trim($request->get('new_password')));

            $user->save();
        }
        else
        {
            if( $request->is('api/*') || $request->ajax())
                return response()->json(['msg'=>'INCORRECT_PASSWORD'], 200);
            else
            {
                flash()->success('Incorrect password', 'Success');
                return redirect()->back();
            }
        }

        if( $request->is('api/*') || $request->ajax())
            return response()->json(['msg'=>'SUCCESS'], 200);
        else
        {
            flash()->success('User successfully updated', 'Success');
            return redirect()->back();
        }
    }

    public function updateSetting(Request $request, User $user)
    {
        $this->validate($request, [
            'key'=>'required',
            'value'=>'required'
        ]);

        $user->settings([$request->key=>$request->value]);
    }

    public function updateImage(Request $request)
    {
        $user = User::find($request->user_id);

        if ($request->file('file'))
            $user->photo_url = $this->uploadOne($request->file('file'), 'users', 'public');

        $user->save();

        if( $request->is('api/*') || $request->ajax())
            return new UserResource($user);
        else
            return redirect()->back();
    }

    public function destroy($id)
    {
        $user = User::findorFail($id);
        $user->delete();

        flash()->success('User successfully deleted', 'Success');

        return redirect()->route('admin.users.index');
    }



}
