# Install

### Domain

[https://dev.local/api/docs](https://dev.local/api/docs)

### Docker install

```shell
docker-compose up -d
```

### Composer install

```shell
docker exec todo_api composer install
```

### migration

```shell
docker exec todo_api php ./bin/console d:m:m
```

### Load fixture

```shell
docker exec todo_api php ./bin/console d:f:l
```

File Structure
/volumes/api - Symfony backend API

Note
Since the specifications did not mention any libraries, it was decided to use [api-platform](https://api-platform.com), eliminating the need to write a service.
