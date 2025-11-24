# 🚀 Chirper - Complete Instagram Clone

A full-featured Instagram-like social media platform built with **Laravel 11**, **Blade**, **Tailwind CSS**, and **SQLite**. 

## ✨ Features Implemented

### 📱 Core Social Media Features
- ✅ **User Authentication** - Sign up, login, logout with Laravel Breeze
- ✅ **User Profiles** - Avatar gradients (8 colors), profile view, edit profile
- ✅ **Tweet Creation** - Post content up to 280 characters with multiple media
- ✅ **Media Upload** - Support for images (JPG, PNG, GIF) and videos (MP4, MOV, AVI, WebM)
- ✅ **Like System** - Like/unlike posts with real-time count updates
- ✅ **Comment System** - Add comments to posts, delete your own comments
- ✅ **Follow System** - Follow/unfollow users, view follower counts
- ✅ **Mutual Connections** - Track mutual followers
- ✅ **Direct Messaging** - Send messages to other users
- ✅ **User Search** - Search for users and tweets by keywords
- ✅ **Hashtag Support** - Automatically detect and link hashtags (#) in posts
- ✅ **Hashtag Pages** - View all posts for a specific hashtag with post count
- ✅ **Activity Notifications** - Get notified for likes, comments, and follows
- ✅ **Notification Bell** - Badge showing unread notification count

### 🎨 Instagram-Style UI/UX
- ✅ **Left Sidebar Navigation** - Logo, menu items (Feed, Explore, Messages, Profile, Notifications)
- ✅ **Stories Section** - Story circle carousel at the top of feed
- ✅ **Post Composer** - Clean form to create posts with media upload preview
- ✅ **Post Cards** - Instagram-style post display with header, media, actions, like count, and caption
- ✅ **Suggested Users Sidebar** - Right sidebar showing suggested users to follow (xl+ screens)
- ✅ **Responsive Design** - Mobile-friendly sidebar navigation with collapsible menu
- ✅ **Notification Page** - Activity feed showing all notifications
- ✅ **Hashtag Pages** - Clean hashtag view showing posts with that hashtag

### 🗄️ Database Schema
```
users (id, name, email, password, avatar, timestamps)
tweets (id, user_id, content, timestamps)
media (id, tweet_id, path, type, timestamps)
likes (id, tweet_id, user_id, timestamps)
comments (id, tweet_id, user_id, content, timestamps)
follows (id, follower_id, following_id, timestamps)
messages (id, sender_id, recipient_id, content, timestamps)
notifications (id, user_id, from_user_id, type, tweet_id, message, read, timestamps)
hashtags (id, name, description, count, timestamps)
hashtag_tweet (id, tweet_id, hashtag_id, timestamps)
```

## 📋 Project Structure

```
chirper-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── TweetController.php         (CRUD for tweets + hashtag parsing)
│   │   │   ├── LikeController.php          (Like/unlike + notifications)
│   │   │   ├── CommentController.php       (Comments + notifications)
│   │   │   ├── FollowController.php        (Follow/unfollow + notifications)
│   │   │   ├── UserController.php          (User profiles)
│   │   │   ├── MessageController.php       (Direct messaging)
│   │   │   ├── SearchController.php        (User & tweet search)
│   │   │   ├── MediaController.php         (Media handling)
│   │   │   ├── NotificationController.php  (Notifications view)
│   │   │   └── HashtagController.php       (Hashtag pages)
│   │   └── Requests/ (validation)
│   ├── Models/
│   │   ├── User.php            (auth, relationships)
│   │   ├── Tweet.php           (posts)
│   │   ├── Like.php            (likes)
│   │   ├── Comment.php         (comments)
│   │   ├── Follow.php          (follow relationships)
│   │   ├── Message.php         (messages)
│   │   ├── Media.php           (media files)
│   │   ├── Notification.php    (activity notifications)
│   │   └── Hashtag.php         (hashtag tracking)
│   ├── Policies/
│   │   ├── TweetPolicy.php
│   │   └── CommentPolicy.php
│   └── Providers/ (service providers)
├── database/
│   ├── migrations/      (all 11 table migrations)
│   ├── factories/       (seeding)
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── tweets/
│   │   │   ├── index.blade.php      (MAIN FEED - Instagram layout)
│   │   │   └── edit.blade.php
│   │   ├── notifications/
│   │   │   └── index.blade.php      (Notifications page)
│   │   ├── hashtags/
│   │   │   └── show.blade.php       (Hashtag posts view)
│   │   ├── profile/
│   │   ├── search/
│   │   ├── auth/
│   │   ├── components/
│   │   └── layouts/
│   ├── css/
│   │   └── app.css        (Tailwind)
│   └── js/
│       └── app.js         (media preview, char counter)
├── routes/
│   ├── web.php           (all 20+ application routes)
│   ├── auth.php          (authentication routes)
│   └── console.php
├── storage/
│   └── app/
│       ├── public/
│       │   └── tweets/   (media uploads)
│       └── framework/    (cache, sessions)
└── config/               (Laravel config)
```

## 🚀 Quick Start

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & npm (for Vite)
- SQLite (included)

### Installation

```bash
# 1. Clone the repository
cd c:\wamp64\www\chirper-app

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Create .env file
copy .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Run migrations
php artisan migrate

# 7. Build frontend assets
npm run build

# 8. Create storage symlink
php artisan storage:link

# 9. Start the development server
php artisan serve
```

Visit **http://localhost:8000** in your browser.

## 🎯 Key Features Implementation Details

### 📝 Post Creation with Media
```php
// Form accepts multiple media files
// Stores in storage/app/public/tweets/
// Automatically creates Media records
// User can upload images and videos
```

### ❤️ Like System with Notifications
```php
// Toggle like/unlike
// Creates Notification when someone likes your post
// Shows real-time like count
// Notification badge in sidebar
```

### 💬 Comments with Notifications
```php
// Add comments to posts
// Create notifications for post author
// Delete own comments
// Show top 5 recent comments per post
```

### 🔔 Activity Notifications
- **Like notification**: When someone likes your post
- **Comment notification**: When someone comments on your post
- **Follow notification**: When someone follows you
- **Unread count badge**: Shows in sidebar
- **Notifications page**: View all activity chronologically

### #️⃣ Hashtag System
```php
// Automatic parsing of #hashtags in content
// Case-insensitive hashtag handling
// Each hashtag tracks post count
// Clickable hashtag links in posts
// Dedicated hashtag page showing all posts
```

### 👥 Follow System
- Follow/unfollow users
- View follower/following counts
- Track mutual connections
- Suggested users sidebar (random users)
- Follow buttons in suggested users section

### 🔍 Search
- Search users by name
- Search tweets by content
- Filter results in real-time

### 💌 Direct Messaging
- Send private messages to users
- View conversation history
- Real-time message display

## 🎨 UI/UX Architecture

### Instagram-Style Layout
```
┌─────────────────────────────────────────────────────────┐
│ Left Sidebar (lg+)    │  Main Feed (max-w-2xl)  │ Right Sidebar (xl+)
│ ────────────────────  │  ─────────────────────  │ ──────────────────
│ Logo                  │  Story Carousel         │ Search Bar
│ Nav (Feed/Explore)    │  Post Composer          │ Suggested Users
│ Nav (Messages)        │  Feed Posts             │ Footer Links
│ Nav (Notifications)   │  (scrollable)           │
│ Nav (Profile)         │                         │
│ Logout                │                         │
└─────────────────────────────────────────────────────────┘
```

### Responsive Breakpoints
- **Mobile**: Sidebar hidden, full-width feed
- **lg (1024px)**: Left sidebar visible
- **xl (1280px)**: Left sidebar + right sidebar visible

## 🔐 Security Features
- ✅ CSRF token protection on all forms
- ✅ Authorization policies for post/comment editing/deletion
- ✅ Authentication middleware on protected routes
- ✅ User validation on all requests
- ✅ File type validation on media uploads
- ✅ File size limits (50MB max per file)

## 🗂️ Routes Overview

```
GET   /                              (Feed)
GET   /search                        (Search)
GET   /hashtag/{tag}                 (Hashtag view)
POST  /tweets                        (Create tweet)
POST  /tweets/{tweet}/like           (Like toggle)
POST  /tweets/{tweet}/comments       (Add comment)
DELETE /comments/{comment}            (Delete comment)
POST  /users/{user}/follow           (Follow)
DELETE /users/{user}/follow          (Unfollow)
GET   /notifications                 (View notifications)
GET   /messages                       (View conversations)
GET   /messages/{user}               (Chat with user)
POST  /messages/{user}               (Send message)
GET   /profile/{user}                (View profile)
GET   /profile                       (Edit profile)
```

## 📊 Database Relationships

```
User → has many Tweets
User → has many Likes
User → has many Comments
User → has many Messages (sent)
User → has many Messages (received)
User → has many Followers (follows)
User → has many Following (followers)
User → has many Notifications

Tweet → belongs to User
Tweet → has many Likes
Tweet → has many Comments
Tweet → has many Media
Tweet → has many Hashtags (many-to-many)

Hashtag → has many Tweets (many-to-many)

Comment → belongs to Tweet
Comment → belongs to User

Notification → belongs to User (receiver)
Notification → belongs to User (from_user)
Notification → belongs to Tweet (optional)
```

## 🛠️ Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Blade + Tailwind CSS
- **Database**: SQLite
- **Authentication**: Laravel Breeze
- **Build Tool**: Vite
- **Storage**: Local filesystem
- **Session**: File-based
- **ORM**: Eloquent

## 📈 Recent Improvements

### Latest Commits
1. ✅ **Fix: Add Instagram-style post composer** - Form now always visible for authenticated users
2. ✅ **Feature: Add comments system** - Instagram-style commenting on posts
3. ✅ **Feature: Add notifications system** - Activity feed with like/comment/follow notifications
4. ✅ **Feature: Add hashtag system** - Auto-detect, link, and track hashtags

### Fixed Issues
- ✅ Post form was hidden when tweets list was empty
- ✅ Added comments to posts for interaction
- ✅ Created notification system for activity tracking
- ✅ Implemented hashtag parsing and linking

## 🎉 Usage Examples

### Create a Post
1. Navigate to home feed
2. Fill in the post composer
3. Upload images/videos (optional)
4. Add hashtags by using # symbol
5. Click "Post"
6. New post appears at top of feed with notifications for likes/comments

### View Hashtag
1. Click on any #hashtag in a post
2. See all posts with that hashtag
3. Post count shown in header

### Get Notifications
1. Notifications bell in sidebar shows unread count
2. Click to view all activity
3. See who liked, commented, or followed you

### Search
1. Click on Explore
2. Type username or keywords
3. View matching users and tweets
4. Click to view profiles or posts

## 🚦 Status: PRODUCTION READY

All core Instagram features are implemented and working:
- ✅ Authentication & Profiles
- ✅ Posts with Media
- ✅ Likes & Comments
- ✅ Follow System
- ✅ Notifications
- ✅ Hashtags
- ✅ Search
- ✅ Messaging
- ✅ Instagram UI/UX

## 📝 Next Enhancement Ideas

- [ ] Stories (24-hour posts)
- [ ] Reels (short video section)
- [ ] Double-tap to like animation
- [ ] Location tagging
- [ ] Save posts to collections
- [ ] Explore/Discovery page with trending
- [ ] User suggestions based on follows
- [ ] Post scheduling
- [ ] Advanced analytics for creators
- [ ] Video processing (thumbnail generation)

## 📧 Contact & Support

For issues or feature requests, please check the GitHub repository or contact the development team.

---

**Built with ❤️ using Laravel + Tailwind CSS**
