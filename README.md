# Blog (test task)

Блог на чистом PHP 8.1 — без фреймворков. Данные в MySQL 8.0 (PDO, prepared statements),
рендер через Smarty 5, стили пишутся в SCSS и компилируются в CSS через `scssphp`,
картинки хранятся и раздаются через Flysystem. Окружение — Docker: nginx + php-fpm + mysql.

Реализованы три страницы: главная (категории с последними статьями), страница категории
(список статей с сортировкой и пагинацией) и страница статьи (полная информация + похожие статьи).

---

# Архитектура

## Поток запроса

```
public/index.php
  └── App\App::server()
        └── App\Route::getController()      // URI → контроллер
              └── Controller::handle($_REQUEST)
                    ├── App\Repositories\*  // SQL → App\Entities\*
                    ├── App\Services\*      // картинки, публичные URL
                    └── App\View (Smarty)   // .tpl → HTML
```

`App\App::__construct()` работает как композиционный корень: создаёт `Database`, `Filesystem`,
репозитории, сервисы, контроллеры и передаёт их в `Route`.

## Структура проекта

```
app/
  App.php                  — сборка зависимостей + CLI-операции (fresh, seeder, compilerCSS)
  Route.php                — роутер по точному совпадению пути, '*' → ErrorController
  Database.php             — обёртка над PDO: query, queryOne, insertOne, execute
  View.php                 — наследник Smarty\Smarty с настроенными template/compile/cache dir
  Controller/              — Controller (abstract) + ControllerInterface,
                             HomeController, CategoryController, ArticleController, ErrorController
  Entities/                — Article, Category, Image (простые объекты с геттерами/сеттерами)
  Repositories/            — доступ к БД и маппинг строк в сущности
                             (ArticleRepository, CategoryRepository, ImageRepository)
  Services/ImageService.php— создание/загрузка файлов картинок, выдача публичных URL
public/
  index.php                — единая точка входа (front controller)
  css/style.css            — результат компиляции SCSS
  assets/                  — файлы изображений
resources/
  scss/style.scss          — исходные стили
  smarty/template/         — шаблоны: layout/, home.tpl, category.tpl, article.tpl, error_404.tpl
  smarty/compile/, cache/  — служебные директории Smarty
docker/
  mysql/init.sql           — схема БД (images, categories, articles)
  nginx/templates/         — конфиг nginx (fastcgi_pass app:9000)
setup.php                  — CLI-скрипт: fresh + seeder + компиляция SCSS
bootstrap.php              — определяет __ROOT__ и подключает автозагрузчик composer
```

## Маршруты

| URI                                          | Контроллер           | Шаблон          |
|----------------------------------------------|----------------------|-----------------|
| `/`                                          | `HomeController`     | `home.tpl`      |
| `/category?id=&sort=&order=&page=`           | `CategoryController` | `category.tpl`  |
| `/article?id=`                               | `ArticleController`  | `article.tpl`   |
| всё остальное                                | `ErrorController`    | `error_404.tpl` |

Параметры страницы категории: `sort` — `created_at` или `view_count` (whitelist),
`order` — `ASC`/`DESC`, `page` — номер страницы (по 6 статей).

## Схема БД

```
images     (id, path, created_at)
categories (id, name, description, created_at)
articles   (id, name, text, category_id, description, view_count, image_id, created_at, updated_at)
```

---

# Установка и запуск (Docker)

```bash
cp .env.example .env                        # при необходимости поправить UGID: id -u : id -g
docker compose build
docker compose up -d                        # nginx :8000, app (php-fpm), mysql 8.0
docker compose exec app composer install
docker compose exec app composer setup      # очистка БД + сидинг + компиляция SCSS
```

Открыть: http://localhost:8000

## Seed

`composer setup` запускает `php setup.php`, который последовательно вызывает:

