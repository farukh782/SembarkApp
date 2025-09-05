<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // important: no public resolving
    }

    // listing per role rules
    public function index()
    {
        $user = auth()->user();

        if ($user->isRole('SuperAdmin')) {
            // requirement: SuperAdmin can't see list of all short urls for every company
            abort(403, 'SuperAdmin cannot view all short URLs.');
        }

        if ($user->isRole('Admin')) {
            // Admin can only see short urls NOT created in their own company
            $urls = ShortUrl::where('company_id','!=',$user->company_id)->get();
        } elseif ($user->isRole('Member')) {
            // Member can only see short urls NOT created by themselves
            $urls = ShortUrl::where('user_id','!=',$user->id)->get();
        } else {
            // Sales/Manager -> show urls in their company (or all - choose business rule)
            $urls = ShortUrl::where('company_id', $user->company_id)->get();
        }

        return view('urls.index', compact('urls'));
    }

    // create (only Sales/Manager allowed)
    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user->isAnyRole(['Sales','Manager'])) {
            abort(403, 'Not allowed to create short urls.');
        }

        $request->validate(['original_url' => 'required|url']);

        // generate unique code
        do {
            $code = Str::random(6);
        } while (ShortUrl::where('short_code', $code)->exists());

        $short = ShortUrl::create([
            'original_url' => $request->original_url,
            'short_code' => $code,
            'user_id' => $user->id,
            'company_id' => $user->company_id,
        ]);

        return redirect()->route('urls.index')->with('success', 'Short URL created: '.$short->short_code);
    }

    // redirect - protected route (auth middleware applied)
    public function redirect($code)
    {
        $short = ShortUrl::where('short_code', $code)->firstOrFail();

        // Additional checks can be enforced here (e.g., allow only users from same company, etc.)
        return redirect()->away($short->original_url);
    }
}
