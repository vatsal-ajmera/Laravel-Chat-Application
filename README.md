# WebSocket Chat Application

This Laravel-based WebSocket chat application supports both private and group chats. It is designed to deliver real-time communication with seamless performance.

## Features
- **Private Chat:** One-on-one conversations with real-time updates.
- **Group Chat:** Collaborate in groups with multiple participants.
- **Real-Time Messaging:** Built using WebSocket technology for instant updates.
- **Secure Communication:** Implemented security measures to protect user data.
- **User-Friendly Interface:** Intuitive design for a better user experience.

## Technology Stack
- **Backend:** Laravel, WebSocket (Pusher or Laravel Echo Server)
- **Frontend:** Blade Templates / Vue.js / React.js (optional)
- **Database:** MySQL
- **Real-Time Library:** Laravel Echo, Pusher

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/vatsal-ajmera/Laravel-Chat-Application.git
   ```

2. **Navigate to the project directory:**
   ```bash
   cd your-repo-name
   ```

3. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

4. **Configure `.env`:**
   - Set up database connection.
   - Add WebSocket configurations (Pusher or Echo).

5. **Run migrations:**
   ```bash
   php artisan migrate
   ```

6. **Start the development server:**
   ```bash
   php artisan serve
   npm run dev
   ```

7. **Start WebSocket server (if applicable):**
   ```bash
   php artisan websocket:serve
   ```

## Usage
- Register and log in to the application.
- Start private chats with other users or join/create group chats.
- Experience real-time communication with seamless performance.

## Contributing
Contributions are always welcome! If you encounter issues or have ideas for improvements, feel free to submit an issue or a pull request.

## License
This project is licensed under the [MIT License](LICENSE).
