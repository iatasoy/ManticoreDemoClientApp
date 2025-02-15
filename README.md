# ManticoreDemoClientApp

A simple PHP client application to interact with **Manticore Search** to perform search queries on customer data indexed from a MySQL database.

## Prerequisites

Before running the application, ensure that the following are installed and properly configured:

- **PHP** (7.4 or higher)
- **Composer** for dependency management
- **Manticore Search** (already installed and running)

### Manticore Installation

1. **Download and Install Manticore**: Ensure you have Manticore Search installed and running. Follow the installation guide on the official [Manticore Search website](https://manticoresearch.com/docs/install/).
   
2. **Manticore Configuration (`manticore.conf`)**: Make sure that your `manticore.conf` is set up as follows to configure your data source and indexing.

    ```ini
    # Manticore Search Configuration File

    ## Data Source Configuration
    source tbcustomer_source
    {
        type            = mysql
        sql_host        = 127.0.0.1
        sql_user        = username
        sql_pass        = password
        sql_db          = manticore_sample
        sql_port        = 3306
        sql_query       = SELECT customerid, firstname, lastname, email, phone, creationdate FROM tbcustomer
        sql_attr_uint    = customerid
        sql_field_string = firstname
        sql_field_string = lastname
        sql_field_string = email
        sql_field_string = phone
        sql_field_string = creationdate
    }

    ## Index Definition
    index tbcustomer
    {
        source = tbcustomer_source
        path   = /var/lib/manticore/tbcustomer
    }

    ## Search Daemon Configuration
    searchd
    {
        listen = 127.0.0.1:9312
        listen = 127.0.0.1:9306:mysql
        listen = 0.0.0.0:9308:http
        log = /var/log/manticore/searchd.log
        query_log = /var/log/manticore/query.log
        pid_file = /var/run/manticore/searchd.pid
    }
    ```

Ensure that `tbcustomer` table data is indexed in Manticore, and the search daemon is running.

## Getting Started

### 1. Clone the Repository

Clone this repository to your local machine:

```bash
git clone https://github.com/iatasoy/ManticoreDemoClientApp.git
cd ManticoreDemoClientApp
```
### 2. Install Dependencies
Make sure Composer is installed on your system. If it's not, you can install it from here.

Run the following command to install the required dependencies:

```bash
composer install
```

### 3. Configure Environment
Create a .env file in the root directory and add the following environment variables:

```ini
MANTICORE_API_URL=http://127.0.0.1:9308
```
This file will be used to store the Manticore Search API URL. The application will use this value to connect to the search daemon.

### 4. Run the Application
To start the PHP built-in server, run the following command:
```bash
php -S localhost:8080 index.php
Your application will be available at http://localhost:8080.
```

### 5. Test the Application
You can test the functionality by visiting the following URLs:
```
Home Page: http://localhost:8080/
Displays a simple message: "Manticore Backend demo application".

Search Customer Page: http://localhost:8080/search/{search_term}
Perform search queries (e.g., http://localhost:8080/search/john).

Search Customer by ID: http://localhost:8080/customer/{customer_id}
Search for a specific customer by ID.
```

### Error Handling
The application handles different types of errors:

Missing Search Parameter: If the search term is missing or empty, a 400 error is returned with the message "Search parameter is required".

Invalid Customer ID Format: If the provided customer ID is not a number, a 400 error is returned with the message "Customer ID must be a number".

Action Not Found: If a user tries to access an invalid or non-existent route, a 404 error is returned with the message "Action not found".

General Errors: In case of any general issues, a 500 error is returned with the respective error message.

### Example Error Responses
Missing Search Parameter Error (400):

```json
{
    "success": false,
    "status": 400,
    "message": "Search parameter is required",
    "data": [],
    "error": {
        "message": "Search parameter is required",
        "code": 400
    }
}
```

Invalid Customer ID Format Error (400):

```json
{
    "success": false,
    "status": 400,
    "message": "Customer ID must be a number",
    "data": [],
    "error": {
        "message": "Customer ID must be a number",
        "code": 400
    }
}
```

Action Not Found Error (404):

```json
{
    "success": false,
    "status": 404,
    "message": "Action not found",
    "data": [],
    "error": {
        "message": "Action not found",
        "code": 404
    }
}
```

### License
This project is open-source and available under the MIT License.
