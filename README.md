# Project Planner API

This project is a RESTful backend system built with **Laravel 12** for managing projects, tasks, and teams. It demonstrates modern software development practices, including secure authentication, role-based access control, activity tracking, analytics, and automated testing.


## Key Features

* **Secure Authentication:** Integrated with Laravel Sanctum for token-based API security.
* **Teams & Permissions:** Role-Based Access Control (RBAC) via Laravel Policies. Users can only access projects within their assigned teams.
* **Task Management:** Full CRUD lifecycle with support for task statuses (Todo, Doing, Review, Done), priorities, and due dates.
* **Task Comments:** Real-time collaboration through a commenting system on individual tasks.
* **Automated Activity Log:** Implemented via **Eloquent Observers** to automatically track and log actions (task creation, status changes, comments) into an Activity Feed.
* **Analytics Dashboard:** Dedicated endpoint providing project statistics, completion rates, and overdue task tracking.
* **Data Integrity (Soft Deletes):** Uses Soft Deletes for both Projects and Tasks to prevent accidental data loss.
* **Test Coverage:** Feature tests with PHPUnit.


##  Tech Stack

* **Backend:** Laravel 12 (PHP 8.2+)
* **Database:** SQLite (Default) / MySQL
* **Authentication:** Laravel Sanctum
* **Testing Suite:** PHPUnit