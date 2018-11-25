<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class IsMyFriend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // CHECK IF THE POSTS OWNER IS A FRIEND OF THE PERSON VIEWING THE PAGE
        // THE Person viewing the page (auth() )
        // The posts owner $user_id

        // Get all the friends of user with $id

        $userFriends = DB::table('user_friends')->where('user_from', $request->user_id)
            ->orWhere('user_to', $request->user_id)->get();
        $friendsIds = [];
        foreach ($userFriends as $friend) {
            if ($friend->user_from == $request->user_id) {
                $friendsIds[] = $friend->user_to;
            } else {
                $friendsIds[] = $friend->user_from;
            }
        }
        // if the current authenticated user is not in his friends
        if (!in_array(auth()->user()->id, $friendsIds) && auth()->user()->id != $request->user_id) {
            return abort(401);
        }
        $request->merge(compact('friendsIds'));
        return $next($request);
    }
}
