# Silex Repository Pattern (Patrón de Repositorio Aplicado al Framework Silex)

Los repositorios en Silex son servicios que contienen la lógica de una entidad y agrupan las operaciones básicas como agregar, editar, eliminar y ver, si se integran con un origen de datos.

Como por ejemplo si tenemos una web app que permite postear estados, la clase `Post` sería mi entidad y `PostRepository` sería el repositorio para la entidad.

##Instalación

Para instalar la aplicación y testearla se ejecutan los siguientes comandos:

```bash
    cd /var/www/html

    #clonar el repositorio de la aplicación
    git clone https://github.com/captaincode0/silex-repository-pattern.git
    cd silex-repository-pattern

    #instalar el framework y sus componentes
    composer install
    cd src/kernel/db
    
    #restaurar la base de datos
    mysql -u myuser -p < db.sql

    #abrir una nueva ventana de firefox y mostrar los usuarios de la base de datos
    firefox --new-window http://127.0.0.1/silex-repository-pattern/web/index.php/private/users.panel/users.json
```

Una vez abierto el navegador se visualizarán todos los usuarios de la base de datos.

##Arquitectura de la applicación



##Creando repositorios para entidades

##Prueba de la aplicación

##Referencias

1. [Doctrine](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/).
2. [Validator Service Provider](http://librosweb.es/libro/silex/apendice_a/validatorserviceprovider.html).
3. [Doctrine Service Provider](http://librosweb.es/libro/silex/apendice_a/validatorserviceprovider.html).
4. [How to create repository classes](http://symfony.com/doc/current/doctrine/repository.html)