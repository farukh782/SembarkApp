@extends('layouts.app')

@section('content')
<h1>Short URLs</h1>

@if(session('success')) <div>{{ session('success') }}</div> @endif

@can('create', App\Models\ShortUrl::class)
<form method="POST" action="{{ route('urls.store') }}">
    @csrf
    <input name="original_url" placeholder="https://example.com" required>
    <button type="submit">Generate</button>
</form>
@endcan

<table border="1" cellpadding="6">
<thead>
<tr><th>Short</th><th>Original</th><th>User</th><th>Company</th></tr>
</thead>
<tbody>
@foreach($urls as $u)
<tr>
    <td><a href="{{ route('short.redirect', $u->short_code) }}">{{ url('/s/'.$u->short_code) }}</a></td>
    <td>{{ $u->original_url }}</td>
    <td>{{ $u->user_id }}</td>
    <td>{{ $u->company_id }}</td>
</tr>
@endforeach
</tbody>
</table>
@endsection
