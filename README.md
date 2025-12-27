# Hexagonal Todo Manager (Priority Focus)

## Project Overview
This is a Task Management system built using **Hexagonal Architecture** (Ports and Adapters) in PHP. The main goal is to demonstrate how to decouple business logic from external dependencies (frameworks, databases, etc.).

The system manages tasks with different priority levels, enforcing strict business rules regardless of whether the request comes from a Web API, a CLI tool, or a test suite.

## The Core Business Rule (Domain)
To prevent user burnout and maintain focus, the system enforces the following invariant:
* **"A user cannot have more than 3 tasks marked as 'URGENT' at the same time."**

If a user attempts to add or update a fourth task to "URGENT", the Domain will throw a `PriorityLimitReachedException`.

---

## Use Cases (Application Layer)

### 1. Create a New Task
* **Actor:** User
* **Input:** Title, Description, Priority (LOW, MEDIUM, URGENT)
* **Process:**
    1. Receive task data.
    2. Check if the priority is URGENT.
    3. If URGENT, count existing URGENT tasks via the Repository.
    4. If count >= 3, **Reject** the creation.
    5. If allowed, persist the new Task.

### 2. Update Task Priority
* **Actor:** User
* **Input:** Task ID, New Priority
* **Process:**
    1. Retrieve the existing Task.
    2. If the new priority is URGENT, check the global limit.
    3. Update the task status.
    4. Persist the changes.

### 3. List All Tasks
* **Actor:** User
* **Output:** List of tasks filtered by status or priority.

## Project Structure

The project follows a folder-by-feature (Bounded Context) approach within a Hexagonal Architecture:

```text
src/
└── Todo/
    ├── Domain/                 # --- DOMAIN LAYER (Business Logic) ---
    │   ├── Model/              # Entities and Value Objects
    │   │   ├── Task.php        # Core Entity with business rules
    │   │   └── Priority.php    # Enum (LOW, MEDIUM, URGENT)
    │   ├── Repository/         # Secondary Ports (Interfaces)
    │   │   └── TaskRepository.php
    │   └── Exception/          # Domain-specific Errors
    │       └── PriorityLimitReachedException.php
    │
    ├── Application/            # --- APPLICATION LAYER (Use Cases) ---
    │   ├── CreateTask/         # Use Case: Create a Task
    │   │   ├── CreateTaskCommand.php
    │   │   └── CreateTaskHandler.php
    │   └── DTO/                # Data Transfer Objects for Input/Output
    │       └── TaskResponse.php
    │
    └── Infrastructure/         # --- INFRASTRUCTURE LAYER (Adapters) ---
        ├── Persistence/        # Secondary Adapters (Implementation)
        │   └── Sqlite/
        │       └── SqliteTaskRepository.php
        └── Delivery/           # Primary Adapters (Entry points)
            ├── Http/           # API Controllers
            │   └── CreateTaskController.php
            └── Console/        # CLI Commands
                └── CreateTaskConsoleCommand.php
```

---

## Technical Stack
* **Language:** PHP 8.2+
* **Architecture:** Hexagonal / Ports & Adapters
* **Pattern:** Command / Command Bus
* **Persistence:** SQLite 3