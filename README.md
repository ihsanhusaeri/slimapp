# SlimApp RESTful API

This is a RESTful api built with the SlimPHP framework and uses MySQL for storage.

### Installation
Create database or import from _sql/slimapp.sql

Install SlimPHP and dependencies
```sh 
    composer update
```

### Run

```sh 
    cd public
    php -S localhost:8080 
```

### API Endpints
```sh
$ GET /getUser
$ GET /getListUser
$ GET /getCompany/{id:[0-9]+}
$ GET /getListCompany
$ POST /createUser
$ POST /createCompany
$ PUT /updateUser/{id:[0-9]+}
$ PUT /updateCompany/{id:[0-9]+}
$ DELETE /deleteUser/{id:[0-9]+}
$ DELETE /deleteCompany/{id:[0-9]+}
$ POST /reimburse
$ POST /disburse
$ POST /close
$ GET /getBudgetCompany/{id:[0-9]+}
$ GET /getListBudgetCompany
$ GET /getLogTransaction
```
