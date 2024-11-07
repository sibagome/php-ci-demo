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
    if [ -t 0 ]; then
        # TTYが利用可能な場合（通常の端末）
        winpty docker-compose exec db mysql -uuser -ppassword dev_db
    else
        # TTYが利用できない場合（スクリプトなど）
        docker-compose exec -T db mysql -uuser -ppassword dev_db
    fi
}

function test() {
    docker-compose exec app vendor/bin/phpunit
}

function shell() {
    winpty docker-compose exec app sh
}

function reset-db() {
    echo "Resetting database..."
    docker-compose exec -T db mysql -uroot -proot -e "DROP DATABASE IF EXISTS dev_db; CREATE DATABASE dev_db;"
    docker-compose exec -T db mysql -uuser -ppassword dev_db < docker/mysql/init/01_init.sql
    echo "Database reset completed"
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
    "reset-db")
        reset-db
        ;;
    *)
        echo "Usage: ./dev.sh [command]"
        echo "Commands:"
        echo "  start    - Start development environment"
        echo "  stop     - Stop development environment"
        echo "  status   - Show container status"
        echo "  mysql    - Connect to MySQL"
        echo "  test     - Run PHPUnit tests"
        echo "  shell    - Open shell in PHP container"
        echo "  reset-db - Reset and reinitialize database"
        ;;
esac
