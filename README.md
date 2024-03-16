# Job Portal API

This repository contains the backend API for a job portal application. The API allows users to perform various actions related to job vacancies, user profiles, and job applications.

## Features

- **User Authentication**: Users can register, login, and logout. Admins have their own authentication for accessing admin-specific endpoints.

- **Job Vacancies**: Admins can create, update, delete, and publish job vacancies. Users can view published job vacancies and apply to them.

- **User Profiles**: Users can create and update their profiles, including uploading their CVs.

- **Job Applications**: Admins can view a list of users who have applied to their job vacancies. Users can apply to job vacancies, and each user can only apply once to each vacancy.

## API Endpoints

The following are the main API endpoints available in this application:

- **User Authentication**:
  - `POST /api/register`: Register a new user.
  - `POST /api/login`: Login as a user.
  - `POST /api/users/logout`: Logout the current user.

- **Job Vacancies**:
  - `GET /api/job-vacancies`: Get a list of all job vacancies.
  - `POST /api/admins/job-vacancies`: Create a new job vacancy (admin only).
  - `POST /api/job-vacancies/{jobVacancy}/apply`: Apply to a job vacancy.

- **User Profiles**:
  - `POST /api/users/profile`: Add data profile of user.

- **Admin Actions**:
  - `GET /api/job-vacancies/{jobVacancy}/applicants`: Get a list of users who have applied to a specific job vacancy (admin only).
  - `GET /api/job-vacancies/{jobVacancy}/applicants/{user}/cv`: Download the CV of a specific user who applied to a specific job vacancy (admin only).
  - `POST /api/admins/login`: Admin login to system.
  - `POST /api/admins/logout`: Admin logout from system.

## Technologies Used

- Laravel: Backend framework for building the API.
- MySQL: Database management system for storing application data.
- Postman: API testing tool used for testing the API endpoints.

## Setup Instructions

1. Clone the repository: `git clone https://github.com/your-username/job-portal-api.git`
2. Install dependencies: `composer install`
3. Create a `.env` file and configure your environment variables.
4. Generate an application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Start the development server: `php artisan serve`

## Future Improvement Plan

Here are some areas for potential improvement in future versions of the Job Portal API:

- Enhanced error handling and validation.
- Completing CRUD features of each features exist.
- Adding support for pagination to efficiently handle large datasets.
- Improving process flow of job vacancy process.
- Integrating with external services such as email notifications for job application status updates.

Contributions and suggestions for improvements are welcome!
