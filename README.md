## Log service
---

<!--## Summary

- Dockerfile & Docker-compose setup with PHP8.1 and MySQL
- Symfony 5.4 installation with a /healthz endpoint and a test for it
- After the image is started the app will run on port 9002 on localhost. You can try the existing
  endpoint: http://localhost:9002/healthz
- The default database is called `database` and the username and password are `root` and `root`
  respectively
- Makefile with some basic commands -->
## Important Documents:
  - [Application Structure](./wiki/AppArch.md)
  - [How To Use The Application](./wiki/howToUse.md)

## Installation

```
  make run && make install && make migration
```

## Run commands inside the container

```
  make enter
```

## Run tests

```
  make test
```
