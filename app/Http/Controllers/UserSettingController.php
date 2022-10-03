<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use App\Repositories\UserSettingRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class UserSettingController extends AppBaseController
{
    /**
     * @var UserSettingRepository
     */
    private $userSettingRepository;

    /**
     * SettingController constructor.
     * @param  UserSettingRepository  $userSettingRepository
     */
    public function __construct(UserSettingRepository $userSettingRepository)
    {
        $this->userSettingRepository = $userSettingRepository;
    }

    /**
     * @param  Request  $request
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $sectionName = $request->get('section') !== null;
        $setting = UserSetting::where('user_id', getLogInUserId())->pluck('value', 'key')->toArray();

        return view("user-settings.credentials", compact('setting','sectionName'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $id = Auth::id();
        $this->userSettingRepository->update($request->all(), $id);

        Flash::success(__('messages.flash.setting_update'));

        return Redirect::back();
    }

}
