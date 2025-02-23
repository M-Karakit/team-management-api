# Student, Course, and Instructor Management System API

## Introduction

This API system enables the management of students, courses, and instructors in an educational environment. It offers a wide range of functionalities to handle the operations of educational institutions, including assigning instructors to courses, enrolling students in multiple courses, and retrieving detailed information about courses and students.

The system is designed with flexibility, scalability, and security in mind, leveraging Laravel Eloquent ORM for database management, JWT authentication for secure access, and robust error handling.

## Key Features

### 1. **Student Management**
- Add new students to the system.
- Enroll students in multiple courses.
- View a list of students and their enrolled courses.
- Soft delete, restore, and permanently delete student records.
- Relationship management between students and courses via `Many-to-Many`.

### 2. **Course Management**
- Add new courses and assign instructors.
- View students enrolled in a specific course.
- Manage courses with soft deletion, restoration, and permanent deletion.
- Assign and unassign instructors to/from courses.
- View detailed course information, including students and instructors.

### 3. **Instructor Management**
- Add, edit, and manage instructor profiles.
- Assign courses to instructors and view all courses they teach.
- View students learning under a specific instructor via `hasManyThrough`.
- Manage instructors' records (soft delete, restore, permanent delete).
- Relationship management between instructors and courses via `Many-to-Many`.

### 4. **Comprehensive Error Handling**
- Custom error messages for validation failures, incorrect inputs, and authorization issues.
- Consistent and structured API responses with appropriate HTTP status codes.

### 5. **Authentication and Authorization**
- Secure JWT authentication flow (Login, Logout, Token Refresh).
- Role-based access controls for users, ensuring only authorized personnel can modify records.

---

## API Endpoints

### Authentication Endpoints
- **POST /auth/v1/login**  
  Authenticate a user and issue a JWT token for further API requests.

- **POST /auth/v1/logout**  
  Log out the currently authenticated user and invalidate the token.

- **POST /auth/v1/refresh**  
  Refresh the access token to keep the session alive.

- **GET /auth/v1/current**  
  Retrieve the currently authenticated user's details.

### Student Endpoints
- **GET /v1/students**  
  Retrieves a paginated list of all students, optionally filtered by criteria.

- **POST /v1/students**  
  Create a new student record in the system.

- **GET /v1/students/{student}/courses**  
  Retrieve all courses a specific student is enrolled in.

- **POST /v1/students/restore/{id}**  
  Restore a previously soft-deleted student record.

- **GET /v1/trashed/students**  
  Get a list of soft-deleted students.

- **DELETE /v1/students/force-delete/{id}**  
  Permanently delete a student and remove all their associated data.

- **POST /v1/students/assign-student-course/{student}**  
  Enroll a student in a specific course.

- **POST /v1/students/unassign-student-course/{student}**  
  Unroll a student from a specific course.

### Course Endpoints
- **GET /v1/courses**  
  Retrieves a paginated list of all courses along with their instructors and students.

- **POST /v1/courses**  
  Create a new course with details and assign an instructor.

- **GET /v1/courses/{course}/instructors**  
  Retrieve the instructor(s) assigned to a specific course.

- **GET /v1/courses/{course}/students**  
  View all students enrolled in a specific course.

- **POST /v1/courses/restore/{courseId}**  
  Restore a previously soft-deleted course.

- **GET /v1/trashed/courses**  
  View a list of all soft-deleted courses.

- **DELETE /v1/courses/force-delete/{courseId}**  
  Permanently delete a course and all its related records.

- **POST /v1/courses/assign-course-instructor/{course}**  
  Assign an instructor to a course.

- **POST /v1/courses/unassign-course-instructor/{course}**  
  Unassign an instructor from a course.

### Instructor Endpoints
- **GET /v1/instructors**  
  Retrieve a paginated list of all instructors, including their assigned courses and students.

- **POST /v1/instructors**  
  Create a new instructor record in the system.

- **GET /v1/instructors/{instructor}/courses**  
  Retrieve all courses taught by a specific instructor.

- **GET /v1/instructors/{instructor}/students**  
  Retrieve all students studying under a specific instructor across their courses.

- **POST /v1/instructors/restore/{id}**  
  Restore a previously soft-deleted instructor.

- **GET /v1/trashed/instructors**  
  Get a list of soft-deleted instructors.

- **DELETE /v1/instructors/force-delete/{id}**  
  Permanently delete an instructor from the system.

- **POST /v1/assign-instructor-course/{instructor}**  
  Assign a course to an instructor.

- **POST /v1/unassign-instructor-course/{instructor}**  
  Unassign a course from an instructor.

---

## Database Structure

### Students Table
| Column Name  | Data Type | Description                  |
|--------------|-----------|------------------------------|
| `id`         | Integer   | Primary key                  |
| `name`       | String    | Student's full name          |
| `email`      | String    | Unique email for the student |
| `password`   | String    | Hashed password              |

### Courses Table
| Column Name   | Data Type | Description                 |
|---------------|-----------|-----------------------------|
| `id`          | Integer   | Primary key                 |
| `title`       | String    | Course title                |
| `description` | Text      | Detailed course description |
| `start_date`  | DateTime  | When the course starts      |

### Instructors Table
| Column Name  | Data Type | Description                          |
|--------------|-----------|--------------------------------------|
| `id`         | Integer   | Primary key                          |
| `name`       | String    | Instructor's full name               |
| `experience` | Integer   | Number of years of experience        |
| `specialty`  | String    | Field of expertise or specialization |
| `created_at` | Timestamp | Record creation timestamp            |

### Intermediate Tables
#### Course_Student Table
| Column Name  | Data Type | Description                      |
|--------------|-----------|----------------------------------|
| `student_id` | Integer   | Foreign key referencing student  |
| `course_id`  | Integer   | Foreign key referencing course   |

#### Course_Instructor Table
| Column Name     | Data Type | Description                        |
|-----------------|-----------|------------------------------------|
| `instructor_id` | Integer   | Foreign key referencing instructor |
| `course_id`     | Integer   | Foreign key referencing course     |

---

## Relationships
- **Students and Courses**: Many-to-Many relationship via the `Course_Student` pivot table.
- **Courses and Instructors**: Many-to-Many relationship via the `Course_Instructor` pivot table.
- **Instructors and Students**: Indirect `hasManyThrough` relationship via the courses they teach.

---

## Installation and Setup

### Prerequisites
- PHP >= 8.0
- Composer
- Laravel >= 9.x
- MySQL or another database

### Installation Steps
1. Clone the repository:
   ```bash
   git clone https://github.com/Dralve/course-management-system.git

2. **Navigate to the Project Directory**

    ```bash
    cd team-management-api
    ```

3. **Install Dependencies**

    ```bash
    composer install
    ```

4. **Set Up Environment Variables**

   Copy the `.env.example` file to `.env` and configure your database and other environment settings.

    ```bash
    cp .env.example .env
    ```

   Update the `.env` file with your database credentials and other configuration details.


5. **Run Migrations**

    ```bash
    php artisan migrate
    ```

6. **Seed the Database (To Make Admin)**

    ```bash
    php artisan db:seed
    ```

7. **Start the Development Server**

    ```bash
    php artisan serve
    ```

## Error Handling

Customized error messages and responses are provided to ensure clarity and user-friendly feedback.

## Documentation

All code is documented with appropriate comments and DocBlocks. For more details on the codebase, refer to the inline comments.

## Contributing

Contributions are welcome! Please follow the standard pull request process and adhere to the project's coding standards.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

