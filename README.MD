# Install

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

