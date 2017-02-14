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

![model](https://raw.githubusercontent.com/captaincode0/silex-repository-pattern/master/screenshots/model.jpg)

La interfaz `ÌEntity` se usa para crear un tipo de dato que extiente a todas las entidades, el cual contiene un método to Array que castea la entidad a un arreglo, fue anexado para usos a futuro.

```php
    class MyEntity implements IEntity{
    }
```

La interfaz `ICrudRepository` se usa para definir repositorios que contengan operaciones de un CRUD y la interfaz `IRepository` se usa para definir un tipo`de dato.

Tal como se puede observar en el siguiente código ICrudRepository define las operaciones propias de un CRUD.

```php
    namespace MyApp\Kernel\Repository;

    use MyApp\Kernel\Entity\IEntity;

    interface ICrudRepository{
        /**
         * [add creates a new entity in the database]
         * @param IEntity $entity [current entity]
         * @return bool             [if the entity was added true]
         */
        public function add(IEntity $entity);
        
        /**
         * [edit updates an existent entity on the database]
         * @param  IEntity $entity [current entity]
         * @return bool            [if the entity was edited correctly true]
         */
        public function edit(IEntity $entity);
        
        /**
         * [del removes an existent entity on the database]
         * @param  int $entity_id [current entity id]
         * @return bool          [if the entity was deleted correctly true]
         */
        public function del($entity_id);
        
        /**
         * [getById retrieves one entity by id]
         * @param  int $entity_id [the current entity id]
         * @return array          [one array that represents the entity, null if there is not the requested entity in the database]
         */
        public function getById($entity_id);

        /**
         * [getAll retrieves all the data from database]
         * @return array [one array that represents all the data, null if there is not entities in the database]
         */
        public function getAll();
    }
```

###Nombres de espacio de la aplicación

Nombre de espacio|Descripción
---|---
MyApp\Kernel\Entity|Contiene todas las entidades de la aplicación.
MyApp\Kernel\Exception|Contiene todas las excepciones checadas de la aplicación.
MyApp\Kernel\HttpComponent\Controller|Almacena todos los controladores Http de la aplicación.
MyApp\Kernel\Repository|Almacena todos los repositorios de la aplicación.

###Validación de parámetros de las entidades con el módulo Validator de Symfony

La validación de los parámetros de las entidades se hace mediante el módulo Validator de Symfony, el cual para validar objetos implementa una función estática llamada `loadValidatorMetadata(ClassMetadata $metadata)`.

Tal como se puede ver en la entidad usuario se valida lo siguiente que el id del usuario sean números positivos, que el email sea válido, que la contraseña tenga por lo menos seis caracteres letras mayúsculas, minúsculas y números.

```php
    public static function loadValidatorMetadata(ClassMetadata $metadata){
            $metadata->addPropertyConstraint("id", new Assert\Regex([
                "pattern" => "/^\d+$/",
                "message" => "The user id needs to be a number"
            ]));

            $metadata->addPropertyConstraint("email", new Assert\Email([
                "message" => "The user email needs to be valid"
            ]));

            $metadata->addPropertyConstraint("pass", new Assert\Regex([
                "pattern" => "/^[a-zA-Z_0-9]{6,}$/",
                "message" => "The password needs to contain at least six numbers or lower characters or upper characters or a mix between them"
            ]));
        }
```

Para validar el objeto actual y ver que todos sus parámetros o campos sean integros, entonces se ejecuta en un middleware before, como en el siguiente ejemplo:

```php
    ->before(function(Request $request, Application $app){
        $email = $request->get("email");
        $pass = $request->get("pass");

        $user_object = new User();
        $user_object->setEmail($email);
        $user_object->setPass($pass);
        
        $errors = $app["validator"]->validate($user_object);

        //check if exists errors in the validation
        if(count($errors) > 0){
            $msg = "";

            //concatenate the errors
            foreach($errors as $error)
                $msg .= $error->getMessage()."\n";

            return new JsonResponse(["msg" => $msg], 400);
        }
    });
```

##Creando repositorios para entidades

Si tu repositorio es de tipo CRUD debes crearlo implementando la interface `ICrudRepository` y `IRepository`, en caso contrario solamente debes implementar la interfaz `IRepository`.

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

##Registar un repositorio

Para registrar un repositorio en la aplicación se hace mediante servicios compartidos, al ser compartido mejora el rendimiento de tu aplicación porque no crea al servicio cada vez que se usa, mantiene la misma instancia, para usarse una y otra vez.

```php
    $app["repository.users"] = $app->share(function() use($app){
        return new UserRepository($app);
    });
```

**El servicio requiere de la aplicación actual para acceder a servicios como Doctrine para accesar a bases de datos**

##Prueba de la aplicación

###Obtener todos los usuarios

![view all users](https://raw.githubusercontent.com/captaincode0/silex-repository-pattern/master/screenshots/get-all-users.png)

###Obtener un usuario

![view one user by id](https://raw.githubusercontent.com/captaincode0/silex-repository-pattern/master/screenshots/get-one-user.png)

###Añadir un usuario

![add one user](https://raw.githubusercontent.com/captaincode0/silex-repository-pattern/master/screenshots/add-new-user.png)

En la siguiente imagen se puede ver al usuario añadido correctamente con el id igual a 9:

![view user added](https://raw.githubusercontent.com/captaincode0/silex-repository-pattern/master/screenshots/get-final-users.png)

###Añadir un usuario repetido

Al añadir un usuario con un email que exista en la base de datos, se obtiene un mensaje de error con un código 400 Bad Request.

![user repeated](https://raw.githubusercontent.com/captaincode0/silex-repository-pattern/master/screenshots/insert-replicate%20-email.png)

###Editar un usuario

En la siguiente imagen se edita la contraseña del usuario de 1234567 a mypassword.

![edit user password](https://raw.githubusercontent.com/captaincode0/silex-repository-pattern/master/screenshots/edit-user-password.png)

##Eliminar un usuario

En la siguiente imagen se elimina un usuario que tiene el id asignado a 8.

![delete user 8](https://raw.githubusercontent.com/captaincode0/silex-repository-pattern/master/screenshots/del-user-8.png)

En la siguiente imagen se puede ver que el usuario ha sido eliminado correctamente de la base de datos.

![user deleted](https://raw.githubusercontent.com/captaincode0/silex-repository-pattern/master/screenshots/view-user-deleted-8.png)

##Referencias

1. [Doctrine](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/).
2. [Validator Service Provider](http://librosweb.es/libro/silex/apendice_a/validatorserviceprovider.html).
3. [Doctrine Service Provider](http://librosweb.es/libro/silex/apendice_a/validatorserviceprovider.html).
4. [How to create repository classes](http://symfony.com/doc/current/doctrine/repository.html)