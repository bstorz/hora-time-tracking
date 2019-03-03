<?php

namespace App\Http\Controllers;

use App\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class TimeEntryController
 * @package App\Http\Controllers
 */
class TimeEntryController extends Controller {
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        return view('mypage', [
            'time_entries' => TimeEntry::where('user_id', $user->getAuthIdentifier())->get()
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('mypage');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\TimeEntry           $time_entry
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, TimeEntry $time_entry)
    {
        return view('mypage', [
            'time_entry' => $time_entry
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\TimeEntry           $time_entry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, TimeEntry $time_entry): \Illuminate\Http\RedirectResponse
    {
        try {
            $time_entry->delete();
        }
        catch (\Exception $e) {
            return redirect()->back('Unable to delete.  Reason: ' . $e->getMessage());
        }
        return redirect()->route('time_entry.index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        (new TimeEntry())->fill($request->input())->save();
        return redirect()->route('time_entry.index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\TimeEntry           $time_entry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, TimeEntry $time_entry): \Illuminate\Http\RedirectResponse
    {
        $time_entry->fill($request->input());
        return redirect()->route('time_entry.index');
    }
}
