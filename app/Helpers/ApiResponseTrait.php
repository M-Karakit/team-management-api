<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait ApiResponseTrait
{
    /**
     * Generate a response with the authentication token and user details.
     *
     * @param $token
     * @param $user
     * @param $guard
     * @return JsonResponse
     */
    public function responseWithToken($token, $user, $guard): JsonResponse
    {

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'expires_in' => Auth::guard($guard)->factory()->getTTL() * 60,
            'token_type' => 'bearer',
        ]);
    }

    /**
     * Return a successful JSON Response
     *
     * @param mixed|null $data the data return in the response
     * @param string $message the success message
     * @param int $status the HTTP Status code
     * @return JsonResponse The JSON response
     */
    public function successResponse(mixed $data = null, string $message = "Operation Done", int $status = 200): JsonResponse
    {
        $array = [
            'status' => 'success',
            'message'=>trans($message),
            'data'=>$data,
        ];

        return response()->json($array, $status);
    }

    /**
     * Return a Error JSON Response
     *
     * @param mixed $data the data return in the response (errors or null)
     * @param string $message the error message
     * @param int $status the HTTP Status code
     * @return JsonResponse The JSON response
     */
    public function errorResponse(mixed $data, string $message, int $status): JsonResponse
    {
        $array = [
            'status' => 'error',
            'data'=>$data,
            'message'=>trans($message)
        ];
        return response()->json($array, $status);
    }

    /**
     * Return a paginated JSON Response
     *
     * @param mixed $data the data that will be paginated
     * @param string $message the success message
     * @param int $status the HTTP Status code
     * @return JsonResponse The JSON response
     */
    public function resourcePaginated(mixed $data, string $message = 'Operation Success', int $status = 200): JsonResponse
    {
        $paginator = $data->resource;
        $resourceData = $data->items();
        Log::info('Paginated Courses:', $resourceData);

        $array = [
            'status' => 'success',
            'message'=>trans($message),
            'data'=>$resourceData,
            'pagination' => [
                'total'        => $paginator->total(),
                'count'        => $paginator->count(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'total_pages'  => $paginator->lastPage(),
            ],
        ];
        return response()->json($array,$status);
    }
}
