Decisiones Adoptadas y su Justificación
=======================================

#### Uso de una tabla de conversaciones para organizar los mensajes privados
-   Para obtener los mensajes de una conversación se puede hacer con una
consulta, pero necesita 2 cosas, que devuelva los mensajes en los que participa
el usuario y otro usuario concreto independientemente de que quien sea el emisor
y además que estén ordenados por fecha y hora de creación, esto con una tabla de
conversaciones se simplifica.
___

#### Búsqueda de usuarios y grupos por juego
-   Los requisitos R34 - Buscar un usuario por juego y R36 - Buscar un grupo por
juego están implementados, al ir a la página de búsquedas están los usuarios y
grupos en los que los términos buscados coinciden con algún juego de la
colección de un usuario, pero no queda muy claro por qué aparecen, por lo que
los juegos y grupos buscados según este criterio aparecen en su propia pestaña.
___

#### Uso de yii2-rbac
-   La autorización basada en RBAC se puede implementar sin mucha complicación,
pero esta extensión te permite crear roles y permisos y además asignarlos sin
tocar el código o la base de datos, por lo que lo encontré interesante para la
gestión de usuarios.


#### No implementación de los requisitos opcionales R41, R42 y R46
-   No he implementado los requisitos R41, R42 y R46 porque viendo que ya no
quedaba demasiado tiempo para la entrega, consideré más importante asegurarme
de que lo que ya tenía funcionaba bien y arreglar detalles por aquí y por allá,
que siempre hay.
