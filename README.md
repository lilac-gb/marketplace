# Instruction to start project on local machine

### Clone git repo to your local machine

```bash
git clone git@github.com:lilac-gb/marketplace.git MarketPlace
```

### I think you install docker yet

Go to settings of docker and add this folder `MarketPlace` to share project, if you don't do this project will break on start

![img](https://image.prntscr.com/image/C5r_SEtQS5_XaMBe6tDtyQ.png)

### RUN command in this folder

```bash
docker-compose up     // Start with log in console
docker-compose up -d  // Start without log
docker-compose down   // Stop all containers
```
*After start you need to wait, because at first will be install vendor folder, run migrations, install npm packages and run build, install Kitematic for docker see logs or install plugin for storm Docker more you can do like this with containers:*

```bash
docker logs mp_php // mp_php name of container
```

### Add this to your hosts file, if after start you don't have it in

```bash
127.0.0.1 marketplace.docker
127.0.0.1 office.marketplace.docker
127.0.0.1 api.marketplace.docker
127.0.0.1 pma.marketplace.docker
```

### Generate seed data in db (OPTIONAL)
#####List of seed data items
1. 10 users
2. 200 publications

*!!script will erase all data from related tables!!*

### Make init migration and fill admin and menus in BD

```bash
docker exec -it mp_php /bin/bash                          // Login container
./yii user/generate-admin && ./yii menu/generate-default  // Generate admin and menu
exit                                                      // to logout from container
```

### Seed test items

```bash
docker exec -it mp_php /bin/bash
./yii seeder
exit
```

### Migration command with docker
```bash
docker exec -it mp_php /bin/bash      //Login container
./yii migrate/create <migration_name> //create migration
./yii migrate                         //run all migrations
```

### SSL Certificate attach for MAC Users

```bash
composer sert
```

### MySqlAdmin

```bash
https://pma.marketplace.docker/
Login: root
Pass: toor
```

### Login to admin area
```bash
https://office.marketplace.docker
Login: admin@marketplace.docker
Pass: admin
```

## !!! For windows users !!!

if you have errors with file init.sh to try fixed it, you need to install Notepad++

Then you need to convert /src/init.sh like 

`Edit > EOL Conversion > UNIX/OSX Format`

Then convert `environments/docker/yii` like this and `init` file

Or run: 

```bash
awk '{ sub("\r$", ""); print }' init.sh > init2.sh && mv init2.sh init.sh
awk '{ sub("\r$", ""); print }' init > init2 && mv init2 init
cd environments/docker && awk '{ sub("\r$", ""); print }' yii > yii2 && mv yii2 yii
```

## INFO

To recive from api request in JSON create URl like this 

`https://api.marketplace.docker/menu?_format=json` 

or send in headers 

`Accept:application/json`

## Start frontend on server 

```bash
pm2 start npm --name "nuxt" -- start
```