1. `App::fresh()` — `TRUNCATE` таблиц `articles`, `categories`, `images`;
2. `App::seeder()` — 15 категорий × 19 статей через Faker, каждой статье создаётся картинка-заглушка в `public/assets/`;
3. `App::compilerCSS()` — `resources/scss/style.scss` → `public/css/style.css`.

Сидер можно перезапускать сколько угодно раз — он каждый раз начинает с чистой БД.

## Про схему БД

`docker/mysql/init.sql` применяется **только при первой** инициализации тома `db_data`.
Если файл изменился, том нужно пересоздать:

```bash
docker compose down -v && docker compose up -d
```

## Переменные окружения (`.env`)

| Переменная            | Назначение                                        |
|-----------------------|---------------------------------------------------|
| `UGID`                | uid:gid пользователя контейнера `app`             |
| `MYSQL_ROOT_PASSWORD` | пароль root в MySQL                               |
| `MYSQL_DATABASE`      | имя базы                                          |
| `MYSQL_USER`          | пользователь приложения                           |
| `MYSQL_PASSWORD`      | пароль пользователя                               |
| `MYSQL_HOST`          | хост БД (имя сервиса — `mysql`)                   |
| `MYSQL_PORT`          | порт БД                                           |
| `ASSETS_URL`          | базовый URL для публичных ссылок на изображения   |

---

# Итог

## Что сделано

- MVC-скелет без фреймворка: единая точка входа, роутер, контроллеры, слой View.
- Слой данных: `Repository` + `Entity`, весь SQL — через prepared statements PDO.
- Smarty-шаблоны с наследованием общего layout (`header` / `footer` / `content`).
- Главная страница: категории и по 3 последних статьи в каждой, кнопка «Все статьи».
- Страница категории: название, описание, список статей, сортировка по дате и просмотрам
  (ASC/DESC, колонки через whitelist), пагинация.
- Страница статьи: полная информация, инкремент счётчика просмотров, блок из 3 похожих статей.
- Страница 404 для неизвестных маршрутов.
- Сидинг категорий и статей на Faker + генерация изображений.
- Работа с файлами через Flysystem, публичные URL из `ASSETS_URL`.
- Стили на SCSS с компиляцией в CSS (`scssphp`).
- Docker-окружение: nginx + php-fpm + mysql, автоинициализация схемы БД.

## Про использование AI

AI использовался в двух местах:

- **вёрстка** — HTML-разметка Smarty-шаблонов и SCSS-стили;
- **оформление этого README** — описание проекта, архитектуры и инструкции по запуску.

> [!CAUTION]
> <h4 style="color: red"> Вся остальная часть написана самостоятельно </h4>
> архитектура и структура проекта, роутинг, 
> контроллеры, сущности и репозитории, 
> работа с MySQL и SQL-запросы, сервис изображений,
> сидер, Docker-окружение.

---

# План оптимизации и доработок

## Запланированное

- [ ] **Middleware** — хук в `Route` (сейчас там стоит `// TODO middleware`) для сквозной логики:
      логирование, обработка исключений, заголовки ответа.
- [ ] **Валидация запроса** — вынести разбор `$_REQUEST` из контроллеров в отдельный слой
      (`Request` + валидатор). Сейчас `(int)($request['id'] ?? 0)` и whitelist сортировки
      размазаны по `CategoryController` и `ArticleController`.
- [ ] **DTO / Response-слой** — вместо ручных массивов `['article' => ..., 'image_url' => ...]`,
      которые собираются в каждом контроллере (в `HomeController` уже стоит соответствующий TODO).
- [ ] **Корректная загрузка связей** — `ImageService::getUrlById()` делает отдельный SELECT
      на каждую статью (N+1: на главной ~9+ запросов, на странице категории — по одному на карточку).
      Решение: batch-загрузка `ImageRepository::getByIds()` или JOIN в запросе статей.
