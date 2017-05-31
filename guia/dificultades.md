Dificultades Encontradas y Soluciones Aplicadas
===============================================

#### Navbar generado de Yii2
-   El navbar con clases de bootstrap que genera Yii2 es poco flexible y da
muchos problemas en cuanto quieres hacer algo que no sea muy común o que algún
componente sea responsive, por lo que he optado por no usar el widget e
implementarlo yo.
___

#### Chat en tiempo real y funcionando en heroku
-   El chat con lo visto en clase era imposible hacerlo en tiempo real, por
lo que investigué maneras de hacerlo y aprendí un poco de node.js y socket.io,
con lo que además conseguí implementar alguna funcionalidad más típica de los
chats.

-   Para que el chat funcionase en heroku necesitaba arrancar una aplicación en
node.js y escuchar por un puerto, primero me hice una aplicación de chat de
prueba con yii2 y node.js e intenté subirlo en un mismo dyno, una vez conseguido
intenté arrancar la aplicación de node.js en el mismo dyno con un buidpack
que permitía ejecutar varios comandos por dyno, pero no sirvió para nada porque
heroku sólo deja un puerto abierto por aplicación y necesitaba dos, uno para el
proyecto y otro para la aplicación en node.js. Al final opté por hacer una
aplicación aparte en heroku llamada jomari-listener para el chat.
-   Al ser una aplicación aparte y recibir peticiones desde un dominio distinto
al que pertenece tuve que habilitar las CORS (Cross Origin HTTP Request) ya que
por razones de seguridad, los exploradores restringen las solicitudes HTTP de
origen cruzado iniciadas dentro de un script.
___
