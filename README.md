
# Central Authentiation

***CAUTION: For this project(in particular PHP module) since it is an abstract component, we assume IP White listing policy. whithout any IP restriction project files might be vulnerable.



## Features

- User registration and login
- Password hashing with password_hash
- Support for user metadata (e.g., roles, registration timestamps)
- PDO-based MySQL connection for secure database interactions
- Simple API key check for request authorization


## Prerequisites
- PHP >= 7.4
- MySQL
- Composer for dependency management

## Authors

- [@AmirAhmadabadiha](https://ir.linkedin.com/in/amir-ahmadabadiha-259113175)


## Structure
```
/auth-service
│── /config
│   └── database.php            # Database configuration
│── /public
│   └── index.php               # Public entry point
│── /src
│   └── Database.php            # Database connection class
│   └── User.php                # User model class
│   └── UserMeta.php            # UserMeta model class
│   └── AuthService.php         # Authentication service class
│── /vendor                     # Composer dependencies
│── composer.json               # Composer configuration
│── README.md                   # Project documentation
```





## DB Schema
```
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `platform` VARCHAR(50) NOT NULL,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `usermeta` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `meta_key` VARCHAR(255) NOT NULL,
  `meta_value` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);
```
## Deployment
First Change on `config/database.php`
```
<?php

return [
    'db' => [
        'host' => 'localhost',
        'dbname' => 'auth_service',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
];
```
You need to define your static TOKEN in `public/index.php`
```
define('API_KEY', 'your-static-api-key-here');
```
To deploy this project run (use APACHE in production)

```bash
  php -S localhost:8000 -t public

```

