<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BulkActionController extends Controller
{
    public function handleBulkAction(Request $request)
    {
        $type = request()->input('type');

        $validatedData = $request->validate([
            'model' => 'required|string',
            'ids' => 'required',
        ], __('request.messages'), [
            'model' => 'Tên model',
            'ids' => 'Danh sách ID',
        ]);

        $modelClass = 'App\\Models\\' . $validatedData['model'];

        // Kiểm tra xem class có tồn tại hay không
        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Model không hợp lệ.'], 400);
        }

        try {
            switch ($type) {
                case 'delete':
                    // Xóa các bản ghi dựa trên ID (soft delete)
                    $modelClass::whereIn('id', $validatedData['ids'])->delete();
                    return response()->json(['message' => 'Xóa thành công!'], 200);

                case 'changePublished':
                    if (!is_array($validatedData['ids'])) $validatedData['ids'] = [$validatedData['ids']];
                    // Thay đổi trạng thái published withTrashed()->
                    $modelClass::whereIn('id', $validatedData['ids'])->get()->map(function ($q) {
                        return $q->update(['published' => !$q->published]);
                    });
                    return response()->json(['success' => true, 'message' => 'Thay đổi trạng thái thành công!'], 200);

                case 'restore':
                    // Phục hồi các bản ghi đã xóa mềm
                    $modelClass::onlyTrashed()->whereIn('id', $validatedData['ids'])->restore();
                    return response()->json(['success' => true, 'message' => 'Phục hồi thành công!'], 200);

                case 'forceDelete':
                    // Xóa vĩnh viễn các bản ghi đã xóa mềm
                    $modelClass::whereIn('id', $validatedData['ids'])->forceDelete();
                    return response()->json(['success' => true, 'message' => 'Xóa vĩnh viễn thành công!'], 200);
                default:
                    return response()->json(['success' => false, 'message' => 'Loại hành động không hợp lệ.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }
}
