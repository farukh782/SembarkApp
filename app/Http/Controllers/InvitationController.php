<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // show invite form (optional)
    public function create()
    {
        $companies = Company::all();
        return view('invite.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'role' => ['required', Rule::in(['Admin','Member','Sales','Manager'])],
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $inviter = $request->user();
        $roleToInvite = $request->role;
        $companyId = $request->company_id ? (int)$request->company_id : null;

        // Rule 1: SuperAdmin can't invite an Admin in a new company (interpreted as: when creating a new company)
        // If company_id is null and role is Admin, disallow.
        if ($inviter->isRole('SuperAdmin') && $roleToInvite === 'Admin' && $companyId === null) {
            return back()->withErrors(['role' => 'SuperAdmin cannot invite an Admin into a new company.']);
        }

        // Rule 2: Admin can't invite another Admin or Member in their own company
        if ($inviter->isRole('Admin') && $inviter->company_id === $companyId && in_array($roleToInvite, ['Admin','Member'])) {
            return back()->withErrors(['role' => 'Admin cannot invite Admin or Member in their own company.']);
        }

        // Create user (simple flow) - in real app you'd send invitation email and let user set password
        $tempPassword = 'TempPass@123'; // change per policy
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($tempPassword),
            'role' => $roleToInvite,
            'company_id' => $companyId,
        ]);

        // Optional: store invitation record in DB (if you made invitations table)
        DB::table('invitations')->insert([
            'inviter_id' => $inviter->id,
            'invitee_email' => $request->email,
            'role' => $roleToInvite,
            'company_id' => $companyId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // In production: send proper email with password set link
        return redirect()->back()->with('success', 'Invitation created (user added). Temporary password: '.$tempPassword);
    }
}
