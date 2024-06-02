<?php
namespace App\Traits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

trait Destroy {
    function delete(Model $model): JsonResponse
    {
        try {
            $model->deleteOrFail();
            return response()->json(['message' => 'DELETED'], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'DELETE_ERROR'], 500);
        }
    }
}
