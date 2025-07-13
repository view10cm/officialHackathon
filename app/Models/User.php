<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class, 'user_id');
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }
    // A scope to only get active users
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'role',
        'role_name',
        'password',
        'active',
    ];

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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the documents submitted by this user
     */
    public function submittedDocuments()
    {
        return $this->hasMany(SubmittedDocument::class, 'user_id');
    }

    /**
     * Get the documents received by this user
     */
    public function receivedDocuments()
    {
        return $this->hasMany(SubmittedDocument::class, 'received_by');
    }

    /**
     * Get the document reviews done by this user
     */
    public function documentReviews()
    {
        return $this->hasMany(Review::class, 'reviewed_by');
    }
}
