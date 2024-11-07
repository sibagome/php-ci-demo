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
