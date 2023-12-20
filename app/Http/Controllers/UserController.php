<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $email = $request->email;
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $user->email = $email;
            } else {
                return response()->json(['error' => 'Invalid email address'], 422);
            }
            $user->password = Hash::make($request->password);
            $user->save();

            return [
                'status' => Response::HTTP_OK,
                'message' => "Success",
                'data' => $user
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function deleteUser(Request $request)
    {
        if (!empty($request->email)) {
            $user = User::where('email', $request->email)->first();
        } else {
            $user = User::where('id', $request->id)->first();
        }

        if(!empty($user)) {
            $user->delete();
            return [
                'status' => Response::HTTP_OK,
                'message' => "Success",
                'data' => $user
            ];
        }

        return[
            'status' => Response::HTTP_NOT_FOUND,
            'message' => "User not found",
            'data' => []
        ];
    }

    public function updateUser(Request $request)
    {
        if (!empty($request->email)) {
            $user = User::where('email', $request->email)->first();
        } else {
            $user = User::where('id', $request->id)->first();
        }

        if(!empty($user)) {
            try {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->save();

                return [
                    'status' => Response::HTTP_OK,
                    'message' => "Success",
                    'data' => $user
                ];
            } catch (\Exception $e) {
                return [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $e->getMessage(),
                    'data' => []
                ];
            }
        }
    }

    public function getUserBudgets()
    {
        $user = Auth::user();

        // Load the user's categories with budgets
        $categoriesWithBudgets = $user->categories()->withPivot('budget')->get();
        $categoriesWithBudgets->makeHidden(['created_at', 'updated_at']);

        return response()->json($categoriesWithBudgets);
    }


}
