# GA-back
 Testwork backend (Symfony 5, Doctrine, SQLite)
 
 Очень простой бекэнд для тестового проекта
 
 ##Установка 
 #### сборка проекта
 `composer install`
 #### сгенерировать ssh ключи:
 
 ``` bash
 $ mkdir -p config/jwt
 $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
 $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
 ```
Passphrase по умолчанию `ebb4fd20f9eb524fe88ae291aca22e8a`

 База данных 
 `data.db`
 
 Файлы от пользователей сохраняются в папке `public/`