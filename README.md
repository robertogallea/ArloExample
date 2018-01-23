# ArloExample
Laravel example application for testing Netgear Arlo API

##1. Usage
- Clone repository using<br>
``` 
git clone git@github.com:robertogallea/ArloExample.git
```

- Install packages and setup application
```
composer install
```

- Set the following variable in .env.example and save it to .env
```
ARLO_USER=<yourusername>
ARLO_PASSWORD=<yourpassword>
```

- Generate a key for the application
```
php artisan key:generate
```

- Run demo
```
php artisan serve
```

- Navigate to http://localhost:8080
