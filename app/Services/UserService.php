<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\UserServiceInterface;

class UserService implements UserServiceInterface
{


    /** @inheritDoc */
    public function hash(string $key)
    {
        return bcrypt($key);
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
        $path = Storage::put('/images', $file);
        
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

        if (isset($attributes['password']) && $attributes['password']) {
            $attributes['password'] = $this->hash($attributes['password']);
        }

        return $attributes;
    }
}