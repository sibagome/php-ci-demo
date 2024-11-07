#!/bin/bash
# setup.sh

# プロジェクト構造の作成
mkdir -p src tests docker/mysql/init public

# composer.jsonの作成
cat > composer.json << 'EOF'
{
    "name": "sibagome/php-ci-demo",
    "description": "PHP CI/CD Demo Project with MySQL",
    "type": "project",
    "require": {
        "php": "^8.1",
        "ext-pdo": "*",
        "ext-pdo_mysql": "*"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0",
        "phpstan/phpstan": "^1.10"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    }
}
EOF

# Nginxの設定ファイル
cat > docker/nginx.conf << 'EOF'
server {
    listen 80;
    index index.php index.html;
    server_name localhost;
    root /var/www/html/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
EOF

# サンプルデータベース初期化スクリプト
cat > docker/mysql/init/01_init.sql << 'EOF'
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email) VALUES
    ('Test User', 'test@example.com'),
    ('Demo User', 'demo@example.com');
EOF

# サンプルPHPファイル
cat > public/index.php << 'EOF'
<?php
require __DIR__.'/../vendor/autoload.php';

try {
    $dsn = "mysql:host=db;dbname=dev_db;charset=utf8mb4";
    $pdo = new PDO($dsn, 'user', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h1>Users List</h1>";
    echo "<pre>";
    print_r($users);
    echo "</pre>";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
EOF

# 開発環境管理スクリプト
cat > dev.sh << 'EOF'
#!/bin/bash

function start() {
    echo "Starting development environment..."
    docker-compose build
    docker-compose up -d
    echo "Waiting for database to be ready..."
    sleep 10
    docker-compose exec app composer install
    echo "Development environment is ready at http://localhost:8080"
}

function stop() {
    echo "Stopping development environment..."
    docker-compose down
    echo "Development environment stopped"
}

function status() {
    docker-compose ps
}

function mysql() {
    docker-compose exec db mysql -uuser -ppassword dev_db
}

function test() {
    docker-compose exec app vendor/bin/phpunit
}

function shell() {
    docker-compose exec app sh
}

case "$1" in
    "start")
        start
        ;;
    "stop")
        stop
        ;;
    "status")
        status
        ;;
    "mysql")
        mysql
        ;;
    "test")
        test
        ;;
    "shell")
        shell
        ;;
    *)
        echo "Usage: ./dev.sh [command]"
        echo "Commands:"
        echo "  start   - Start development environment"
        echo "  stop    - Stop development environment"
        echo "  status  - Show container status"
        echo "  mysql   - Connect to MySQL"
        echo "  test    - Run PHPUnit tests"
        echo "  shell   - Open shell in PHP container"
        ;;
esac
EOF

chmod +x dev.sh

echo "Setup completed! Use ./dev.sh to manage your development environment."
