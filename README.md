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
