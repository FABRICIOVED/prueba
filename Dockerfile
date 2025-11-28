# Usa una versión fija de PHP + Apache
FROM php:8.2-apache

# Copia todos los archivos al directorio por defecto de Apache
COPY . /var/www/html/

# Si necesitas reescrituras (htaccess/rutas limpias), habilita mod_rewrite
RUN a2enmod rewrite

# Establece el WORKDIR
WORKDIR /var/www/html

# Expone el puerto 80 (por defecto de Apache)
EXPOSE 80

# Por defecto la imagen ya tiene CMD que lanza Apache en primer plano — no necesitas override
