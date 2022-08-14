## Application structure
The service designed to be separate of the framework, to can easliy to switch or upgreate the framework without tight coupled.
The follow is the directory structure of our service:
```
├── ApISchema
├── Command
├── Controller
├── Repositories
└── Services
    └── log-analyzer
        ├── src
        │   ├── Application
        │   │   ├── Command
        │   │   └── Query
        │   ├── Exceptions
        │   ├── Infrastructure
        │   │   ├── Adapters
        │   │   ├── Entities
        │   │   └── Repositories
        │   └── Libraries
        │       ├── RepositoriesInterfaces
        └── tests
            ├── Application
            │   ├── Command
            │   └── Query
            └── Libraries
```
I will describe each folder and its role in the app

**ApISchema:** Here will locate Schema api. I didn't use any thired party to build the api schema for simplicity.

**Repositories:** the main repositories directory, here where I built the base repository `BaseRepository.php` that will use in our service.

**Services:** Here will locate all feature services, for now we have one service which is `log-analyzer`.
- **log-analyzer:** the service consists of `src` & `tests`
    - `src` consists of
        - `Application`: this is the application service that we going to use in the API, CLI, or any client service
            - the application have two directories `Command` where we will build any action that the applicatin will perform, and `Query` where will locate any query or any kind of retriving data.
        - `Exceptions`: here will locate exceptions our service.
        - `Infrastructure`: consists of `Repositories` where the repo implimentation will be located, and `Adapters` here will built Adapters for the services that we dont want to depend on directly, and `Entities` our service entities.
        - `Libraries`: here will locate service logic and `RepositoriesInterfaces` the repositories interfaces that the logic will depened on instead of the repo implementation.

## Database structure
```
            Table name: log_file
            +-------------+--------------------------------------+------+-----+---------+----------------+
            | Field       | Type                                 | Null | Key | Default | Extra          |
            +-------------+--------------------------------------+------+-----+---------+----------------+
    ├──-PK- | id          | int                                  | NO   | PRI | NULL    | auto_increment |
    |       | unique_name | char(32)                             | NO   |     | NULL    |                |
    |       | file_name   | varchar(255)                         | NO   |     | NULL    |                |
    |       | status      | enum('in-progress','stopped','done') | NO   |     | NULL    |                |
    |       | total_lines | int                                  | NO   |     | NULL    |                |
    |       | last_line   | int                                  | NO   |     | 0       |                |
    |       | created_at  | datetime                             | NO   |     | NULL    |                |
    |       | updated_at  | datetime                             | YES  |     | NULL    |                |
    |       +-------------+--------------------------------------+------+-----+---------+----------------+
    |
    |       Table name: transaction_log
    |       +--------------+-------------------------------------------+------+-----+---------+----------------+
    |       | Field        | Type                                      | Null | Key | Default | Extra          |
    |       +--------------+-------------------------------------------+------+-----+---------+----------------+
    |       | id           | int                                       | NO   | PRI | NULL    | auto_increment |
    |       | line_num     | int                                       | NO   |     | NULL    |                |
    └──-FK- | log_file_id  | int                                       | NO   | MUL | NULL    |                |
            | service_name | varchar(30)                               | NO   | MUL | NULL    |                |
            | endpoint     | varchar(255)                              | NO   |     | NULL    |                |
            | method       | enum('POST','GET','PUT','DELETE','PATCH') | NO   | MUL | NULL    |                |
            | status_code  | int                                       | NO   | MUL | NULL    |                |
            | http_version | varchar(30)                               | YES  |     | NULL    |                |
            | log_date     | datetime                                  | NO   |     | NULL    |                |
            | created_at   | datetime                                  | NO   |     | NULL    |                |
            +--------------+-------------------------------------------+------+-----+---------+----------------+
```
