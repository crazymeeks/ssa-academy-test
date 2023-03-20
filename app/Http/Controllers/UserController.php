<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\UserServiceInterface;

class UserController extends Controller
{
    
    /** @var \App\Services\UserServiceInterface */
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Index page
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function index(Request $request)
    {
        $users = $this->userService->list();

        if (config('app.env') === 'testing') {
            return $users;
        }

        // return view();
    }

    /**
     * View user details
     *
     * @param integer $id
     * 
     * @return \Illuminate\View\View
     */
    public function viewUserDetails(int $id)
    {
        $user = $this->userService->find($id);

        if (config('app.env') === 'testing') {
            return $user;
        }

    }

    /**
     * Get list of soft deleted users
     *
     * @return \Illuminate\View\View
     */
    public function getTrashedUsers()
    {
        $users = $this->userService->listTrashed();
        
        if (config('app.env') === 'testing') {
            return $users;
        }

        // return view();
    }

    /**
     * Create user
     *
     * @param \App\Http\Requests\UserRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(UserRequest $request)
    {
        
        try {
            $this->userService->store($request->toArray());
            return response()->json([
                'message' => 'User successfully created!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Oops! Something went wrong!',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update existing user
     * 
     * @param \App\Http\Requests\UserRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(UserRequest $request)
    {
        try {
            $this->userService->update($request->id, $request->toArray());

            return response()->json([
                'message' => 'User successfully updated!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Oops! Something went wrong!',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Soft delete user
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function softDeleteUser(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        try {
            $this->userService->destroy($request->id);

            return response()->json([
                'message' => 'User successfully deleted!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Oops! Something went wrong!',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Permanently delete user
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function permanentlyDeleteUser(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        try {
            $this->userService->delete($request->id);

            return response()->json([
                'message' => 'User permanently deleted!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Oops! Something went wrong!',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore softdeleted user
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreUser(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        try {
            $this->userService->restore($request->id);

            return response()->json([
                'message' => 'User successfully restored!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Oops! Something went wrong!',
                'exception' => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Upload photo
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'file' => 'required|image',
        ]);


        try {

            $path = $this->userService->upload($request->file);
            $user = Auth::user();
            $user->photo = $path;
            $user->save();
            return response()->json([
                'message' => 'User photo successfully uploaded!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Oops! Something went wrong!',
                'exception' => $e->getMessage(),
            ], 500);
        }

    }
}
