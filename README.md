
# XML to DB Library

The "XML to DB Library" is a minimalistic web application designed to demonstrate a simple yet effective implementation of an MVC framework in PHP. It focuses on processing XML files to populate a PostgreSQL database with book and author data. The application allows users to parse XML inputs and search for authors within the database.

## Features

- **Parse XML Input**: Users can upload XML files containing book and author data. Upon clicking the "Parse XML input" button, the data is parsed, stored in the database, and displayed in a modal window.
- **Author Search**: An input field and button enable users to search for authors by name or name fragments.
- **Cron Job**: Regularly scheduled tasks to import XML data into the database. See `docs/xml_to_db_cron_job.md` for setup details.

## Database Schema

The application utilizes two primary tables:

- `authors`: Stores unique authors.
- `books`: Stores books, each linked to an author ensuring that each author-book pair is unique.

## Technology Stack

- **Backend**: PHP for server-side logic.
- **Frontend**: ES6 and CSS for dynamic content and styling.
- **Database**: Supports both MySQL and PostgreSQL. Currently configured for PostgreSQL.

## MVC Architecture

A simplified MVC (Model-View-Controller) framework organizes the application, facilitating future maintenance and scalability.

## Exception Logging

All exceptions are logged to the `/logs` directory, aiding in debugging and monitoring application health.

## Future Improvements

- Enhanced exception handling to provide more detailed feedback and fail gracefully.
- Implement batch insert operations for adding books, similar to authors insert functionality to improve performance.
- Select of all author-book pairs in a single operation rather than individually to enhance app performance.

## Setup and Configuration

### Requirements

- PHP 8.0 or higher.
- PostgreSQL or MySQL database server.
- Composer for managing PHP dependencies.

### Installation

1. Clone the repository to your local machine.
2. Run `composer install` to install required PHP dependencies.
3. Configure your database connection in `config/database.php`.
4. Execute the provided SQL scripts to create the necessary database schema.
- The sql scripts are in "/config/database_dumps" folder.
5. Set up the cron job as described in `docs/xml_to_db_cron_job.md`.

### Running the Application

1. Start your PHP server pointing to the application's "/public" directory.
2. Open your web browser and navigate to the application URL.
3. Use the "Parse XML input" feature to upload and process XML files.
4. Use the "Author Search" to find authors in the database.

## Contributing

Contributions are welcome! Please feel free to submit pull requests or open issues for any bugs or feature suggestions.

## License

This project is open-source and available under the MIT License.