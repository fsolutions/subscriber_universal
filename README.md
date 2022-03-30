# Subscriber Universal Bot (@SubscriberUniversalBot) & User Dashboard
Subscriber Universal Bot helps people don't create junk in there messages list from channels in Telegram messenger. Bot allows to subscribe on channels and create a wall of messages in one place. 

Try it now in telegram @SubscriberUniversalBot.

Read project website: https://news-feeder-bot.are-u-happy.com/

## Subscriber easy workflow scheme
![SubscriberUniversal](https://user-images.githubusercontent.com/3332161/159100708-6f0c2b01-0661-4b07-8a48-909c26e542e3.png)

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
7. Installing python telethon watcher for channels and new messages, and other helper libs
```
> cd app/Bundles/telethonForwarder
> python3 -m pip install -r requirements.txt
```
8. Create .env file in telethonForwarder directory
9. Set telegram webhooks by visiting page: https://YOURS_WEBSITE.COM/webhook
10. Create bot & empty channel for telethon user -> you can write me for help fomichevms@gmail.com


---

Write me in Telegram: @fomichevms

E-mail: fomichevms@gmail.com
