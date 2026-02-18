# DreamWeaver: A Gamified Dream Journaling and Analysis Platform

## TL;DR

DreamWeaver is an AI-powered, gamified dream journaling platform that improves user retention and self-reflection by combining immediate AI feedback, creative interaction, and challenge-based engagement. A 14-day study with 147 users showed more than double the retention of traditional dream apps and strong engagement-wellbeing associations.

## Overview

DreamWeaver is a web-based gamified dream journaling and analysis platform designed to address the adherence problem in therapeutic self-reflection. Traditional dream journaling applications suffer from critically poor retention rates (31% at 7 days), primarily due to lack of immediate feedback and sustained motivation. DreamWeaver integrates AI-powered interpretation with 16 interactive entertainment features to transform passive dream logging into an engaging, sustained practice.

This platform was developed as part of a research study demonstrating that entertainment computing principles can significantly enhance dream journaling engagement while delivering measurable mental wellness outcomes. A 14-day field study with 147 participants achieved 69.4% retention—more than double typical dream app rates—with high-engagement users showing 49-point improvements in wellness scores compared to low-engagement users (p<.001, η²=.85).

## Research Publication

This implementation accompanies the research paper:

**Title:** DreamWeaver: A Gamified Dream Journaling and Analysis Platform for Mental Wellness Through Interactive AI-Powered Features

**Authors:** Adit Mugdha Das, Kazi Saeed Alam  
**Affiliation:** Department of Computer Science and Engineering, Khulna University of Engineering and Technology, Bangladesh  
**Journal:** Entertainment Computing  
**Contact:** das2107118@stud.kuet.ac.bd

## Key Features

DreamWeaver implements 16 integrated features organized across five design pillars:

### Core Journaling & Analysis
- **Dream DNA Analysis:** AI-generated personalized profiles extracting patterns across four gene categories (Emotions, Symbols, Colors, Archetypes) with 3D helix visualization
- **Pattern Analysis Dashboard:** Comprehensive visualization of dream patterns with emotion distribution, frequency trends, and temporal analysis
- **AI-Powered Interpretation:** Four interpretation modes (emotion detection, short interpretation, story generation, long narrative) using Google Gemini API

### Creative Expression
- **Dream Art Generation:** AI-generated imagery using OpenAI DALL-E 2 API based on dream content
- **Interactive Story Mode:** Branching narrative extensions with choose-your-own-adventure prompts
- **Mindmap Visualization:** 2D/3D force-graph visualization of recurring themes with emotion-coded nodes
- **Dream Map Exploration:** Spatial exploration with emotion-based realm unlocking mechanism
- **Avatar Creation:** 17 emotion-based avatar configurations with unique visual representations

### Challenge-Based Progression
- **Riddle Challenges:** Difficulty-adaptive daily puzzles (Easy/Medium/Hard) related to dream symbolism
- **Totem Collection:** Achievement badges serving dual functions as recognition and keys for unlocking Dream Map realms
- **Evolution/Health Score Tracking:** Daily self-reported growth and wellbeing metrics (0-100 scale)

### Wellness Support
- **Psychological Support Resources:** Location-enabled access to nearby healthcare providers (psychiatrists, hospitals, clinics)
- **Guided Audio Meditations:** 5-10 minute meditation sessions for dream reflection
- **Dream Library:** Curated literary and mythological content related to dreams across cultures

### Social & Archival
- **Community Feed:** Optional anonymized dream sharing with social interactions
- **Archive & Export:** Comprehensive saving system with PDF downloads and bulk journal archiving
- **Notification System:** Real-time alerts for social interactions and engagement milestones

## Technical Architecture

### Backend
- **Framework:** Laravel 11 (PHP 8.2)
- **Architecture:** MVC pattern with 19 controllers handling feature-specific logic
- **Database:** MySQL with structured storage for user profiles, dreams, engagement metrics, DNA profiles, avatars, totems, and social data
- **Storage:** AWS S3 for scalable media storage (AI-generated artwork, audio files)
- **Authentication:** Custom dual email validation system (@dream.com for login, Gmail for recovery)
- **Security:** bcrypt password hashing, CSRF protection, AES-256-CBC encryption at rest, HTTPS in transit, GDPR compliant

### Frontend
- **Templating:** Blade with Vue.js components
- **Build Tool:** Vite for asset compilation and hot module replacement
- **Visualization:** Three.js and Vanta.js for 3D animated backgrounds and force-directed graphs
- **Design:** Responsive web design accessible across desktop and mobile browsers

