<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
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
         * Relationship: A user can have many Tweets (Chirps)
         */
        public function tweets()
        {
            return $this->hasMany(Tweet::class);
        }

        /**
         * Relationship: A user can have many Likes
         */
        public function likes()
        {
            return $this->hasMany(Like::class);
        }

        /**
         * Get avatar gradient colors
         */
        public function getAvatarColors()
        {
            $avatars = [
                'blue' => 'from-blue-400 to-blue-600',
                'purple' => 'from-purple-400 to-purple-600',
                'pink' => 'from-pink-400 to-pink-600',
                'cyan' => 'from-cyan-400 to-cyan-600',
                'green' => 'from-green-400 to-green-600',
                'orange' => 'from-orange-400 to-orange-600',
                'red' => 'from-red-400 to-red-600',
                'indigo' => 'from-indigo-400 to-indigo-600',
            ];
            
            return $avatars[$this->avatar] ?? $avatars['blue'];
        }

        /**
         * Relationship: A user can send many Messages
         */
        public function sentMessages()
        {
            return $this->hasMany(Message::class, 'sender_id');
        }

        /**
         * Relationship: A user can receive many Messages
         */
        public function receivedMessages()
        {
            return $this->hasMany(Message::class, 'recipient_id');
        }
}
