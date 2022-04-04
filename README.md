# hypnos-api

## API pour servir une application React :  
## HYPNOS  
### Gestion de reservation d'une chaine hoteliere
[Link to API documentation](https://hypnos-back.herokuapp.com/api/docs)  

[Link to react app HYPNOS](https://hypnos-fr.herokuapp.com/)

## Getting Started to deploy it locally

### First , in your project folder :

```
git clone github.com/jpLedos/hypnos-api yourProject
cd yourProject
composer install --ignore-platform-reqs
```

### Second step, configure your environnement :
```
APP_ENV=dev
APP_SECRET=xxxxxxxxx 
DATABASE_URL="mysql://root@127.0.0.1:3306/localDatabase"
```


### run the development server:

```bash
symfony server:start 
or
symfony server:start -d
```

### to have fixture,  restaure mySQL backup hypnos.sql
an admin user will be created (login : admin@hypnos.com  password : 1111 )
* replace **yourDatabase**  by your local database name in hypnos.sql (lines 22 & 24)
```
mysql --host=127.0.0.1:3306 --user=root localDatabase < hypnos.sql
```
### login with admin identification :

## On postman : 
post to https://127.0.0.1:8000/api/login  ": post
{
    "email":"admin@hypnos.com",
    "password":"1111"
}

you will receive a JWT. 
You can login on ther api with the APIKey
bearer receivedtoken

## Go and test front application
[Link to react app HYPNOS](https://hypnos-fr.herokuapp.com/)  
[Link GITHUB to react front-end application](https://github.com/jpLedos/hypnos)


## Getting Started to deploy it in heroku

1.create a new app on heroku  
2.add resource : JawsDB MySQL  
3.restaure sql Backup hypnos.sql to your  JawsDB MySQL  
4.Connect your github repository  
5.Deploy branch master  




