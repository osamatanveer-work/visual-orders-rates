# Ubold - Laravel

Ubold laravel is bootstraped using standard laravel way. Please follow below steps in order to setup the project:

1. Make sure to have installed and running [`node`](https://nodejs.org/) and `yarn`
2. Make sure to have installed and running [`composer`](https://getcomposer.org/)
3. Open the laravel folder in command line E.g. `cd ubold-laravel`
4. Install frontend dependencies using command `yarn install`
5. Install backend dependencies using command `composer install`
6. Copy env.example and rename it to .env, make sure to edit required configuration including database
7. Run migration to create database tables using command `php artisan migrate`
8. Compile frontend assets using command `yarn run dev` or `yarn run prod`
9. Now, you are ready to start server using command `php artisan serve`


## Support

If you run into any trouble, feel free to contact us via our [website](https://coderthemes.com) or email [support@coderthemes.com](mailto:support@coderthemes.com). We would would happy to help.
