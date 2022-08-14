## How To use this service

### Import log file
To import your log file you will need to run the follow command
```
php bin/console app:log-importer <your full path file>
```
example: `php bin/console app:log-importer /app/public/logs.txt `

### API
+ Endpoint: http://localhost:9002/count
+ method: GET
+ Parameters:
    - serviceNames: you can pass several service names comma separated. ex serviceNames=USER-SERVICE,INVOICE-SERVICE
    - statusCode
    - startDate

Example:

`curl --request GET \
  --url 'http://localhost:9002/count?=&=&serviceNames=USER-SERVICE&statusCode=201&endDate=2021-08-18%2010%3A33%3A59&startDate=2021-08-17%2009%3A21%3A53'`
