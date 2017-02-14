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

![json result](https://raw.githubusercontent.com/captaincode0/silex-repository-pattern/master/screenshots/json-result.jpg)

##Arquitectura de la applicación

La aplicación usa una arquitectura basada en componentes y orientada a servicios, las rutas de la applicación son las siguientes:

Nombre|Ruta HTTP|Métodos Aceptados|Descripción
---|---|---|---
Obtener todos los usuarios|user.panel/users.json|get|Obtiene todos los usuarios de la base de datos y los retorna en formato JSON.
Obtener un usuario por id|user.panel/user.json/{id}|get|Obtiene un usuario por identificador y lo retorna en formatio JSON.
Añadir un usuario|user.panel/user.add|post|Añade un usuario a la base de datos siempre y cuando el email del usuario no exista.
Editar un usuario|user.panel/user.edit|post|Edita un usario de la base de datos siempre y cuando el id exista en la base de datos.
Eliminar un usuario|user.panel/user.del|post|Elimina un usuario de la base de datos siempre y cuando el id exista en la base de datos.

**Los controladores HTTP retornan formato JSON, por eso es recomendable indicar el formato usando un Middleware tipo after, esto mejora notablemente el rendimiento de la aplicación.**

```php
    $controllers->after(function(Request $request, Response $response) use($app){
        $response->headers->set("content-type", "application/json");
    });
```

###Modelado



###Nombres de espacio de la aplicación

Nombre de espacio|Descripción
---|---
MyApp\Kernel\Entity|Contiene todas las entidades de la aplicación.
MyApp\Kernel\Exception|Contiene todas las excepciones checadas de la aplicación.
MyApp\Kernel\HttpComponent\Controller|Almacena todos los controladores Http de la aplicación.
MyApp\Kernel\Repository|Almacena todos los repositorios de la aplicación.

###Validación de parámetros de las entidades con el módulo Validator de Symfony

La validación de los parámetros de las entidades se hace mediante el módulo Validator de Symfony, el cual para validar objetos implementa una función estática llamada `loadValidatorMetadata(ClassMetadata $metadata)`.



##Creando repositorios para entidades

Si tu repositorio es de tipo CRUD debes crearlo implementando la interface ICrudRepository y IRepository, en caso contrario solamente debes implementar la clase IRepository.

```php
    namespace MyApp\Kernel\Repository;

    use Silex\Application;
    use MyApp\Kernel\Repository\IRepository;
    use MyApp\Kernel\Repository\ICrudRepository;
    use MyApp\Kernel\Repository\Repository;
    use MyApp\Kernel\Entity\IEntity;

    namespace MyCRUDRepository extends Repository implements IRepository, ICrudRepository{
        /**
         * YOUR REPOSITORY IMPLEMENTATION GOES HERE
         */

        public function __construct(Application $app){
            parent::__construct($app);
        }

        /**
         * @Override
         */
        public function add(IEntity $entity){
           
        }

        /**
         * @Override
         */
        public function edit(IEntity $entity){
           
        }

        /**
         * @Override
         */
        public function del($user_id){
           
        }

        /**
         * @Override
         */
        public function getById($user_id){
           
        }
    
        /**
         * @Override
         */
        public function getAll($user_id){
           
        }
    }
```

##Prueba de la aplicación

##Referencias

1. [Doctrine](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/).
2. [Validator Service Provider](http://librosweb.es/libro/silex/apendice_a/validatorserviceprovider.html).
3. [Doctrine Service Provider](http://librosweb.es/libro/silex/apendice_a/validatorserviceprovider.html).
4. [How to create repository classes](http://symfony.com/doc/current/doctrine/repository.html)