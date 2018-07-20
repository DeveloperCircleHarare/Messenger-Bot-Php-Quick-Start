![alt text](https://raw.githubusercontent.com/DeveloperCircleHarare/Messenger-Bot-Php-Quick-Start/master/images/banner.png)

# Messenger-Bot-Php-Quick-Start

A quick start guide to building a bot on the Messenger messaging platform.

## <a name="develop"></a> Develop

#### Deploy - Heroku
![Heroku](https://raw.githubusercontent.com/DeveloperCircleHarare/Messenger-Bot-Php-Quick-Start/master/images/heroku.png)

#### Language - Php
![Php](https://raw.githubusercontent.com/DeveloperCircleHarare/Messenger-Bot-Php-Quick-Start/master/images/php.png)

#### Data Persistence - PostgresSql
![Postgres](https://raw.githubusercontent.com/DeveloperCircleHarare/Messenger-Bot-Php-Quick-Start/master/images/postgresql_logo.png)

### Requirements
- A [Facebook Page](https://web.facebook.com/pages/create)
- A [Facebook App](https://developers.facebook.com/apps/) with the Facebook Messenger product
- [PHP 7.1+](http://php.net/downloads.php)
- [Composer](https://getcomposer.org/)
For Hosting
- [Heroku Account](https://signup.heroku.com/dc)
- [Heroku Cli](https://devcenter.heroku.com/articles/heroku-cli)
OR
- A local web-server e.g. [Apache](https://www.apache.org/dyn/closer.cgi)
- A publicly accessible URL e.g. [ngrok](https://ngrok.com/)

### First time

- Run `composer install`
- Visit localhost to confirm you see `index.php`
- Run `cp .env.example .env`
- Edit `.env`
- Run `phpunit`
- Run `ngrok http 80` to get the publicly accessible URL
- Go to your [Facebook App](https://developers.facebook.com/apps/)
- Click `Webhooks`
- Select `Page` from the drop-down
- Click `Subscribe to this topic`
- Enter the publicly accessible URL of your app and `/webhook.php`
- Enter the `FACEBOOK_VERIFY_TOKEN`
- Click `Verify and Save`
- Visit [Facebook Messenger](https://messenger.com)
- Search for your Facebook Page and send it a message

## <a name="deploy"></a> Deploy

You can deploy Sourcebot to your own web-server or quickly and for free to Heroku.

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy)

The latest release of Sourcebot is now supported! Changes include:

  * Requires PostgresSQL database, available through add-ons:
    * [Heroku-Postgresql](https://elements.heroku.com/addons/heroku-postgresql) (deploy default)
  * `HEROKU_URL` config var renamed to `PUBLIC_URL` to avoid using Heroku's namespace
  * `DATABASE_URL` config var will be set for you to access your database

## <a name="references"></a> References 
[Messenger Platform Getting Started](https://developers.facebook.com/docs/messenger-platform/getting-started/app-setup/)