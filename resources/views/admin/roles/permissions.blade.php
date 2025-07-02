@extends('layouts.admin')

@section('content')
<h1>Rechten beheren voor rol: {{ $role->name }}</h1>

<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.roles.index') }}" style="background: #6c757d; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px;">← Terug naar rollen</a>
</div>

<div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
    <h3>{{ $role->name }}</h3>
    <p>{{ $role->description }}</p>
    <p><strong>Gebruikers met deze rol:</strong> {{ $role->users()->count() }}</p>
</div>

<form method="POST" action="{{ route('admin.roles.updatePermissions', $role) }}">
    @csrf
    @method('PUT')
    
    @foreach($permissions as $group => $groupPermissions)
    <div style="margin-bottom: 30px; border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
        <h3 style="margin-top: 0; color: #007cba; text-transform: capitalize;">{{ $group }}</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 10px;">
            @foreach($groupPermissions as $permission)
            <label style="display: flex; align-items: center; padding: 8px; background: #f8f9fa; border-radius: 4px;">
                <input type="checkbox" 
                       name="permissions[]" 
                       value="{{ $permission->id }}"
                       {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                       style="margin-right: 10px;">
                <div>
                    <strong>{{ $permission->name }}</strong><br>
                    <small style="color: #666;">{{ $permission->description }}</small>
                </div>
            </label>
            @endforeach
        </div>
    </div>
    @endforeach
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
        <button type="submit" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
            Rechten Opslaan
        </button>
        <a href="{{ route('admin.roles.index') }}" style="background: #6c757d; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin-left: 10px;">
            Annuleren
        </a>
    </div>
</form>

<script>
// Voeg wat JavaScript toe voor betere gebruikerservaring
document.addEventListener('DOMContentLoaded', function() {
    // Voeg "Alles selecteren" functionaliteit toe per groep
    const groups = document.querySelectorAll('[style*="margin-bottom: 30px"]');
    
    groups.forEach(group => {
        const groupTitle = group.querySelector('h3');
        if (groupTitle) {
            const selectAllBtn = document.createElement('button');
            selectAllBtn.type = 'button';
            selectAllBtn.textContent = 'Alles selecteren';
            selectAllBtn.style.cssText = 'background: #007cba; color: white; padding: 4px 8px; border: none; border-radius: 4px; font-size: 12px; margin-left: 10px; cursor: pointer;';
            
            const deselectAllBtn = document.createElement('button');
            deselectAllBtn.type = 'button';
            deselectAllBtn.textContent = 'Alles deselecteren';
            deselectAllBtn.style.cssText = 'background: #dc3545; color: white; padding: 4px 8px; border: none; border-radius: 4px; font-size: 12px; margin-left: 5px; cursor: pointer;';
            
            groupTitle.appendChild(selectAllBtn);
            groupTitle.appendChild(deselectAllBtn);
            
            const checkboxes = group.querySelectorAll('input[type="checkbox"]');
            
            selectAllBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = true);
            });
            
            deselectAllBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = false);
            });
        }
    });
});
</script>

<style>
button:hover {
    opacity: 0.9;
}

label:hover {
    background: #e9ecef !important;
}

input[type="checkbox"] {
    transform: scale(1.2);
}
</style>

@endsection