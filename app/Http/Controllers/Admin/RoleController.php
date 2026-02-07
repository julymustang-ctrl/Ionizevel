<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Rule;

class RoleController extends Controller
{
    /**
     * Rol listesi
     */
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('role_level', 'desc')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Yeni rol formu
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Rol kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:100',
            'role_code' => 'required|string|max:50|unique:roles,role_code',
            'role_level' => 'required|integer|min:0|max:10000',
        ]);

        Role::create([
            'role_name' => $request->role_name,
            'role_code' => $request->role_code,
            'role_level' => $request->role_level,
            'role_description' => $request->role_description,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Rol düzenleme formu
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        
        // İzin listesi
        $permissions = $this->getPermissionList();
        
        // Rolün izinleri
        $roleRules = Rule::where('id_role', $id)->pluck('value', 'resource')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'roleRules'));
    }

    /**
     * Rol güncelle
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        $request->validate([
            'role_name' => 'required|string|max:100',
            'role_level' => 'required|integer|min:0|max:10000',
        ]);

        $role->update([
            'role_name' => $request->role_name,
            'role_level' => $request->role_level,
            'role_description' => $request->role_description,
        ]);

        // İzinleri güncelle
        if ($request->has('permissions')) {
            Rule::where('id_role', $id)->delete();
            
            foreach ($request->permissions as $resource => $actions) {
                foreach ($actions as $action => $value) {
                    if ($value) {
                        Rule::create([
                            'id_role' => $id,
                            'resource' => $resource,
                            'actions' => $action,
                            'value' => 1,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Rol sil
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        // Super Admin silinemesin
        if ($role->role_level >= 10000) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Super Admin role cannot be deleted.');
        }
        
        // Rol kullanılıyorsa silinemesin
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role with assigned users.');
        }

        $role->rules()->delete();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * İzin listesi
     */
    private function getPermissionList(): array
    {
        return [
            'pages' => [
                'label' => 'Pages',
                'actions' => ['view', 'create', 'edit', 'delete', 'publish']
            ],
            'articles' => [
                'label' => 'Articles',
                'actions' => ['view', 'create', 'edit', 'delete', 'publish']
            ],
            'categories' => [
                'label' => 'Categories',
                'actions' => ['view', 'create', 'edit', 'delete']
            ],
            'media' => [
                'label' => 'Media',
                'actions' => ['view', 'upload', 'edit', 'delete']
            ],
            'menus' => [
                'label' => 'Menus',
                'actions' => ['view', 'create', 'edit', 'delete']
            ],
            'translations' => [
                'label' => 'Translations',
                'actions' => ['view', 'create', 'edit', 'delete']
            ],
            'users' => [
                'label' => 'Users',
                'actions' => ['view', 'create', 'edit', 'delete']
            ],
            'settings' => [
                'label' => 'Settings',
                'actions' => ['view', 'edit']
            ],
            'themes' => [
                'label' => 'Themes',
                'actions' => ['view', 'edit']
            ],
            'roles' => [
                'label' => 'Roles',
                'actions' => ['view', 'create', 'edit', 'delete']
            ],
        ];
    }
}
