Despliegue en la nube
=====================
### Requisitos
-   Heroku cli

### Despliegue

1.  Clonamos el repositorio `git clone https://github.com/Jomari94/weRgamers.git`

2.  Creamos una aplicación en heroku

3.  Nos vamos al directorio donde hemos clonado la aplicación y ejecutamos:
```
heroku login
heroku git:remote -a aplicacion_heroku_wergamers
git push heroku master
```

4.  Añadimos el addon heroku-postgresql y hacemos un dump de la db local, luego ejecutamos `heroku pg:psql < dump.sql`

5.  Desplegamos [Jomari94/listener](https://github.com/Jomari94/listener):
    1.  Clonamos el repositorio `git clone https://github.com/Jomari94/listener.git`

    2.  Creamos una aplicación en heroku

    3.  Nos vamos al directorio donde hemos clonado la aplicación y ejecutamos:
    ```
    heroku login
    heroku git:remote -a aplicacion_heroku_listener
    git push heroku master
    ```

6.  Configuramos las variables de entorno:
-   GOOGLE_ID:  Id de la api de google
-   GOOGLE_SECRET:  Clave secreta de la api de google
-   SMTP_PASS: Contraseña de la cuenta de correo
-   LISTENER:  aplicacion_heroku_listener(usar https)
-   YII_ENV:    prod

7. La aplicación está lista para funcionar
