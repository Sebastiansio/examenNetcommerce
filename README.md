<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Pasos de la instalacion
- clonar repositorio
https://github.com/Sebastiansio/examenNet.git 

- composer update

- php artisan migrate

- php artisan db:seed

## Objetivos cubiertos
- Se uso Laravel 10.x

- Se puede consultar el listado de empresas y sus tareas relacionadas

- Se implemento el uso de relaciones Eloquent HasMany

- Se implemento el uso de relaciones Eloquent BelongsTo

- Se utilizó validación para la creación de tareas

- Se limito la creación de tareas pendientes a 5 por usuario

- Los modelos están correctamente nombrados en base a las convenciones de Laravel

- Las tablas de base de datos se pueden crear mediante migraciones

- Los modelos cuentan con Factories

- Se crearon Seeders

- No se utilizo consultas en “crudo (raw)”

- Se subió el código a un repositorio de GIT

- Se adjuntaron capturas de pantalla de los resultados de las consultas a los puntos de consulta

## Puntos de consulta
GET
**/api/companies**

POST
**/api/tasks/create**
