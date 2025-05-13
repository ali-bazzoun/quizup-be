# best practices

## structure

Stick to this minimum structure for clarity:

1. Model
2. Repository
3. Service
4. Controller

### separation of concerns

- all functions related to direct data_base and security of the data in model/repo layer
- handle logic and business in service layer
- add last line of defense in the data layer and considering handling errors if it occurs their
- handle input validation at control layer, the closest layer to the input
- routing not in controller layer (in Router class or index)

## docker

- log to stderr
- use some specialized containers for metrics

## database

- how to store images in database
- what is migration
- when making update function pay the attention to the allowed fields that can be updated
- wrap the entire prepare/execute in a try { … } catch (PDOException $e) { log_error($e); throw $e; } so that you log every SQL error with context.

## php

- not to add ?> at the end of the php file unless there is html in the php file

## auth

### userid

- username not same as userid
- username is unique

### password

- min 8 char
- max 64 char (for phrases)
- allow all types of chars even spaces long passwords are better.
- use unique strong passwords
- use multi factor auth (mfa) password, phone, biometrics (passwords are not enough alone anymore, nist talks about relying only on biometrics)
- seekless list
- breechless
- never store password as plain text use hashing
- recover for forget passwords
- osp (logins with admin super privileged should not be allowed to sign in from normal front end)
- pay attention to error message best practice: generic message do not specify anything (email or password is not valid)
- limit failed login attempts
- use tls encrypt data
- password manager may be good solution
- (owas) changing email is risky ...

## frameworks to learn

### testing

- PHPUnit – standard testing framework
- Mockery or PHPUnit built-in mocks – mocking DB behavior
- Faker – for generating fake test data

## solid principles

### Single Responsibility Principle (SRP)

- **Controllers:** Their sole responsibility is to handle incoming HTTP requests, validate input (often by delegating to a validation service/component), call appropriate services to perform the core logic, and then format and return an HTTP response (e.g., JSON). They shouldn't contain business logic or direct database queries.
- **Services:** Contain the core business logic. For example, a UserService might handle user registration, login verification, profile updates, etc.
- **Models/Entities:** Represent your data structures.
- **Repositories (Optional but Recommended):** Encapsulate the logic for fetching and persisting data, abstracting the database from your services.

### Open/Closed Principle (OCP)

Your system should be open for extension but closed for modification.

- Using interfaces for services or repositories allows you to swap out implementations (e.g., a different database, a mock for testing) without changing the controller code that uses them.
- Middleware can be added to the request/response pipeline without altering core controller or service logic.

### Liskov Substitution Principle (LSP)

Subtypes must be substitutable for their base types. When using inheritance (though favor composition), ensure derived classes can replace their parent classes without altering the correctness of the program.

- Interface Segregation Principle (ISP): Clients should not be forced to depend on interfaces they do not use.
- Define small, specific interfaces. For example, instead of one large DataAccessInterface, you might have UserReaderInterface and UserWriterInterface.
- Dependency Inversion Principle (DIP): High-level modules should not depend on low-level modules. Both should depend on abstractions (e.g., interfaces). Abstractions should not depend on details. Details should depend on abstractions.
- Dependency Injection (DI): This is key. Instead of a controller creating its own service instance ($userService = new UserService();), the service (or its interface) is "injected" into the controller, typically via its constructor. This makes your code more modular, testable, and flexible. DI containers can automate this process.
