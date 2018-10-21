<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class FriendRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        // Array of users' ids that I have sent requests to
        $requests = DB::table('requests')->where('user_to', auth()->user()->id)->get()->pluck('user_from');
        // Get the users out of the ids array
        $users = DB::table('users')->whereIn('id', $requests)->get();

        return view('request')->with('users', $users);
    }

    public function send($to_id)
    {
        User::findOrFail($to_id);
        $request = new Request();
        $request->user_from = auth()->user()->id;
        $request->user_to = $to_id;
        $request->save();
        return redirect()->route('home');
    }

    public function cancel($to_id)
    {
        $request = Request::where('user_from',auth()->user()->id)
            ->where('user_to',$to_id)->get()->pluck('id');
        /** @var $request Collection */
        if($request->isEmpty()){
            abort(404);
        }
        Request::destroy($request);
        return redirect()->route('home');
    }

    public function accept($from_id)
    {
        DB::table('user_friends')->insert([
            'user_from' => $from_id,
            'user_to' => auth()->user()->id,
        ]);

        $request = DB::table('requests')->where('user_to', auth()->user()->id)
            ->where('user_from', $from_id)->get()->pluck('id');
        DB::table('requests')->where('id', $request)->delete();
        return redirect()->back();
    }

    public function reject($from_id)
    {
        $request = DB::table('requests')->where('user_to', auth()->user()->id)->where('user_from', $from_id)->get()->pluck('id');
        DB::table('requests')->where('id', $request)->delete();
        return redirect()->back();
    }
}
