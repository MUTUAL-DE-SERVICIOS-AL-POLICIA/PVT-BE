# PLATAFORMA VIRTUAL DE TRÁMITES - MÓDULO DE BENEFICIOS ECONÓMICOS

Sistema de administración y gestión de Fondo de Retiro, Complemento Económico, Cuota y Auxílio Mortuorio.

Laravel 10.50.4 sobre PHP 8.3.36.

### Documentación

* [Despliegue con Docker](./docker-compose.prod.yml) — producción
* [Despliegue con Docker](./docker-compose.yml) — desarrollo
* [Imagen del contenedor](./Dockerfile)
* Validación previa: `bash preflight.sh`
* Verificación posterior: `bash smoke.sh http://localhost:9014`

```sh
cp .env.example .env          # completar credenciales y APP_KEY
bash preflight.sh

docker compose up -d                                      # desarrollo, :9014
docker compose -f docker-compose.prod.yml up -d --build   # producción, :9015
```

Los dos modos usan el mismo contenedor `app`; solo cambian los montajes del
código y las variables de entorno. Artisan y Composer se ejecutan dentro:

```sh
docker compose exec app php artisan migrate
docker compose exec app composer install
```
