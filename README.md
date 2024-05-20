# stack-overflow-bridge-API

La **stack-overflow-bridge-API** es una API que consume datos públicos de Stack Overflow a través de una integración con la [API oficial de Stack Exchange](https://api.stackexchange.com/). Esta API permite acceder a información relevante sobre preguntas y respuestas en Stack Overflow de manera programática.


## Funcionalidades

El proyecto implementa dos endpoints principales como ejemplo:

1. **Listar Preguntas**: Este endpoint permite listar preguntas de Stack Overflow, con la posibilidad de ordenarlas y paginarlas según los parámetros especificados.

   **Endpoint:** `/questions`

   **Método HTTP:** GET

   **Parámetros:**
   - `page`: Número de página para la paginación (mínimo 1, por defecto 1).
   - `pageSize`: Tamaño de la página para la paginación (máximo 100, por defecto 10).
   - `order`: Orden de las preguntas (puede ser "asc" o "desc", por defecto "desc").
   - `sort`: Parámetro de ordenación de las preguntas (puede ser "activity", "votes", "creation", "hot", "week", "month", por defecto "activity").


2. **Obtener Respuestas**: Este endpoint permite obtener las respuestas asociadas a una pregunta específica en Stack Overflow.

   **Endpoint:** `/questions/{id}/answers`

   **Método HTTP:** GET

   **Parámetros:**
    - `id`: ID de la pregunta de Stack Overflow.


## Configuración

Para utilizar la **stack-overflow-bridge-API**, es necesario configurar dos variables de entorno:

- `STACK_EXCHANGE_URL`: La URL base de la API de Stack Exchange que se está integrando. Por defecto, se utiliza `https://api.stackexchange.com/2.3`.
- `STACK_EXCHANGE_KEY`: La clave de acceso a la API de Stack Exchange. Esta clave es proporcionada por Stack Overflow al registrar una aplicación en Stack Apps. Es importante tener esta clave para evitar bloqueos por parte de Stack Exchange debido al límite de llamadas.


## Uso

1. Clone el proyecto desde el repositorio.


2. Navegue hasta el directorio del proyecto.


3. Ejecute el siguiente comando para configurar y levantar el proyecto:

    ```bash
    make deploy
    ```

4. Una vez que el proyecto se haya desplegado correctamente, puede acceder a él a través de su navegador web visitando [http://127.0.0.1:8080](http://127.0.0.1:8080).


## Arquitectura de la Aplicación

La arquitectura de la aplicación intenta seguir los principios de una arquitectura limpia, también conocida como arquitectura hexagonal junto con el patrón de puertos y adaptadores. Este enfoque arquitectónico promueve la modularidad, la separación de preocupaciones y la facilidad de mantenimiento.

### Organización de Carpetas

La aplicación está cuidadosamente organizada en diferentes carpetas, cada una con una responsabilidad específica:

- **Api**: Contiene los endpoints de la API que proporcionan acceso a los recursos de Stack Overflow.

- **Application**: Aquí se encuentran los casos de uso de la aplicación, representados por los proveedores de información de respuestas (`AnswerInfoProvider`) y preguntas (`QuestionInfoProvider`).

- **Domain**: Contiene las clases de dominio, como `Answer`, `Owner` y `Question`, y las interfaces de los repositorios (`AnswerRepositoryInterface` y `QuestionRepositoryInterface`) que definen contratos para la persistencia de datos.

- **Infrastructure/Client**: En esta carpeta se encuentran los clientes que se integran con Stack Exchange (`AnswerClient` y `QuestionClient`). Estos clientes implementan las interfaces de los repositorios (`AnswerRepositoryInterface` y `QuestionRepositoryInterface`) definidas en el dominio.

- **Infrastructure/Event**: Contiene la implementación de eventos, como `KernelExceptionEvent`, que captura excepciones y las transforma en respuestas JSON adecuadas para mejorar la gestión de errores en la aplicación.

La estructura de carpetas proporciona una clara separación de responsabilidades y facilita el mantenimiento del código.

La arquitectura de puertos y adaptadores se refleja en la separación clara entre el núcleo de la aplicación y los detalles de implementación. Los puertos representan las interfaces a través de las cuales la aplicación interactúa con el mundo exterior, mientras que los adaptadores son responsables de conectar los puertos con los detalles de implementación concretos.


## Documentación

Hemos añadido Swagger a nuestra API utilizando la librería NelmioApiDocBundle. Esta integración nos permite generar una documentación interactiva de la API, donde se pueden explorar y probar los diferentes endpoints de manera sencilla.

Puedes acceder a la documentación de la API generada por Swagger en la siguiente URL:

http://127.0.0.1:8080/api/doc


NelmioApiDocBundle facilita la creación de documentación detallada y clara para nuestra API, mejorando así la comprensión y uso de la misma por parte de los desarrolladores.

Para más información sobre NelmioApiDocBundle, puedes consultar la [documentación oficial](https://symfony.com/bundles/NelmioApiDocBundle/current/index.html).


## Siguientes Pasos

La **stack-overflow-bridge-API** proporciona acceso a datos de Stack Overflow de una manera estructurada y programática. Hasta el momento, hemos implementado funcionalidades para paginar preguntas y obtener las respuestas asociadas a estas preguntas. Aquí están algunos posibles siguientes pasos para continuar desarrollando la API:

### Autenticación de Usuarios

Implementa un sistema de autenticación para permitir que los usuarios accedan a funcionalidades adicionales de la API, como la capacidad de publicar preguntas, responder preguntas, votar, comentar, entre otros.

### Crear, Editar y Eliminar Posts

Permite a los usuarios autenticados crear nuevas preguntas y respuestas, editar sus propios posts existentes y eliminarlos si es necesario. Esto proporcionará una funcionalidad completa para interactuar con Stack Overflow a través de la API.

### Comentarios y Votación

Implementa la capacidad de comentar en preguntas y respuestas, así como la posibilidad de votar por preguntas y respuestas. Esto permitirá una mayor interacción de los usuarios con el contenido.

### Búsqueda Avanzada

Introduce funcionalidades de búsqueda avanzada que permitan a los usuarios buscar preguntas por etiquetas, fecha de publicación, número de votos, etc. Esto mejorará la capacidad de los usuarios para encontrar el contenido relevante.

### Estadísticas y Métricas

Implementa endpoints que proporcionen estadísticas sobre el uso de la API, métricas de popularidad de preguntas y respuestas, tendencias de etiquetas, etc. Esto ayudará a los desarrolladores a comprender mejor cómo se está utilizando la API.