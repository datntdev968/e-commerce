<?php

namespace App\Http\Controllers\Api\Permisstion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permisstion\PermisstionRequest;
use App\Http\Resources\Permisstion\PermisstionCollection;
use App\Traits\PaginateTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermisstionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use PaginateTrait;
    protected $permisstion;

    public function __construct(Permission $permission)
    {
        $this->permisstion = $permission;
    }
    public function index()
    {
        $conditions = $this->payload();

        $query = $this->permisstion->query();

        $response = new PermisstionCollection(
            $this->filterAndPaginate(
                $query,
                ['id', 'name', 'created_at'],
                $conditions,
                []
            )
        );

        return successResponse('Fetching data successfully', $response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermisstionRequest $request)
    {

        return transaction(function () use ($request) {
            $credentials = $request->validated();

            $this->permisstion->create($credentials);

            return handleResponse('Thêm quyền thành công.', 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermisstionRequest $request, string $id)
    {
        return transaction(function () use ($request, $id) {
            $credentials = $request->validated();

            if (!$permisstion = $this->permisstion->query()->find($id)) {
                return errorResponse('Bản ghi không tồn tại trên hệ thống!');
            }

            $permisstion->update($credentials);

            return handleResponse('Cập nhật quyền thành công.', 200);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function payload()
    {
        return  [
            'searchField' => request('searchField', 'name'),
            'sortField' => request('sortField', 'id'),
            'sortOrder' => request('sortOrder', 'desc'),
            'searchText' => request()->searchText,
            'filterCustom' => request('filterCustom'),
            'deleted_at' => request('deleted_at'),
        ];
    }
}
