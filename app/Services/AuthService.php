<?php

namespace App\Services;

use App\Helpers\ApiResponseTrait;
use App\Models\Student;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthService
{
    use ApiResponseTrait;

    protected ?string $guard = null;

    /**
     * Authenticate a user and return a token.
     *
     * @param array $credentials
     * @return JsonResponse
     */
    public function login(array $credentials): JsonResponse
    {
        try {
            $email = $credentials['email'];

            $student = Student::where('email', $email)->first();

            $guard = $student ? 'student' : 'api';

            if (!$token = Auth::guard($guard)->attempt($credentials)) {
                return $this->errorResponse(null, 'Invalid credentials', 401);
            }

            return $this->responseWithToken($token, Auth::guard($guard)->user(), $guard);
        } catch (Exception $e) {
            Log::error('Error Logging in: ' . $e->getMessage());
            throw new HttpResponseException($this->errorResponse(null, 'There is something wrong with the server', 500));
        }
    }

    /**
     * Refresh the authentication token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        try {
            $newToken = Auth::guard($this->guard)->refresh();
            return $this->responseWithToken($newToken, Auth::guard($this->guard)->user(), $this->guard);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Token has expired',
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Could not refresh the token',
            ], 500);
        }
    }

    /**
     * Log the user out.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            Auth::guard($this->guard)->logout();
            return response()->json([
                'status' => 'success',
                'message' => 'User has been logged out',
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Could not log out the user',
            ], 500);
        }
    }
}
