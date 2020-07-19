<?php


namespace App\Modules\Bot\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\OAuthAccount;
use App\Modules\Bot\Http\Requests\OauthRequest;

class OauthController extends Controller
{
    public function findOne(OauthRequest $request)
    {
        return OAuthAccount::whereOauthProviderId($request->oauth_provider_id)
            ->whereAccountId($request->account_id)
            ->firstOrFail();
    }
}
