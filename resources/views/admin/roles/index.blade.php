@extends('layouts.admin')

@section('content')
<h1>Rollen Beheer</h1>

<a href="{{ route('admin.roles.create') }}" style="background: #28a745; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; margin-bottom: 20px; display: inline-block;">Nieuwe Rol</a>

<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background: #f8f9fa;">
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Naam</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Beschrijving</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Gebruikers</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Rechten</th>
            <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Acties</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
        <tr>
            <td style="border: 1px solid #ddd; padding: 12px;">{{ $role->name }}</td>
            <td style="border: 1px solid #ddd; padding: 12px;">{{ $role->description }}</td>
            <td style="border: 1px solid #ddd; padding: 12px;">{{ $role->users_count }}</td>
            <td style="border: 1px solid #ddd; padding: 12px;">{{ $role->permissions_count }}</td>
            <td style="border: 1px solid #ddd; padding: 12px;">
                <a href="{{ route('admin.roles.show', $role) }}">Bekijken</a> |
                <a href="{{ route('admin.roles.permissions', $role) }}">Rechten</a>
                @if(!in_array($role->name, ['admin', 'directeur', 'coordinator', 'bewaker']))
                | <a href="{{ route('admin.roles.edit', $role) }}">Bewerken</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $roles->links() }}
@endsection

<?php
// FIX 6: Update je hoofddashboard om admin link te tonen
// Voeg dit toe aan je dashboard view waar de navigatie staat:
?>
@if(auth()->user()->hasPermission('admin.access'))
<a href="{{ route('admin.dashboard') }}" style="background: #dc3545; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px;">Admin Panel</a>
@endif