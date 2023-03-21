# Running with docker
If you have Docker and docker compose installed, follow the below steps:  
1. Create docker network by copy/paste this command in your terminal:  
```bash
docker network create \
  --driver=bridge \
  --subnet=172.28.0.0/16 \
  --ip-range=172.28.5.0/24 \
  --gateway=172.28.5.254 \
  devnetwork
```  
2. Build container  
```bash
docker compose build
```  
3. Run container  
```bash
docker composer up -d
```  
4. Install dependencies
```bash
docker exec -it ssa-academy.local-172.28.0.63 composer install
``` 
5. Literally copy everything from `.env.example` to `.env`.
6. Migrate and seed  
```bash
docker exec -it ssa-academy.local-172.28.0.63 php artisan migrate
docker exec -it ssa-academy.local-172.28.0.63 php artisan db:seed
```  
7. SSH to docker container to install npm dependencies  
```bash
docker exec -it ssa-academy.local-172.28.0.63 bash
```  
8. Install npm dependencies
```bash
npm install
```  
9. Finally, run command below and you're good to go:  
```bash
npm run dev
```  

# Running tests
Run the command below in your terminal in order to run the tests.  
Make sure you have sqlite3 installed on your machine since.
```bash
./vendor/bin/phpunit

or

php artisan test
```

## Default Login username and password.
You may use the ready made account to login.  
```bash
email: john.doe@example.com
password: password
```