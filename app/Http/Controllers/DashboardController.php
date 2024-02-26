<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GoogleService;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     * 
     * @return void
     */
    public function __construct(protected GoogleService $googleService)
    {
        $this->middleware('role_or_permission:Dashboard', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $active = User::where('status', 1)->count();

        $inactive = User::where('status', 0)->count();

        $allUser = User::count();

        if ($this->tokenIsExpired($this->googleService)) {
            session()->flash('message', 'Please authenticate with Google');

            return view('settings.error');
            // $url = $this->googleService->refreshToken($this->googleService->getCredentails()->toArray());
            // request()->session()->put('page_url', request()->url());

            // return redirect()->to($url);
        }

        if (!$url = $this->getSiteBaseUrl()) {
            session()->flash('message', 'Please complete your Site Setting Then Continue');

            return view('settings.error');
        }

        return view('dashboard.index', compact('allUser', 'inactive', 'active'));
    }

    /**
     * Get Statistics
     *
     * @param string $category
     * @return int
     */
    public function getStats()
    {
        $url = $this->getSiteBaseUrl();

        $category = request()->category;
 
        $response = Http::get($url . '/feeds/posts/default?alt=json&category=' . $category);

        return $response->json()['feed']['openSearch$totalResults']['$t'];
    }
}
