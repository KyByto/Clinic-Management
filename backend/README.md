🧪 Full-Stack Technical Assessment – Senior Laravel & React Developer
Duration: 5 Days (05/06/2025 - 23:59 PM)
Goal: Evaluate practical skills in Laravel, Filament, React, API design, security, and architectural thinking.

🎯 Objective
Build a simplified Clinic Dashboard module for a startup SaaS platform.
The system should include two main parts:
Clinic Dashboard (Filament) – for clinics to manage their offers.
Client Interface (React) – for users to browse and book offers.

✅ Assessment Requirements
1. Backend – Laravel
Use Laravel 10+ and FilamentPHP for the admin/dashboard UI.
Implement authentication (basic email/password is fine).
Core models: Clinic, Offer, Client, Booking.
Features to implement:
Clinics can log in and manage their offers via Filament (CRUD).
API endpoints to:
List all offers
Create a booking (from client-side)
Simulate a payment integration (e.g., Stripe test environment or just payment intent logic).
Simulate messaging (e.g., Twilio, or Laravel Notification for SMS/email).




2. Frontend – React
A minimal responsive app where:
Clients can browse offers (publicly)
Clients can submit a booking request for an offer
Focus on clean, basic UX (no need for polished UI, but clear functionality).

3. Security
Protect APIs (authentication + rate limiting + validation).
Sanitize user inputs and demonstrate awareness of common threats (XSS, CSRF, etc.).

4. Architecture Proposal (No Coding Required)
Submit a simple AWS architecture diagram (PDF or diagram tool export).
Must include:
MVP phase setup (minimal, low-cost)
Scaling phase suggestions (high traffic, data protection, backups, etc.)
Highlight services like EC2, RDS, S3, CloudFront, etc., and justify choices briefly.

📦 Deliverables
GitHub repo with clear commit history and structured folders:
/backend (Laravel + Filament)
/frontend (React client interface)
Architecture diagram for AWS infrastructure (MVP + scale phases)
Hosted demo (e.g., Vercel + Render/AWS/Heroku)

🔍 Evaluation Criteria
Code quality and maintainability
Logical structuring of backend and API
Filament integration and usability
React implementation (simplicity, clarity)
Communication of architectural thinking
