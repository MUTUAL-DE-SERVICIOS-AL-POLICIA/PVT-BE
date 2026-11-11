# ---------------------------------------------------------------------------
# Imagen de PVT-BE: Laravel 10 + PHP 8.3.36 (runtime con el parche de
# CVE-2026-109618 / CVE-2026-109619).
#
# Tres etapas, cada una con un trabajo:
#   assets   compila el frontend con Laravel Mix (Node). Aporta solo public/.
#   builder  compila las extensiones de PHP (necesita gcc y headers -dev).
#   final    runtime: php-fpm + nginx + extensiones .so + public/ compilado.
#
# La imagen final NO lleva Node, npm ni node_modules: la etapa assets se
# descarta. El .env NO se hornea: se monta en runtime, porque el frontend se
# compila con las variables de build (MIX_APP) y no con el .env.
#
#   docker build --build-arg MIX_APP=prod -t pvt-app:prod .
# ---------------------------------------------------------------------------

# --- Etapa 1: compilar el frontend -----------------------------------------
# Node 8 es obligatorio: el proyecto usa Laravel Mix 1.x (webpack 3).
# Para actualizar Mix hay que subir NODE_VERSION y probar `npm run production`.
ARG NODE_VERSION=8
FROM node:${NODE_VERSION} AS assets

WORKDIR /app

# Dependencias primero: si no cambia el lock, la capa se reutiliza y solo se
# recompila el bundle cuando cambian las fuentes.
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

# Fuentes del frontend. Tambien views/: purgecss las lee para no borrar clases
# que se usan en las plantillas.
# artisan: Laravel Mix solo detecta que es un proyecto Laravel si existe ese
# archivo (Mix.sees('laravel')); sin el, publicPath queda vacio y .version()
# busca los archivos en la raiz del contenedor.
COPY artisan webpack.mix.js ./
COPY resources ./resources

# MIX_APP elige el CSS (prod | test | dev). Un valor invalido falla el build.
# MIX_PVT_URL se pasa a la etapa; hoy webpack.mix.js no la inyecta en el bundle
# (falta el DefinePlugin), se agrega en un commit aparte.
ARG MIX_APP=prod
ARG MIX_PVT_URL=
ENV MIX_APP=${MIX_APP}
ENV MIX_PVT_URL=${MIX_PVT_URL}

RUN npm run production

# --- Etapa 2: compilar las extensiones de PHP ------------------------------
FROM php:8.3.36-fpm AS builder

RUN apt-get update && apt-get install -y --no-install-recommends \
      $PHPIZE_DEPS \
      libzip-dev libldap2-dev libgmp-dev libicu-dev libpq-dev \
      libpng-dev libjpeg62-turbo-dev libfreetype6-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
       bcmath gmp intl ldap pdo_pgsql pgsql zip gd opcache pcntl soap ftp \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

# --- Etapa 3: imagen final, solo runtime -----------------------------------
FROM php:8.3.36-fpm

# Librerias de runtime que necesitan las extensiones copiadas (sin headers ni
# compilador) + nginx + lo que exige el binario de wkhtmltopdf.
RUN apt-get update && apt-get install -y --no-install-recommends \
      libzip5 libldap2 libgmp10 libicu76 libpq5 libpng16-16t64 \
      libjpeg62-turbo libfreetype6 libxml2 \
      nginx unzip curl locales \
      libxrender1 libfontconfig1 libxext6 libx11-6 libxcb1 \
      fontconfig xfonts-base xfonts-75dpi fonts-dejavu-core \
    && sed -i 's/^# *es_ES.UTF-8/es_ES.UTF-8/; s/^# *es_BO.UTF-8/es_BO.UTF-8/' /etc/locale.gen \
    && locale-gen es_ES.UTF-8 es_BO.UTF-8 \
    && rm -rf /var/lib/apt/lists/*

# Extensiones compiladas en el builder: solo los .so y sus .ini.
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

# Dependencias primero: si no cambia el lock, la capa se reutiliza.
# Solo las dependencias: el autoloader se genera despues de copiar el codigo,
# porque el classmap apunta a app/ y database/.
COPY composer.json composer.lock ./
# COMPOSER_NO_DEV=--no-dev en produccion. Para una imagen de testing con las
# dependencias de desarrollo (por ejemplo laravel-debugbar, que muestra el
# entorno y la version), construir con --build-arg COMPOSER_NO_DEV=
ARG COMPOSER_NO_DEV=--no-dev
RUN composer install ${COMPOSER_NO_DEV} --no-scripts --no-autoloader --no-interaction --prefer-dist

COPY . .

# public/js y public/css entran al contexto sin los compilados (ver
# .dockerignore): los que valen son los de la etapa assets. Los estaticos
# (datatables.js, vendor.css, patterns/, fonts/, images/) vienen del repo.
COPY --from=assets /app/public/js/  /var/www/public/js/
COPY --from=assets /app/public/css/ /var/www/public/css/
COPY --from=assets /app/public/mix-manifest.json /var/www/public/mix-manifest.json

# Las fuentes del frontend viajan al contexto para la etapa assets, pero en
# runtime no se usan: fuera de la imagen (31 MB).
RUN rm -rf resources/assets

# El cache de paquetes se genera en runtime: si viaja desde el arbol de
# desarrollo, referencia paquetes de require-dev que la imagen no tiene.
RUN rm -f bootstrap/cache/*.php \
    && composer dump-autoload ${COMPOSER_NO_DEV} --optimize --no-scripts \
    && mkdir -p storage/framework/cache storage/framework/sessions \
                storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Pool de php-fpm: el default de la imagen es pm.max_children = 5, que encola
# a partir de 6 peticiones simultaneas. Medido con k6: con 40 VUs y 5 workers
# el p95 llegaba a 873 ms; con 20 baja. Ajustar segun RAM: ~80 MB por worker.
RUN sed -i 's/^pm.max_children = .*/pm.max_children = 20/' \
            /usr/local/etc/php-fpm.d/www.conf \
 && sed -i 's/^pm.start_servers = .*/pm.start_servers = 5/' \
            /usr/local/etc/php-fpm.d/www.conf \
 && sed -i 's/^pm.min_spare_servers = .*/pm.min_spare_servers = 3/' \
            /usr/local/etc/php-fpm.d/www.conf \
 && sed -i 's/^pm.max_spare_servers = .*/pm.max_spare_servers = 10/' \
            /usr/local/etc/php-fpm.d/www.conf \
 && sed -i '/^;pm.max_requests = 500/a pm.max_requests = 500' \
            /usr/local/etc/php-fpm.d/www.conf

COPY php.ini /usr/local/etc/php/conf.d/zz-prod.ini
COPY nginx.conf /etc/nginx/conf.d/default.conf
RUN rm -f /etc/nginx/sites-enabled/default \
    && chmod -R a+rX /var/www

# php-fpm escucha en 127.0.0.1:9000 y nginx publica el 8080: un solo
# contenedor, sin volumenes compartidos de codigo.
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

LABEL org.opencontainers.image.title="PVT-BE" \
      org.opencontainers.image.description="Laravel 10.50 sobre PHP 8.3.36" \
      com.pvt.php.cve="CVE-2026-109618 CVE-2026-109619"
