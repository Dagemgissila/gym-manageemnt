<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use App\Traits\Filterable;
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, Filterable;


    // Fields that can be searched
    protected $searchable = ['first_name', 'last_name', 'email'];

    // Filters that can be applied
    protected $allowedFilters = ['status', 'role'];

    // Default sorting field
    protected $defaultSortField = 'id';

    // Default sorting order
    protected $defaultSortOrder = 'desc';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile_number',
        'password',
        'gender',
        'date_of_birth',
        'address',
        'city',
        'emergency_contact_name',
        'emergency_contact_phone',
        'hire_date',
        'salary',
        'assigned_location'
    ];




    // Relationship for role filter
    public function scopeRole($query, $role)
    {
        $query->whereHas('roles', fn($q) => $q->where('name', $role));
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
