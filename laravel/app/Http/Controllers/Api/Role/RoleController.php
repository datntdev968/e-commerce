<?php

namespace App\Http\Controllers\Api\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleRequest;
use App\Http\Resources\Role\RoleCollection;
use App\Http\Resources\Role\RoleResource;
use App\Traits\PaginateTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    use PaginateTrait;

    public $role;
    public function __construct(Role $role)
    {
        $this->role = $role;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {

        $this->authorize('view',  $this->role);

        $conditions = $this->payload();

        $query = $this->role->newQuery();

        $response = new RoleCollection(
            resource: $this->filterAndPaginate(
                $query,
                ['id', 'name', 'guard_name', 'created_at'],
                $conditions,
                ['permissions']
            )
        );

        return successResponse('Fetching data successfully', $response);
    }

    public function getPermissions()
    {
        $this->authorize('viewAny',  $this->role);

        $permissions = Permission::query()
            ->select('name', 'id')
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy(function ($permission) {
                $parts = explode(' ', $permission->name);
                return $parts[0];
            });

        return successResponse('Fetching data successfully', $permissions);
    }

    public function payload()
    {
        return  [
            'searchField' => request('searchField', 'name'),
            'sortField' => request('sortField', 'id'),
            'sortOrder' => request('sortOrder', 'desc'),
            'searchText' => request('searchText'),
            'filterCustom' => request('filterCustom'),
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        return transaction(function () use ($request) {
            $credentials = $request->validated();

            $role = $this->role->create(['name' => $credentials['name']]);

            if (!empty($credentials['permissions'])) {
                $role->syncPermissions($credentials['permissions']);
            }

            return handleResponse('Thêm mới vai trò thành công.', 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = $this->role->with('permissions')->find($id);

        if (!$role) {
            return errorResponse('Không tìm thấy dữ liệu.', 404);
        }

        $data = new RoleResource($role);

        return successResponse('Fetching data successfully', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        return transaction(function () use ($request, $id) {
            $role = $this->role->find($id);

            if (!$role) {
                return errorResponse('Không tìm thấy dữ liệu.', 404);
            }

            $credentials = $request->validated();

            $role->update(['name' => $credentials['name']]);

            if (!empty($credentials['permissions'])) {
                $role->syncPermissions($credentials['permissions']);
            }

            return handleResponse('Cập nhật vai trò thành công.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
