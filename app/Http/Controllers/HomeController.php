<?php

namespace App\Http\Controllers;

use App\User;
use App\User_Friend;
use App\Request;
use Illuminate\Support\Collection;
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
        $requests = Request::where('user_from',auth()->user()->id)->get()->pluck('user_to')->toArray();
//        dd($requests->toArray());
        $user_friends = User_Friend::where('user_from',auth()->user()->id)
                                    ->orWhere('user_to',auth()->user()->id)->get();
        $friendsIds = [];
        //TODO: include myself in friends array

        foreach ($user_friends as $friend){
            if($friend->user_from == auth()->id()){
                $friendsIds[] = $friend->user_to;
            }else{
                $friendsIds[] = $friend->user_from;
            }
        }
        $friendsIds[] = auth()->user()->id;
//        dd($friendsIds);
//        dd($user_friends);
        $users = User::whereNotIn('id',$friendsIds)->paginate(10);
//        dd($users);
        return view('home')->with('members',$users)
                                ->with('requests',$requests);
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
