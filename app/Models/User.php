<?php

namespace App\Models;

use App\Models\Detail;
use App\Events\UserSaved;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const TYPE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'suffixname',
        'username',
        'email',
        'password',
        'photo',
        'type',
        'email_verified_at',
        'deleted_at'
    ];

    /**
     * The event map for the model
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get avatar of this model
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function avatar(): Attribute
    {
        
        return Attribute::make(
            get: fn ($avatar) => $this->photo,
        );
    }

    /**
     * Get fullname of this model
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function fullname(): Attribute
    {
        return Attribute::make(
            get: fn() => sprintf("%s %s", $this->firstname, $this->lastname) 
        );
    }

    /**
     * Get middle initial of this model
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function middleinitial(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->middlename ? strtoupper(substr($this->middlename, 0, 1)) : null
        );
    }

    /**
     * Get details of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(Detail::class);
    }

    /**
     * Get gender of a user according to prefix
     *
     * @param string $prefix Mr, Mrs, Ms.
     * 
     * @return string
     */
    public function getGenderAccordingToPrefix(string $prefix)
    {
        return $this->genderFromMap($prefix);
    }

    /**
     * Get mapped gender
     *
     * @param string $prefix
     * 
     * @return string
     */
    private function genderFromMap(string $prefix)
    {

        $prefix = strtolower($prefix);

        $prefixes = [
            'mr' => 'Male',
            'mrs' => 'Female',
            'ms' => 'Female'
        ];

        return $prefixes[$prefix];
    }
}
