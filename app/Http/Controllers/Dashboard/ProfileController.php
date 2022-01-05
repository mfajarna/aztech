<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Profile\UpdateDetailUserRequest;
use App\Http\Requests\Dashboard\Profile\UpdateProfileRequest;
use App\Models\DetailUser;
use App\Models\ExpericenceUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use File;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model_user = User::where('id', Auth::user()->id)->first();
        $experience_user = ExpericenceUser::where('detail_user_id', $model_user->detail_user->id)
                                    ->orderBy('id', 'asc')
                                    ->get();

        return view('pages.Dashboard.profile', compact([
            'model_user',
            'experience_user',
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request_profile, UpdateDetailUserRequest $request_detail_user)
    {
        $data_profile = $request_profile->all();
        $data_detail_user = $request_detail_user->all();

        // get photo user
        $get_photo = DetailUser::where('users_id',  Auth::user()->id)->first();

        // delete old file from storage
        if(isset($data_detail_user['photo']))
        {
            $data = 'storage/' . $get_photo('photo');

            if(File::exists($data))
            {
                File::delete($data);
            }else
            {
                File::delete('storage/app/public' . $get_photo['photo']);
            }
        }

        // store file to storage
        if(isset($data_detail_user['photo'])){
            $data_detail_user['photo'] = $request_detail_user->file('photo')->store(
                'assets/photo', 'public'
            );

        }

        $user = User::find(Auth::user()->id);
        $user->update($data_profile);

        $detail_user = DetailUser::find($user->detail_user->id);
        $detail_user->update($data_detail_user);

        $experience_user_id = ExpericenceUser::where('detail_user_id', $detail_user['id'])->first();
        if(isset($experience_user_id))
        {
            foreach($data_profile['experience'] as $key => $value)
            {
                $experience_user = ExpericenceUser::find($key);
                $experience_user->detail_user_id = $detail_user['id'];
                $experience_user->experience = $value;
                $experience_user->save();
            }
        }else{
            foreach($data_profile['experience'] as $key => $value)
            {
                if(isset($value))
                {
                    $experience_user = new ExpericenceUser;
                    $experience_user->detail_user_id = $detail_user['id'];
                    $experience_user->experience = $value;
                    $experience_user->save();
                }
            }
        }

        toast()->success('Update has been success!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete($id)
    {
        $get_user_photo = DetailUser::where('users_id', Auth::user()->id)->first();
        $path_photo = $get_user_photo['photo'];

        $data = DetailUser::find($get_user_photo['id']);
        $data->photo = NULL;
        $data->save();

        $data = 'storage/' . $path_photo;
        if(File::exist($data))
        {
            File::delete();
        }else{
            File::delete('storage/app/public/' . $path_photo);
        }


        toast()->success('Delete has been success');
        return back();
    }
}
