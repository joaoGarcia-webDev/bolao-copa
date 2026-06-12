# Bolão da Copa — Guia de Implantação

## Requisitos

- PHP 8.1+
- MySQL 8.x
- Apache 2.4+ com `mod_rewrite`
- Composer

---

## Localhost (Windows / XAMPP / Laragon)

### 1. Configurar o banco

```sql
CREATE DATABASE bolao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Ou importe o script:

```bash
mysql -u root < app/Database/SQL/bolao.sql
```

### 2. Configurar ambiente

O arquivo `.env` já está configurado para:

| Parâmetro | Valor |
|-----------|-------|
| baseURL | `http://localhost/bolao-copa/public/` |
| database | `bolao` |
| username | `root` |
| password | *(vazio)* |

Ajuste `app.baseURL` se o caminho for diferente.

### 3. Instalar dependências e migrar

```bash
cd bolao-copa
composer install
php spark migrate
php spark db:seed DatabaseSeeder
```

### 4. Permissões (writable)

Garanta que `writable/` tenha permissão de escrita (cache, logs, session).

### 5. Apache — Virtual Host (opcional)

```apache
<VirtualHost *:80>
    ServerName bolao.local
    DocumentRoot "C:/Users/seu_usuario/Desktop/bolao_copa/bolao-copa/public"
    <Directory "C:/Users/seu_usuario/Desktop/bolao_copa/bolao-copa/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 6. Acessar

| Área | URL |
|------|-----|
| Pública | http://localhost/bolao-copa/public/ |
| Admin | http://localhost/bolao-copa/public/admin/login |

**Credenciais padrão:** `admin` / `admin123`

---

## Ubuntu Server (Produção)

### 1. Instalar pacotes

```bash
sudo apt update
sudo apt install apache2 mysql-server php8.2 php8.2-mysql php8.2-mbstring php8.2-intl php8.2-xml php8.2-curl libapache2-mod-php8.2 composer
sudo a2enmod rewrite
```

### 2. Publicar aplicação

```bash
sudo mkdir -p /var/www/bolao-copa
sudo cp -r bolao-copa/* /var/www/bolao-copa/
cd /var/www/bolao-copa
composer install --no-dev --optimize-autoloader
```

### 3. Configurar .env

```bash
cp env.example .env
nano .env
```

Defina:
- `CI_ENVIRONMENT = production`
- `app.baseURL = 'https://bolao.suaempresa.local/'`
- Credenciais MySQL de produção
- `encryption.key` gerada com `php spark key:generate`

### 4. Banco de dados

```bash
mysql -u root -p < app/Database/SQL/bolao.sql
php spark migrate
php spark db:seed AdminSeeder
```

### 5. Permissões

```bash
sudo chown -R www-data:www-data /var/www/bolao-copa/writable
sudo chmod -R 775 /var/www/bolao-copa/writable
```

### 6. Virtual Host Apache

```apache
<VirtualHost *:80>
    ServerName bolao.suaempresa.local
    DocumentRoot /var/www/bolao-copa/public

    <Directory /var/www/bolao-copa/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/bolao-error.log
    CustomLog ${APACHE_LOG_DIR}/bolao-access.log combined
</VirtualHost>
```

```bash
sudo a2ensite bolao-copa.conf
sudo systemctl reload apache2
```

### 7. Segurança em produção

- Alterar senha do administrador padrão
- Usar HTTPS (`app.forceGlobalSecureRequests = true`)
- Restringir acesso à rede interna via firewall
- Não expor `writable/` e `app/` na web

---

## Dados de Teste (Seeders)

O `DatabaseSeeder` cria:

- 1 administrador (`admin` / `admin123`)
- 4 jogos com datas futuras
- 4 palpites de exemplo (RE001 aposta em 2 jogos diferentes — válido)

```bash
php spark db:seed DatabaseSeeder
```

---

## Comandos Úteis

```bash
php spark routes          # Listar rotas
php spark migrate         # Executar migrations
php spark migrate:rollback # Reverter última migration
php spark db:seed DatabaseSeeder
```
