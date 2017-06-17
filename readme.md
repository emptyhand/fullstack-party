# Testio

## Fullstack developer task

After login you can see all your assigned issues (from different repos)

**Technologies where used:**
* PHP 7.1.3
* Symfony framework
* Webpack (SCSS)
* hwi/oauth-bundle - Github API Oauth authentication
* knplabs/github-api - getting data from Github

## How to start

**Clone repository to your server**
```
git clone https://github.com/emptyhand/fullstack-party
```

**Install PHP dependencies**
```
composer install
```
**Create Github OAuth application and add credentials in parameters.yml**
```
app.oauth_github_client_id: 'your app client id'
app.oauth_github_client_secret: 'your app client secret'
```

**Install frontend libraries**
```
npm install
```
**Compile frontend libraries**
```
npm start
```
**Start webpack development server**
```
npm run dev
```
