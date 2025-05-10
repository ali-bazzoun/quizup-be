# best practices

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
- use multi factor auth (mfa) password, phone, biometrics (passwords are enough alone anymore, nist talks about relying only on biometrics)
- seekless list
- breechless
- never store password as plain text use hashing 
- recover for forget passwords
- osp (logins with admin super privilleged should not be allowed to sign in from normal front end)
- payettention to error message best practice: generic message do not specify anything (username or password is wrong)
- limit failed login attempts
- use tls encrypt data
- password manager may be good solution
- (owas) changing email is risky ...


## questions? 

- i did not use validator for logging in is it considered a best practice?