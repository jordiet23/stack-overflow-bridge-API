# stack-overflow-bridge-API

The **stack-overflow-bridge-API** is an API that consumes public data from Stack Overflow through an integration with the [official Stack Exchange API](https://api.stackexchange.com/). This API allows programmatic access to relevant information about questions and answers on Stack Overflow.

## Features

The project implements two main endpoints as examples:

1. **List Questions**: This endpoint allows you to list questions from Stack Overflow, with the ability to sort and paginate them according to specified parameters.

   **Endpoint:** `/questions`

   **HTTP Method:** GET

   **Parameters:**
   - `page`: Page number for pagination (minimum 1, default 1).
   - `pageSize`: Page size for pagination (maximum 100, default 10).
   - `order`: Order of the questions (can be "asc" or "desc", default is "desc").
   - `sort`: Sorting parameter for the questions (can be "activity", "votes", "creation", "hot", "week", "month", default is "activity").

2. **Get Answers**: This endpoint allows you to retrieve the answers associated with a specific question on Stack Overflow.

   **Endpoint:** `/questions/{id}/answers`

   **HTTP Method:** GET

   **Parameters:**
   - `id`: ID of the Stack Overflow question.

## Configuration

To use the **stack-overflow-bridge-API**, you need to configure two environment variables:

- `STACK_EXCHANGE_URL`: The base URL of the Stack Exchange API being integrated. By default, it is set to `https://api.stackexchange.com/2.3`.
- `STACK_EXCHANGE_KEY`: The access key for the Stack Exchange API. This key is provided by Stack Overflow when registering an application on Stack Apps. It is important to have this key to avoid rate limiting by Stack Exchange.

## Usage

1. Clone the project from the repository.

2. Navigate to the project directory.

3. Run the following command to set up and launch the project:

    ```bash
    make deploy
    ```

4. Once the project is successfully deployed, you can access it via your web browser by visiting [http://127.0.0.1:8080](http://127.0.0.1:8080).

## Testing

Our project includes comprehensive tests to ensure the quality and reliability of the application. We have implemented unit tests for both the Application and Infrastructure layers, as well as WebTestCase tests for our controllers.

### Types of Tests

- **Unit Tests**: These are located in the `tests/Application` and `tests/Infrastructure` directories. They cover the core logic and functionalities of our application and infrastructure layers.
- **Web Tests**: These are located in the `tests/Api` directory. They utilize Symfony's `WebTestCase` to test the behavior and responses of our controllers in a simulated HTTP environment.

### Running Tests

To execute the tests, follow these steps:

1. **Deploy the application**: Ensure all services are up and running.
    ```bash
    make deploy
    ```
2. **Run the tests**: This will execute all the tests in the project.
    ```bash
    make test
    ```
By following these commands, you can verify that all aspects of the application are working as expected.


## Application Architecture

The application architecture follows the principles of clean architecture, also known as hexagonal architecture, combined with the ports and adapters pattern. This architectural approach promotes modularity, separation of concerns, and ease of maintenance.

### Folder Structure

The application is organized into different folders, each with a specific responsibility:

- **Api**: Contains the API endpoints that provide access to Stack Overflow resources.

- **Application**: Contains the use cases of the application, represented by the answer information provider (`AnswerInfoProvider`) and question information provider (`QuestionInfoProvider`).

- **Domain**: Contains the domain classes such as `Answer`, `Owner`, and `Question`, and the repository interfaces (`AnswerRepositoryInterface` and `QuestionRepositoryInterface`) that define contracts for data persistence.

- **Infrastructure/Client**: Contains the clients that integrate with Stack Exchange (`AnswerClient` and `QuestionClient`). These clients implement the repository interfaces (`AnswerRepositoryInterface` and `QuestionRepositoryInterface`) defined in the domain.

- **Infrastructure/Event**: Contains the implementation of events such as `KernelExceptionEvent`, which captures exceptions and transforms them into appropriate JSON responses to improve error handling in the application.

The folder structure provides a clear separation of responsibilities and facilitates code maintenance.

The architecture reflects the principles of ports and adapters by clearly separating the core application logic from implementation details. Ports represent the interfaces through which the application interacts with the external world, while adapters are responsible for connecting the ports with concrete implementation details.

## Documentation

Swagger has been added to our API using the NelmioApiDocBundle library. This integration allows us to generate interactive API documentation where you can explore and test the different endpoints easily.

You can access the API documentation generated by Swagger at the following URL:

http://127.0.0.1:8080/api/doc

NelmioApiDocBundle makes it easy to create detailed and clear documentation for our API, thus improving the understanding and usage of the API by developers. For more information about NelmioApiDocBundle, you can refer to the [official documentation](https://symfony.com/bundles/NelmioApiDocBundle/current/index.html).

## Next Steps

The **stack-overflow-bridge-API** provides structured and programmatic access to Stack Overflow data. So far, we have implemented functionalities to paginate questions and retrieve the answers associated with those questions. Here are some possible next steps for further development of the API:

### User Authentication

Implement a user authentication system to allow users to access additional API functionalities, such as posting questions, answering questions, voting, commenting, and more.

### Create, Edit, and Delete Posts

Allow authenticated users to create new questions and answers, edit their existing posts, and delete them if necessary. This will provide full functionality to interact with Stack Overflow through the API.

### Comments and Voting

Implement the ability to comment on questions and answers, as well as vote for questions and answers. This will enable greater user interaction with the content.

### Advanced Search

Introduce advanced search functionalities that allow users to search for questions by tags, publication date, number of votes, etc. This will enhance users' ability to find relevant content.

### Statistics and Metrics

Implement endpoints that provide statistics on API usage, question and answer popularity metrics, tag trends, etc. This will help developers better understand how the API is being used.
