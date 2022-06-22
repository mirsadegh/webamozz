<?php

namespace Sadegh\RolePermissions\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Sadegh\Category\Responses\AjaxResponses;
use Sadegh\RolePermissions\Repositories\RoleRepo;
use Sadegh\RolePermissions\Http\Requests\RoleRequest;
use Sadegh\RolePermissions\Repositories\PermissionRepo;
use Sadegh\RolePermissions\Http\Requests\RoleUpdateRequest;

class RolePermissionsController extends Controller
{

    private $roleRepo;
    private $permissionRepo;

    public function __construct(RoleRepo $roleRepo,PermissionRepo $permissionRepo)
    {
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
    }

    public function index()
    {
        $roles = $this->roleRepo->all();
        $permissions = $this->permissionRepo->all();

        return view('RolePermissions::index',compact('roles','permissions'));
    }

    public function store(RoleRequest $request)
    {

      $this->roleRepo->create($request);
      return redirect(route('role-permissions.index'));
    }

    public function edit($roleId)
    {
      $role =  $this->roleRepo->findById($roleId);
      $permissions = $this->permissionRepo->all();
      return view('RolePermissions::edit',compact('role','permissions'));
    }

    public function update(RoleUpdateRequest $roleUpdateRequest,$id)
    {

        $this->roleRepo->update($id,$roleUpdateRequest);
        return redirect(route('role-permissions.index'));
    }

    public function destroy($roleId)
    {

        $this->roleRepo->delete($roleId);
        return AjaxResponses::successResponse();
    }
}
