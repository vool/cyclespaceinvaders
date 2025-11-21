## Cycle Space Invaders

~~[cyclespaceinvaders.com](http://cyclespaceinvaders.com)~~

A strange rich man bought Twitter and shut down access to the API so this no longer works, plus the type of people how care about parking in cycle lanes were probally the first to jump ship.

You can check out a static copy as it was on 21/11/2025 here -> [cyclespaceinvaders.vool.ie](//cyclespaceinvaders.vool.ie)

### Instalation

```bash

git clone git@github.com:vool/cyclespaceinvaders.git

cd cyclespaceinvaders

composer install

yarn install

yarn build

```

### Configure

Add db and Twitter auth settings to `.env`

### Commands

##### Initialise the database

`/actions/init`

##### Bulk import tweets

`/actions/import`

##### Get new tweets

`/actions/update`

##### Update user info

`/actions/update-players`  
