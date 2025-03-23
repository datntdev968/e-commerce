<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserCollection;
use App\Models\User;
use App\Traits\PaginateTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use PaginateTrait;

    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conditions = $this->payload();

        $query = $this->user->query();

        $response = new UserCollection(
            resource: $this->filterAndPaginate(
                $query,
                ['id', 'name', 'phone', 'email', 'address', 'gender', 'uni_code', 'birthday', 'created_at', 'published'],
                $conditions,
                [],
                ['user_group_id' => request('user_group_id')]
            )
        );

        return successResponse('Fetching data successfully', $response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
