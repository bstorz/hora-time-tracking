<?php

namespace App\Http\Controllers;

use App\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class CompanyController
 * @package App\Http\Controllers
 */
class CompanyController extends Controller {
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        /** @var \App\User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $entries = TimeEntry::whereHas('user', function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->groupBy('user_id')->get();

        return view('company', [
            'time_entries' => $entries
        ]);
    }
}
