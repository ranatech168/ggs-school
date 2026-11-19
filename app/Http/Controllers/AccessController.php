<?php
namespace App\Http\Controllers;
use App\Models\{User,Role,Permission};use Illuminate\Http\Request;use Illuminate\Validation\Rule;
class AccessController extends Controller {
 public function users(){return view('admin.users',['users'=>User::with('role')->paginate(15),'roles'=>Role::orderBy('name')->get()]);}
 public function saveUser(Request $r){$id=$r->input('id');$user=$id?User::findOrFail($id):new User;$data=$r->validate(['id'=>'nullable|exists:users,id','name'=>'required|string|max:100','email'=>['required','email','max:190',Rule::unique('users')->ignore($id)],'role_id'=>'required|exists:roles,id','password'=>[$id?'nullable':'required','string','min:12','max:200']]);unset($data['id']);if(empty($data['password']))unset($data['password']);if((int)$id===$r->user()->id && (int)$data['role_id']!==$user->role_id)abort(422,'You cannot change your own role.');$user->fill($data)->save();return response()->json(['message'=>'User saved.','redirect'=>route('admin.users')]);}
 public function deleteUser(Request $r,User $user){abort_if($user->is($r->user()),422,'You cannot delete yourself.');$user->delete();return response()->json(['message'=>'User deleted.']);}
 public function roles(){return view('admin.roles',['roles'=>Role::with('permissions')->withCount('users')->orderBy('id')->get(),'permissions'=>Permission::orderBy('id')->get()]);}
 public function saveRole(Request $r){$id=$r->input('id');$role=$id?Role::findOrFail($id):new Role;abort_if($role->exists && $role->slug==='super-admin',403,'The super administrator role is protected.');$data=$r->validate(['name'=>'required|string|max:100','slug'=>['required','alpha_dash:ascii','max:100',Rule::unique('roles')->ignore($id)],'permissions'=>'nullable|array','permissions.*'=>'integer|exists:permissions,id']);$role->fill($data)->save();$role->permissions()->sync($data['permissions']??[]);return response()->json(['message'=>'Role saved.','redirect'=>route('admin.roles')]);}
 public function deleteRole(Role $role){abort_if($role->slug==='super-admin'||$role->users()->exists(),422,'Protected role or role has users.');$role->delete();return response()->json(['message'=>'Role deleted.']);}
}
