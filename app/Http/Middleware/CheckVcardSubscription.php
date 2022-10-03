<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Vcard;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class CheckVcardSubscription
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return Application|RedirectResponse|Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request = $next($request);

        $array = explode('/', url()->current());
        $vcard = Vcard::whereUrlAlias($array[4])->first();
        $user = User::with('subscription')->whereTenantId($vcard->tenant_id)->first();

        if ($user->subscription->ends_at > Carbon::now()->format('Y-m-d H:i:s')){
            return $request;
        }
        else{
            return abort(404);
        }
    }
}
