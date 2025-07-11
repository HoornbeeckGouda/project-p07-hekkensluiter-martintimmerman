@extends('layouts.admin')

@section('content')
    <h1>Permissies voor rol: {{ $role->name }}</h1>

    <form method="POST" action="{{ route('admin.roles.updatePermissions', $role) }}">
        @csrf
        @method('PUT')

        @foreach($permissions as $group => $permissionList)
            <h3>{{ ucfirst($group) }}</h3>
            <div style="margin-bottom: 1rem;">
                @foreach($permissionList as $permission)
                    <label>
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                        {{ $permission->description }} ({{ $permission->name }})
                    </label><br>
                @endforeach
            </div>
        @endforeach

        <button type="submit">Permissies opslaan</button>
        <a href="{{ route('admin.roles.index') }}">Terug</a>
    </form>
@endsection