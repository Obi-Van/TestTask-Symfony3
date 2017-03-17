# Test Application by Symfony 3

:star: Just download files, check requirements and follow few steps :star:

## Installation

  1. Setup the Symfony:

      ## Linux and macOS systems:

```bash
    sudo mkdir -p /usr/local/bin
    sudo curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony
    sudo chmod a+x /usr/local/bin/symfony
```
      ## Windows systems:

```bash
    php -r "readfile('https://symfony.com/installer');" > symfony
```


  2. Download files. And put them in the your project's folder.


  3. Open file app/config/parameters.yml, and setup values of parameters 
     relevant for your application:
    - database_name;
    - database_user;
    - database_password.

  4. Create the tables in your Database. Run:

```bash
    php bin/console schema:update --force
```

:star: That's it! :star: