<?php

    namespace App\Http\Controllers\Api\Auth;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\Auth\AuthRequest;
    use App\Models\User;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;

    class AuthController extends Controller
    {
        /**
         * Handle the incoming request.
         */
        public function __invoke(AuthRequest $request): JsonResponse
        {
            $credentials = $request->validated();

            $auth = Auth::attempt($credentials);

            if ($auth) {
                /** @var User $user */
                $user = Auth::user();

                $token = $user->createToken('user-token')->plainTextToken;

                return response()->json([
                    'message' => 'Authentifier avec succes.',
                    'user' => [
                        'name' => $user->name,
                        'role' => $user->role->name,
                        'token' => $token,
                    ]
                ]);
            } else {
                return response()->json([
                    'message' => 'Les informations renseignées sont incorrectes.',
                ], status: 401);
            }
        }
    }