### AI Integration
- **Google Gemini 2.5 Flash API:** Natural language processing for Dream DNA analysis, emotion detection, and interpretation modes
- **OpenAI DALL-E 2 API:** Image generation for Dream Art feature
- **Custom Algorithms:** Graph clustering for Mindmap visualization, totem-emotion mapping system

### Additional Libraries
- **DomPDF:** PDF generation for dream exports
- **Laravel Notifications:** Asynchronous alert system for social interactions

## Installation

> **Note:** This project integrates multiple third-party AI services (Gemini, DALL-E, Google Maps). Running all features locally requires valid API keys.

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 5.7 or higher
- Node.js 16 or higher
- npm or yarn

### Setup Instructions

1. Clone the repository:
```bash
git clone https://github.com/yourusername/dreamweaver.git
cd dreamweaver
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Create environment configuration:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure database and API keys in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dreamweaver
DB_USERNAME=your_username
DB_PASSWORD=your_password

GEMINI_API_KEY=your_gemini_api_key
OPENAI_API_KEY=your_openai_api_key
GOOGLE_MAPS_API_KEY=your_google_maps_api_key

AWS_ACCESS_KEY_ID=your_aws_key
AWS_SECRET_ACCESS_KEY=your_aws_secret
AWS_DEFAULT_REGION=your_region
AWS_BUCKET=your_bucket
```

7. Run database migrations:
```bash
php artisan migrate
```

8. Compile frontend assets:
```bash
npm run build
```

9. Start the development server:
```bash
php artisan serve
```

The application will be accessible at `http://localhost:8000`.

## Configuration

### Required API Keys

- **Google Gemini API:** Obtain from [Google AI Studio](https://aistudio.google.com/apikey)
- **OpenAI API:** Obtain from [OpenAI Platform](https://platform.openai.com/api-keys)
- **Google Maps API:** Obtain from [Google Cloud Console](https://console.cloud.google.com/apis/credentials)
- **AWS S3:** Optional for media storage; configure bucket and credentials

### Email Configuration

The custom authentication system requires configuration of mail settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

## Research Data

The empirical study data supporting the research paper includes:
- Pre-survey responses (N=167)
- Behavioral tracking data (N=147, 14 days)
- Post-survey responses (N=86)

Data files are not included in this repository to protect participant privacy per IRB approval and GDPR compliance.

## Study Results Summary

- **Retention Rate:** 69.4% over 14 days (vs. 31% typical dream app baseline)
- **Wellness Difference:** High-engagement users reported substantially higher wellbeing scores than low-engagement users (49-point difference; see paper for context)
- **Engagement Correlations:** Strong positive associations (r=.88 to .93) between feature usage and wellness outcomes
- **User Satisfaction:** 84.9% expressed intent to continue using the platform
- **Feature Preferences:** Dream DNA (16.3%), Dream Art (14.0%), Riddles (12.8%)
- **Hard Fun Phenomenon:** Strong association between challenge preference and retention (see paper for detailed analysis)

## Ethical Considerations

This research received ethical approval from the Institutional Review Board of Khulna University of Engineering and Technology (KUET). All participants provided informed consent. Data handling follows GDPR standards with encryption, anonymization, and secure storage protocols.

## License

This project is released under a custom academic-use license in conjunction with the Entertainment Computing journal submission. Commercial use, redistribution, or deployment requires author permission. For licensing inquiries, please contact the authors.

## Citation

If you use this platform or reference this work, please cite:

```
Das, A. M., & Alam, K. S. (2026). DreamWeaver: A Gamified Dream Journaling 
and Analysis Platform for Mental Wellness Through Interactive AI-Powered Features. 
Entertainment Computing.
```

## Acknowledgments

This research was conducted at the Department of Computer Science and Engineering, Khulna University of Engineering and Technology, Bangladesh. We thank all study participants for their engagement and feedback during the 14-day field study.

## Contact

For questions, collaboration inquiries, or technical support:

**Adit Mugdha Das**  
Department of Computer Science and Engineering  
Khulna University of Engineering and Technology  
Email: das2107118@stud.kuet.ac.bd

---

**Note:** This implementation represents the platform as deployed during the October 2025 field study. API keys and credentials must be configured independently. The platform requires active internet connection for AI-powered features (Gemini, DALL-E 2, Google Maps).
