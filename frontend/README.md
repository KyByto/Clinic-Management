# Clinic Booking System - Frontend


## Project Overview

This frontend application serves as the client interface for our Clinic Booking System, providing:

- **Public offer browsing**: Users can view available clinical services
- **Booking functionality**: Interactive form for scheduling appointments
- **Responsive design**: Optimized for both mobile and desktop
- **Secure API integration**: Communication with Laravel backend

## Technologies Used

- **Next.js 14**: React framework with server-side rendering
- **React**: UI component library
- **Tailwind CSS**: Utility-first CSS framework for styling
- **SWR**: React Hooks for data fetching
- **Axios**: HTTP client for API requests
- **React Hook Form**: Form validation
- **Context API**: State management

## Getting Started

### Prerequisites

- Node.js 16.8.0 or later
- npm or yarn package manager
- Backend API running (Laravel application)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/KyByto/Clinic-Management/
   cd clinic-management/frontend
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Configure environment variables:
   - Create a `.env.local` file in the root directory
   - Add the following variables:
   ```
   NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
   NEXT_PUBLIC_ASSET_URL=http://localhost:8000
   ```

4. Run the development server:
   ```bash
   npm run dev
   ```

5. Open [http://localhost:3000](http://localhost:3000) with your browser to see the result.

## Application Structure

- `app/` - Next.js App Router pages and layouts
- `components/` - Reusable UI components
- `hooks/` - Custom React hooks
- `services/` - API service functions
- `context/` - React Context providers
- `styles/` - Global CSS and Tailwind configuration
- `public/` - Static assets

## Key Features

- **Offer Browsing**: Interactive catalog of clinical services
- **Search & Filter**: Find services by category, price, and availability
- **Booking Flow**: Multi-step form with date/time selection
- **User Authentication**: Login/registration for clients
- **Booking History**: View past and upcoming appointments


