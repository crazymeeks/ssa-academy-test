<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\UserServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show create user form page
     *
     * @param \App\Models\User $user
     * 
     * @return \Illuminate\View\View
     */
    public function createUser(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    /**
     * View user details
     *
     * @param integer $id
     * 
     * @return \Illuminate\View\View
     */
    public function editUser(int $id)
    {
        try {
            $user = $this->userService->find($id);

        } catch (ModelNotFoundException $e) {
            $user = new User();
        }

        if (config('app.env') === 'testing') {
            return $user;
        }

        return view('admin.users.form', compact('user'));

    }

    /**
     * Show user details
     *
     * @param integer $id
     * 
     * @return \Illuminate\View\VIew
     */
    public function showUserDetails(int $id)
    {
        try {
            $user = $this->userService->find($id);
            return view('admin.users.details', compact('user'));
        } catch (ModelNotFoundException $e) {
            abort(404);
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
        
        return view('admin.users.trash', compact('users'));
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
            $user = $this->userService->store($request->toArray());

            $this->shouldUpdatePhoto($user, $request);
            
            return redirect()->route('users.index');

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
            $user = $this->userService->update($request->id, $request->toArray());

            $this->shouldUpdatePhoto($user, $request);

            return redirect()->route('users.index');

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Oops! Something went wrong!',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update photo
     *
     * @param \App\Models\User $user
     * @param \Illuminate\Http\Request $request
     * 
     * @return void
     */
    private function shouldUpdatePhoto(User $user, $request)
    {
        if ($request->has('photo')) {
            $path = $this->userService->upload($request->photo);
            $user->photo = $path;
            $user->save();
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
            'photo' => 'required|image',
        ]);

        try {

            $path = $this->userService->upload($request->photo);
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
