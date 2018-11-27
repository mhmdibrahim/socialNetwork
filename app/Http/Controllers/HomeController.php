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
//        dd($members);
//        $first = DB::table('users')->leftJoin('requests','users.id','=','requests.user_to')
//            ->select('users.*','user_from')->where('user_from','=',auth()->id());
////        dd($first);
//        $second = DB::table('users')->whereNotIn('id',DB::table('user_friends')
//            ->selectRaw('user_to as friend_id')
//            ->where('user_from', auth()->id())
//            ->union(
//                DB::table('user_friends')->where('user_to',auth()->id())
//                    ->selectRaw('user_from as friend_id')
//            )->pluck('friend_id'))->whereNotIn('id',[auth()->id()])->union($first)->get();
//        dd($second);
//
//        $requests = Request::where('user_from',auth()->user()->id)->get()->pluck('user_to')->toArray();
////        dd($requests->toArray());
//        $users = DB::table('users')->whereNotIn('id',DB::table('user_friends')
//            ->selectRaw('user_to as friend_id')
//            ->where('user_from', auth()->id())
//            ->union(
//                DB::table('user_friends')->where('user_to',auth()->id())
//                    ->selectRaw('user_from as friend_id')
//            )->pluck('friend_id'))->get();
//            dd($users);
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
        $request = Request::where('user_from',auth()->user()->id)
                            ->where('user_to',$id)->get()->pluck('id');
        /** @var $request Collection */
        if($request->isEmpty()){
            abort(404);
        }
        Request::destroy($request);
        return redirect()->route('home');

    }
}
