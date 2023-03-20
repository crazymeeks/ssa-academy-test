<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Services\UserServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;


class UserService implements UserServiceInterface
{

    /** @inheritDoc */
    public function hash(string $key)
    {
        return bcrypt($key);
    }


    /**
     * Return the paginated list of users
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list()
    {
        $collection = collect(User::all());
        
        return $this->getPaginatedResults($collection);

    }

    /**
     * Get paginated results
     * 
     * @param \Illuminate\Support\Collection $collection
     * 
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function getPaginatedResults(Collection $collection)
    {
        $request = request();

        $page = $request->has('page') ? (int) $request->page : 1;
        $perPage = 2;
        $paginate = new LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
        );

        return $paginate;
    }

    /**
     * Get paginated list of users in trashed
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listTrashed()
    {
        $collection = User::withTrashed()->get();

        return $this->getPaginatedResults($collection);
    }

    /**
     * Find user
     *
     * @param integer $id
     * 
     * @return \App\Models\User
     */
    public function find(int $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Store data in the database
     *
     * @param array $attributes
     * 
     * @return \App\Models\User
     */
    public function store(array $attributes)
    {
        $attributes = $this->hashPassword($attributes);

        return DB::transaction(function() use ($attributes) {
            return User::create($attributes);
        });
    }

    /**
     * Update data in the database
     *
     * @param integer $id
     * @param array $attributes
     * 
     * @return \App\Models\User
     */
    public function update(int $id, array $attributes)
    {
        $user = User::find($id);

        $attributes = $this->hashPassword($attributes);

        return DB::transaction(function() use ($attributes, $user) {
            $user->fill($attributes)
                 ->save();

            return $user;
        });
    }

    /**
     * Delete existing user(soft delete)
     *
     * @param int $id
     * 
     * @return bool
     */
    public function destroy(int $id)
    {
        return User::find($id)->delete();
    }

    /**
     * Permanently delete soft deleted model
     * 
     * @param int $id
     *
     * @return true
     */
    public function delete(int $id)
    {
        return ($this->findInTrash($id)->forceDelete()) === 1;
    }

    /**
     * Restore delete user
     *
     * @param int $id
     * 
     * @return bool
     */
    public function restore(int $id)
    {
        return ($this->findInTrash($id)->restore()) === 1;
    }

    /**
     * Upload photo
     * 
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return string The uploaded file path
     */
    public function upload(UploadedFile $file)
    {
        $path = Storage::put('/public/images', $file);
        $path = str_replace('public', 'storage', $path);
        return $path;
    }


    /**
     * Get soft deleted user
     *
     * @param integer $id
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function findInTrash(int $id)
    {
        return User::withTrashed()->whereId($id);
    }

    /**
     * Hash password from attributes
     *
     * @param array $attributes
     * 
     * @return array
     */
    protected function hashPassword(array $attributes)
    {
        if (isset($attributes['id'])) {
            if (isset($attributes['password']) || is_null($attributes['password'])) {
                unset($attributes['password']);
                return $attributes;
            }
        }

        if (isset($attributes['password']) && !is_null($attributes['password'])) {
            $attributes['password'] = $this->hash($attributes['password']);
        }

        return $attributes;
    }
}