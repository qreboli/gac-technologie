# gac-technologie

## install project

### Clone or download repository and go to path project
```
cd gac-technologie/
```

### install dependencies with
```
composer install
```
### Create database with 
```
php bin/console d:d:c
```

### Execute migrations
```
php bin/console d:migr:diff
```
### Execute migrations migrate
```
php bin/console d:migr:migr
```
### Start the web server with 
```
symfony server:start
```
### Execute import command 
```
php bin/console app:import-data
```
### Open web browser and go to 
```
https://127.0.0.1:8000/data
```
