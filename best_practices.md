# best practices

## structure

Stick to this minimum structure for clarity:

1. Model
2. Repository
3. Service
4. Controller

## docker

- log to stderr
- use some specialized containers for metrics

## database

- how to store images in database
- what is migration

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

## questions?

- i did not use validator for logging in is it considered a best practice?

○ Create quiz
○ Get all quizzes
○ Edit quiz
○ Delete quiz
○ Create question
○ Get questions of specific quiz
○ Edit question
○ Delete question