- [ ] **Пагинация в базовый контроллер** — расчёт `totalPages`, клампинг `page` и offset
      сейчас лежат прямо в `CategoryController::handle()`. Вынести в `Controller` или в `Paginator`,
      чтобы переиспользовать в других списках.
- [ ] **HTTP-коды** — 404-страница сейчас отдаётся со статусом `200`. Добавить `http_response_code(404)`
      в `ErrorController` и в ветки «не найдено» у `CategoryController` / `ArticleController`,
      явный `200` для успешных ответов.

- [ ] **Связь many-to-many** — по ТЗ у статьи «категория — одна или несколько», а в схеме только
      `category_id`. Нужна связующая таблица `article_category`.
- [ ] **Индексы и внешние ключи** в `init.sql`: индексы на `articles.category_id`, `created_at`,
      `view_count` (используются в WHERE/ORDER BY), FK на `category_id` и `image_id`.
- [ ] **Инкремент просмотров** — `ArticleRepository::save()` делает полный UPDATE + SELECT ради `+1`.
      Заменить на атомарный `UPDATE articles SET view_count = view_count + 1 WHERE id = ?`.
- [ ] **Разделение `App`** — сейчас это одновременно DI-контейнер и CLI-команды (`fresh`, `seeder`,
      `compilerCSS`). Разнести на контейнер и консольные команды; полноценные миграции
      вместо разового `init.sql`.
- [ ] **Роутинг** — сейчас только точное совпадение пути, id передаётся в query-строке.
      Добавить параметры в URL (ЧПУ `/category/{id}`) и разбор HTTP-методов.
- [ ] **Экранирование вывода** — включить `$escape_html = true` в `View` (защита от XSS
      в названиях, описаниях и тексте статей).
- [ ] **Кеширование** — Smarty-кеш выключен (`CACHING_OFF`), включить для главной и страниц категорий.
- [ ] **Тесты** (PHPUnit) на репозитории и контроллеры — сейчас их нет.
- [ ] **`prod`-таргет** в `Dockerfile` (пока `# TODO`) и `.dockerignore`.

---

# Требования

Необходимо разработать простой, но полностью рабочий веб-сайт на чистом PHP (без фреймворков) с использованием MySQL и шаблонизатора Smarty. Сайт должен реализовывать функционал блога с категориями и постами.

## Технологический стек:
- [x] PHP 8.1+
- [x] Шаблонизатор Smarty
- [x] База данных MySQL
- [x] Не использовать фреймворки

## Структура данных:

### Категория:
- [x] Название
- [x] Описание

### Статья:

<img src="design.png" width="300"  alt="design">

- [x] Изображение
- [X] Название
- [X] Описание
- [x] Текст
- [x] Категория (одна или несколько)
- [x] Кол-во просмотров

## Обязательные страницы:

### Главная страница:

- [x] Вывести каждую категорию, в которой есть статьи и отобразить 3 последних поста (по дате публикации).
- [x] Вывести кнопку “Все статьи” для каждой категории.

### Страница категории:

- [x] Вывести название, описание, список статей
- [x] Реализовать сортировку статей (по кол-во просмотров, по дате публикации)
- [x] Реализовать пагинацию

### Страница статьи:

- [x] Вывести всю информацию о статье
- [x] Вывести блок из 3 похожих статей

### Дополнительный функционал:

- [x] Реализовать функционал для сидинга категорий и статей 


### Что будет оцениваться:

- [ ] Простота, читаемость и структура кода
- [ ] Структура проекта
- [ ] Работа с MySQL
- [ ] Уровень самостоятельной реализации
- [ ] Глубина понимания решения

### Будет плюсом:

- [x] Использование SCSS для стилей
- [x] Docker-окружение

---

# Plan

- Init
  - [x] docker
- Реализация
  - [x] init MVC
  - [x] ORM entity & repo
  - [x] migration & seeder
  - [x] view pages
  - [x] image load & seed
  - [x] layout & scss
- итог
  - [x] README.md

---