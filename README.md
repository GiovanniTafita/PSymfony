## ErgoRH
### Composer
```
composer install
```

### Generate key
```
php bin/console lexik:jwt:generate-keypair
```
### Create database
```
php bin/console doctrine:database:create
```
### Database migration
```
php bin/console doctrine:migrations:migrate
```
