<?php

namespace App\Http\Controllers;

use App\User;
use App\User_Friend;
use App\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use function PhpParser\filesInDir;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()

    {
        // another soln
        $members = DB::table('users')->leftJoin('requests',function ($join){
            $join->on('users.id','=','requests.user_to');
            $join->on('requests.user_from' ,'=' ,DB::raw(auth()->id()));
        })->select('users.*','requests.user_from')
            ->whereNotIn('users.id',DB::table('user_friends')
                ->selectRaw('user_to as friend_id')
                ->where('user_from', auth()->id())
                ->union(
                    DB::table('user_friends')->where('user_to',auth()->id())
                        ->selectRaw('user_from as friend_id')
                )->pluck('friend_id'))->whereNotIn('users.id',[auth()->id()])->orderBy('users.id','asc')->paginate(10);
//
//        $users = DB::table('users')
//            ->leftJoin('requests',function ($join){
//            $join->on('users.id','=','requests.user_to');
//            $join->on('requests.user_from' ,'=' ,DB::raw(auth()->id()));
//        })->leftJoin('user_friends',function($join){
//            $join->on('users.id','=','user_friends.user_from');
//            $join->on('user_friends.user_to','=',DB::raw(auth()->id()));
//            $join->orOn('users.id','=','user_friends.user_to');
//            $join->orOn('user_friends.user_from','=',DB::raw(auth()->id()));
//        })->get();
//           ->select('users.*','requests.user_from')
//          ->where('users.id','!=',auth()->id())
//          ->where('user_friends.user_from','=',null)
//          ->where('user_friends.user_to','=',null)
//            ->orderBy('users.id','asc')->get();
//        dd($users);

        return view('home')->with('users',$members);
    }

    public function sentRequest($id){
        User::findOrFail($id);
        $request =new Request();
        $request->user_from = auth()->user()->id ;
        $request->user_to = $id ;
        $request->save();
        return redirect()->route('home');
    }

    public function cancelRequest($id){
        $req = Request::where('user_from',auth()->user()->id)
                            ->where('user_to',$id)->get()->pluck('id');
        /** @var $request Collection */
        if($req->isEmpty()){
            abort(404);
        }
        Request::destroy($req);
        return redirect()->route('home');
    }
}
