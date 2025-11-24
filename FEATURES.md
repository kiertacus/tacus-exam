# Chirper - A Twitter-like Social Media Platform

A feature-rich social media application built with **Laravel 11**, **Tailwind CSS**, and **SQLite**, designed to provide a real social media experience with modern UI/UX.

## ✨ Features

### Core Functionality
- **Tweet Management**: Create, edit, and delete tweets (max 280 characters)
- **Media Uploads**: Upload images and videos with tweets
- **Like System**: Like/unlike tweets with real-time counts
- **User Profiles**: View user profiles with tweet history and follower stats
- **Avatar Customization**: Choose from 8 different avatar colors

### Social Features
- **Follow System**: Follow and unfollow other users to become mutuals
- **Follower/Following Stats**: View follower and following counts on profiles
- **Direct Messaging**: Send private messages to other users
- **Conversation View**: See all conversations with unread badges
- **Last Message Preview**: Quick view of conversation history

### Discovery
- **Search Functionality**: Search for users and tweets by keywords
- **Global Feed**: Browse all tweets from users you follow
- **User Profiles**: Discover other users and their tweets
- **Search Results**: See results for both users and posts

### User Experience
- **Authentication**: Secure user registration and login with Laravel Breeze
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile
- **Modern UI**: Beautiful gradient designs with smooth animations
- **Dark Mode**: Light blue and modern design aesthetic
- **Real-time Updates**: AJAX-based interactions for smooth UX

## 🛠️ Technical Stack

- **Backend**: Laravel 11
- **Database**: SQLite
- **Frontend**: Blade Templates with Tailwind CSS
- **Authentication**: Laravel Breeze
- **File Storage**: Local storage with public symlink
- **Development**: PHP 8.2+, Composer, npm

## 📦 Database Models

### Users
- Profiles with email authentication
- Avatar color preferences
- Relationships with tweets, messages, and follows

### Tweets
- Content (max 280 characters)
- Created and updated timestamps
- User ownership
- Media attachments
- Like counts

### Likes
- Links users to tweets they've liked
- Prevents duplicate likes

### Media
- Stores media file paths (images/videos)
- File type classification
- Associated with tweets

### Messages
- Private messages between users
- Sender and recipient relationships
- Read status tracking
- Timestamp tracking

### Follows
- Stores follower/following relationships
- Unique constraint to prevent duplicate follows
- User bidirectional relationships

## 🚀 Getting Started

### Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/kiertacus/tacus-exam.git
   cd tacus-exam
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup Database**
   ```bash
   php artisan migrate
   ```

5. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

6. **Build Assets**
   ```bash
   npm run build
   # or for development with watch
   npm run dev
   ```

7. **Start Server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` in your browser.

## 📁 Project Structure

```
resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php          # Main layout
│   │   └── navigation.blade.php   # Navigation bar with search
│   ├── tweets/
│   │   ├── index.blade.php        # Feed with tweet creation
│   │   └── edit.blade.php         # Edit tweet form
│   ├── profile/
│   │   └── show.blade.php         # User profile with tweets
│   ├── messages/
│   │   ├── conversations.blade.php # Message list
│   │   └── show.blade.php         # Chat interface
│   ├── search/
│   │   └── results.blade.php      # Search results
│   └── components/
│       └── create-tweet-form.blade.php # Reusable tweet form
app/
├── Models/
│   ├── User.php
│   ├── Tweet.php
│   ├── Like.php
│   ├── Message.php
│   ├── Follow.php
│   └── Media.php
├── Http/Controllers/
│   ├── TweetController.php
│   ├── LikeController.php
│   ├── UserController.php
│   ├── MessageController.php
│   ├── FollowController.php
│   ├── SearchController.php
│   └── MediaController.php
└── Policies/
    └── TweetPolicy.php
database/
├── migrations/          # All schema migrations
└── factories/          # User factory for seeding
```

## 🔐 Security Features

- **CSRF Protection**: All forms protected with CSRF tokens
- **Authentication**: Only authenticated users can create/edit/delete content
- **Authorization**: Users can only modify their own content
- **Input Validation**: All inputs validated server-side
- **File Validation**: Media files validated for type and size (max 50MB)

## 🎨 UI Components

### Navigation
- Logo/brand name with gradient
- Search bar (desktop and mobile)
- Feed and Messages links
- User profile dropdown

### Tweet Card
- User avatar with gradient
- Tweet content
- Media attachments (images/videos)
- Like button with count
- Edit/Delete options (own tweets only)
- Timestamps with relative time

### Profile
- Large avatar with gradient
- User statistics (tweets, followers, following)
- Avatar color selector (own profile)
- Follow/Message buttons (other profiles)
- All user tweets with media

### Search
- Search input for users and tweets
- Results organized by user and posts
- Follow/unfollow from search results
- Like functionality on search results

## 📱 Responsive Design

- **Mobile**: Single column layout, optimized touch targets
- **Tablet**: 2-column layout with sidebar
- **Desktop**: Full layout with optimal spacing

## 🎯 Key Routes

```
GET  /                           # Feed (tweets index)
POST /tweets                     # Create tweet
PUT  /tweets/{tweet}             # Update tweet
DELETE /tweets/{tweet}           # Delete tweet
POST /tweets/{tweet}/like        # Like/unlike tweet

GET  /profile/{user}             # View user profile
PATCH /profile                   # Update own profile

GET  /messages                   # Message conversations list
GET  /messages/{user}            # Chat with user
POST /messages/{user}            # Send message

GET  /search                     # Search users and tweets

POST /users/{user}/follow        # Follow user
DELETE /users/{user}/follow      # Unfollow user
```

## 🔄 Data Flow

1. **Creating a Tweet**
   - User writes content and optionally uploads media
   - Form validates content (max 280 chars) and media (max 50MB)
   - Tweet stored in database with user_id
   - Media stored in `storage/app/public/tweets/`
   - Tweet appears immediately in feed

2. **Following a User**
   - Click Follow button on profile
   - Creates entry in `follows` table
   - Follow/Following counts update
   - User's tweets appear in personalized feed

3. **Messaging**
   - Navigate to Messages
   - Click user to start conversation
   - Messages stored with sender_id and recipient_id
   - Unread messages tracked with `read` status

4. **Searching**
   - Type in search bar (min 2 characters)
   - Results show matching users and tweets
   - Can follow/like directly from search results

## 🐛 Troubleshooting

### Tweets not appearing
- Check database migrations ran: `php artisan migrate:status`
- Verify storage link exists: `ls -la public/storage`

### Media not uploading
- Check `storage/app/public/tweets/` directory exists
- Verify file permissions: `chmod -R 755 storage/`
- Check max file size: 50MB limit in MediaController

### Messages not sending
- Verify session driver in `.env`: should be `file` or `database`
- Check CSRF token in forms: `{{ csrf_field() }}`

### Images not displaying
- Run storage link: `php artisan storage:link`
- Check public symlink: `php artisan storage:link`

## 📚 Learning Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Laravel Breeze](https://laravel.com/docs/starter-kits#breeze)

## 📝 License

This project is open source and available under the MIT License.

## 👤 Author

Created by Tacus

## 🙏 Acknowledgments

- Built with Laravel 11
- Designed with Tailwind CSS
- Inspired by Twitter/X and modern social platforms
- Uses Laravel Breeze for authentication scaffolding

---

**Built with ❤️ for real social connection**
