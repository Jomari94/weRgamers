Instalación y despliegue en local
=================================
### Requisitos
-   PHP >= 7.0.0
-   Composer
-   Postgresql >= 9.5
-   Apache 2
-   Node.js  ~6.10.0
-   Correo electrónico
-   Registrar la aplicación en el gestor de API de Google y habilitar Google+

Una vez cumplidos los requisitos procedemos a la instalación.
### Instalación
1.  Habilitamos un host virtual para la aplicación

2.  Habilitamos el módulo RewriteEngine del Apache

3.  Ejecutamos los siguientes comandos:
    ```
    git clone https://github.com/Jomari94/weRgamers.git
    cd weRgamers
    composer install
    composer run post-create-project-cmd
    cd web
    chmod 777 attachments, avatars, covers
    cd ../db
    sh ./create.sh
    sh ./load.sh
    ```

4.  En '/config/web.php' modificamos el nombre del usuario administrador y el  email a usar:
    ```
    'modules' => [
        'user' => [
            'admins' => ['Usuario_admin'],
            'mailer' => [
                'sender' => ['correo_a_usar' => "We 'r' Gamers"]
            ],
        ],
    ],
    ```

5.  Configurar las siguientes variables de entorno:
    -   GOOGLE_ID:  Id de la api de google
    -   GOOGLE_SECRET:  Clave secreta de la api de google
    -   SMTP_PASS: Contraseña de la cuenta de correo

6.  Para que funcione el chat necesitaremos la aplicación del repositorio
[Jomari94/listener](https://github.com/Jomari94/listener):
    -   `git clone https://github.com/Jomari94/listener.git`
    -   Una vez clonado ejecutamos la aplicación para hacer funcionar el chat:
        ```
        cd listener
        node index.js
        ```
        
