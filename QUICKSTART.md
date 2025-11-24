# 🚀 Chirper - Quick Start Guide

Welcome to **Chirper**, a full-featured Twitter-like social media platform built with Laravel 11!

## ⚡ Quick Start (5 minutes)

### 1. Start the Development Server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

### 2. Create Your First Account

- Click **Register** in the top right
- Enter name, email, and password
- Click **Register**

### 3. Create Your First Tweet

- In the feed, type your message (max 280 characters)
- Click **Tweet 🚀** to post
- Your tweet appears instantly!

### 4. Upload Media

- When creating a tweet, click **Add image or video**
- Select an image or video file (jpg, png, gif, mp4, etc.)
- Preview appears before posting
- Tweet publishes with media attached

### 5. Interact with Tweets

- Click ❤️ to like tweets
- See like counts update in real-time
- View detailed stats on profiles

### 6. Follow Other Users

- Click on any user's avatar or name
- On their profile, click **Follow**
- See updated follower counts
- Following status shown as "Following"

### 7. Search for Users & Tweets

- Use the search bar at the top (desktop) or mobile menu
- Type at least 2 characters
- See results for users and tweets
- Follow or like directly from search results

### 8. Send Private Messages

- Click **Messages** in navigation
- Click a user to start chatting
- Type your message and send
- See unread badges for new messages

### 9. Customize Your Avatar

- Click your profile in the dropdown (top right)
- Select **Profile**
- Scroll down to avatar colors
- Click any color to change your avatar

### 10. Edit or Delete Your Tweets

- Hover over your own tweet
- Click the **⋯** menu
- Choose **Edit** or **Delete**

---

## 📋 Features at a Glance

| Feature | How to Use |
|---------|-----------|
| **Tweet** | Write in the form, click Tweet |
| **Like** | Click the heart icon on any tweet |
| **Follow** | Click Follow on a user profile |
| **Message** | Go to Messages, click a user |
| **Search** | Use the search bar at the top |
| **Upload Media** | Click "Add image or video" in tweet form |
| **Edit Tweet** | Hover tweet, click ⋯ menu, Edit |
| **Change Avatar** | Go to profile, select new color |

---

## 🎨 User Interface

### Main Navigation
- **Chirper** logo (left) - Go to feed
- **Search bar** (center) - Find users/tweets
- **Messages** link (right) - View messages
- **Profile dropdown** (right) - Your profile & logout

### Feed
- Write tweets in the form at top
- See all tweets below in chronological order
- Each tweet shows:
  - User name and avatar
  - Post time (e.g., "2 hours ago")
  - Tweet content
  - Attached images/videos
  - Like count and button

### Profile
- See user's avatar and stats
- View number of tweets, followers, following
- See all their tweets with media
- Option to follow/message (if not your profile)

### Messages
- List of all conversations
- Unread badge shows new messages
- Click to open conversation
- See message history
- Type and send new messages

---

## 🛠️ Troubleshooting

### I see a blank page
- Make sure server is running: `php artisan serve`
- Clear cache: `php artisan cache:clear`
- Check browser console for errors (F12)

### Media not uploading
- Check file size (max 50MB)
- Try different file format (jpg, mp4, etc.)
- Check server storage permissions

### I can't follow someone
- Make sure you're logged in
- You can't follow yourself
- Refresh page if it doesn't update

### Messages not sending
- Check you're logged in
- Make sure recipient exists
- Refresh to see new messages

### Login not working
- Check email is correct
- Password is case-sensitive
- Create new account if forgotten

---

## 💡 Tips & Tricks

✨ **Pro Tips:**
- Use emojis in tweets: 🎉, ❤️, 🚀
- Follow topics by finding related users
- Search for #hashtags by including them in search
- Check conversations regularly for new messages
- Use avatar colors to express your personality
- Post videos for engaging content

🎯 **Best Practices:**
- Keep tweets concise and interesting
- Use images/videos for higher engagement
- Follow people with similar interests
- Respond to messages promptly
- Update profile regularly

---

## 📱 Mobile Experience

The app works perfectly on phones! 
- Navigation menu adapts for mobile
- Touch-friendly buttons
- Search bar in mobile menu
- Responsive tweet cards
- Easy media upload

---

## 🔐 Safety & Security

- Your password is securely hashed
- All data is validated server-side
- CSRF protection on all forms
- Messages are private
- Only you can edit/delete your tweets

---

## 📞 Need Help?

### Common Issues

**Lost password?**
- On login page, click "Forgot your password?"
- Enter email and follow instructions

**Want to delete account?**
- Go to Profile
- Scroll down, click "Delete Account"
- Confirm deletion

**Report issue?**
- GitHub: [Report Issue](https://github.com/kiertacus/tacus-exam/issues)

---

## 🎓 Learning Resources

- Read **FEATURES.md** for complete documentation
- Check **IMPLEMENTATION_SUMMARY.md** for technical details
- View code at: https://github.com/kiertacus/tacus-exam

---

## 🎉 Ready to Start?

1. Go to **http://localhost:8000**
2. Click **Register**
3. Create your account
4. **Start Tweeting!**

Enjoy Chirper! 🚀

---

**Happy social networking!**
