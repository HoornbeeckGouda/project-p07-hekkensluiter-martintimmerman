@extends('layouts.admin')

@section('content')
<h1>Admin Dashboard</h1>
<p>Welkom in het admin panel!</p>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
    @can('admin.roles.manage')
    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
        <h3>Rollen Beheer</h3>
        <p>Beheer gebruikersrollen en hun rechten</p>
        <a href="{{ route('admin.roles.index') }}" style="background: #007cba; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px;">Ga naar rollen</a>
    </div>
    @endcan

    @can('admin.users.view')
    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
        <h3>Gebruikers Beheer</h3>
        <p>Beheer alle gebruikers van het systeem</p>
        <a href="{{ route('admin.users.index') }}" style="background: #007cba; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px;">Ga naar gebruikers</a>
    </div>
    @endcan
</div>
@endsection
