## Installation
```
git clone https://github.com/mickaelnambs/snackSf5
cd snackSf5
composer install
npm install
composer prepare
```

## Configuration
Créer un fichier `.env.local` : 
```dotenv
DATABASE_URL=mysql://root:root@127.0.0.1:3306/snack
```

## Démarer le serveur
```
symfony serve
npm run dev
```


