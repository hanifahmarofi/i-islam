# i-Islam: Gamified Islamic Learning System 🌙

> A modern, gamified web application designed to make learning foundational Islamic subjects interactive, engaging, and fun for primary school students.

## 📖 About the Project

Traditional education often relies on passive learning and rote memorization, which can struggle to engage today’s "digital-native" primary students. **i-Islam** bridges the gap between traditional Islamic teaching and modern technology. 

Developed as my Final Year Project for the Bachelor of Computer Science (Information Security & Assurance) at Universiti Sains Islam Malaysia (USIM), this platform transforms subjects like Tawhid, Sirah, and Hadith into interactive adventures. By integrating an Agile development lifecycle, the system was built to iteratively address user engagement through active participation and data-driven tracking.

## ✨ Key Features

* **Gamified "Arcade Zone":** Shifts learning from passive listening to active participation through interactive games including *Word Scramble*, *Hangman*, *Match Pairs*, and *Domino Stack*.
* **AI-Powered Learning:** Integrates Gemini 2.5 Flash to generate infinite, dynamic AI quizzes, ensuring students always have new challenges.
* **Reward System:** Motivates continuous learning through XP accumulation, dynamic leaderboards, and unlockable badges.
* **Data-Driven Teacher Dashboard:** Provides educators with real-time analytics to monitor student performance and identify weak areas for targeted support.
* **Secure Platform:** Built with a security-first mindset, ensuring student data privacy using industry-standard encryption and Bcrypt password hashing.

## 🛠️ Technology Stack

* **Framework:** Laravel (PHP)
* **Frontend:** Tailwind CSS, Blade Templates
* **Database:** MySQL
* **AI Integration:** Google Gemini 2.5 Flash API
* **Version Control:** Git & GitHub

## 🚀 Local Installation & Setup

To run this project locally, ensure you have PHP, Composer, and Node.js installed on your machine.

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/hanifahmarofi/i-islam.git](https://github.com/hanifahmarofi/i-islam.git)
Navigate to the backend directory:

cd i-Islam/i-islam/backend

**Install Dependencies:**

composer install
npm install

**Environment Setup:**
Copy the example environment file and generate a new application key.

cp .env.example .env
php artisan key:generate

# Note: Update your .env file with your local MySQL database credentials and your Gemini API key.

Run Database Migrations:
php artisan migrate

**Build Assets & Start the Server:**
npm run build
php artisan serve

Visit http://localhost:8000 in your browser to view the application.