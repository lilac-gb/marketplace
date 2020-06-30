#Art Project

## Hosts

Next records should be added to `/etc/hosts`:
```bash
127.0.0.1   art.docker
127.0.0.1   api.art.docker
127.0.0.1   api.art.local
127.0.0.1   office.art.docker
127.0.0.1   pma.art.docker
127.0.0.1   3001.art.docker
```

## Setting up SSL certificate:

```bash
composer cert
```
## Generate Art content 

```bash
docker exec -it art_backend bash

./yii category/generate //add categories
```