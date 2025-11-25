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
        'bio',
        'location',
        'website',
        'is_verified',
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

        /**
         * Relationship: A user can have many followers
         */
        public function followers()
        {
            return $this->hasMany(Follow::class, 'following_id');
        }

        /**
         * Relationship: A user can follow many users
         */
        public function following()
        {
            return $this->hasMany(Follow::class, 'follower_id');
        }

        /**
         * Check if this user is following another user
         */
        public function isFollowing(User $user)
        {
            return $this->following()->where('following_id', $user->id)->exists();
        }

        /**
         * Check if this user is followed by another user
         */
        public function isFollowedBy(User $user)
        {
            return $this->followers()->where('follower_id', $user->id)->exists();
        }

        /**
         * Check if users are mutual followers
         */
        public function isMutualWith(User $user)
        {
            return $this->isFollowing($user) && $this->isFollowedBy($user);
        }

        /**
         * Get mutual connections
         */
        public function mutuals()
        {
            return User::whereIn('id', function ($query) {
                $query->select('follower_id')
                    ->from('follows')
                    ->where('following_id', $this->id);
            })
            ->whereIn('id', function ($query) {
                $query->select('following_id')
                    ->from('follows')
                    ->where('follower_id', $this->id);
            });
        }

        /**
         * Relationship: A user can have many notifications
         */
        public function notifications()
        {
            return $this->hasMany(Notification::class);
        }

        /**
         * Get unread notifications count
         */
        public function getUnreadNotificationsCount()
        {
            return $this->notifications()->where('read', false)->count();
        }

        /**
         * Relationship: A user can have many stories
         */
        public function stories()
        {
            return $this->hasMany(Story::class);
        }

        /**
         * Relationship: A user can have a profile picture
         */
        public function profilePicture()
        {
            return $this->hasOne(ProfilePicture::class);
        }

        /**
         * Get active stories (not expired)
         */
        public function getActiveStories()
        {
            return $this->stories()
                ->where('expires_at', '>', now())
                ->latest()
                ->get();
        }

        /**
         * Relationship: A user can have many bookmarks
         */
        public function bookmarks()
        {
            return $this->hasMany(Bookmark::class);
        }

        /**
         * Relationship: A user can have many retweets
         */
        public function retweets()
        {
            return $this->hasMany(Retweet::class);
        }
}

