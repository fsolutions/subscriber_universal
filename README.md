# Subscriber Universal Bot & User Dashboard

## Installation

1. Install backend packages
```
> composer install
```  
2. Installing frontend packages
```
> not allowed yet
```  
3. Laravel keys generation
```
> php artisan key:generate
> php artisan passport:install
```
4. Create empty database
5. Create .env file in root directory of project
6. Install migrations
```
> php artisan migrate
```
7. Installing python telethon watcher for channels and new messages
```
> cd app/Bundles/telethonForwarder
> python3 -m pip install -r requirements.txt
```
8. Create .env file in telethonForwarder directory
9. Set telegram webhooks by visiting page: https://YOURS_WEBSITE.COM/webhook
10. Create bot & empty channel for telethon user -> you can write me for helping fomichevms@gmail.com