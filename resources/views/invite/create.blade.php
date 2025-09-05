@extends('layouts.app')

@section('content')
<h1>Invite</h1>
@if($errors->any()) <div>{{ implode(', ', $errors->all()) }}</div> @endif
@if(session('success')) <div>{{ session('success') }}</div> @endif

<form method="POST" action="{{ route('invite.store') }}">
    @csrf
    <input name="name" placeholder="Name" required><br>
    <input name="email" placeholder="email" required><br>
    <select name="role">
        <option>Admin</option>
        <option>Member</option>
        <option>Sales</option>
        <option>Manager</option>
    </select><br>
    <select name="company_id">
        <option value="">-- New Company (leave blank) --</option>
        @foreach($companies as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
        @endforeach
    </select><br>
    <button type="submit">Send Invite</button>
</form>
@endsection
