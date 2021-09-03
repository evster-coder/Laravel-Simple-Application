<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Описание
В данном проекте раелизована небольшая система, включающая в себя админ-панель и API. Аутентификация в админ-панели реализована с использованием сессии, а в API - с использованием токенов.
Админ-панель построена на Vue.js и является SPA.

## Установка

Склонируйте проект 
<b>https://github.com/evster-coder/Laravel-Simple-Application.git</b>

В папке проекта выполните команду для установки пакетов Composer:<br>
<b>composer install</b>


Установите зависимости через npm и скомпилируйте frontend-часть<br>
<b>npm install&&npm run dev</b>

Создайте базу данных MySQL и внесите данные для подключения к ней в .env<br>
<b>DB_CONNECTION=mysql <br>
DB_HOST=127.0.0.1 <br>
DB_PORT=3306 <br>
DB_DATABASE=yourDataBase <br>
DB_USERNAME=yourCredentials <br>
DB_PASSWORD=yourCredentials<br> </b>

Выполните миграции баз данных и сгенерируйте admin-пользователя для системы<br>
<b>php artisan migrate<br>
php artisan db:seed<br></b>

Запустите сервер приложения<br>
<b>php artisan serve</b>

## Маршруты REST API
Для получения доступа к некоторым маршрутам по API предварительно нужно авторизоваться в системе

<table>
<tbody>
<tr>
	<td>
	<b>POST /api/login</b> - авторизация в системе. 
	</td>
	<td>В качестве параметров нужно указать username и password. В случае успеха в качестве ответа пользователь получит данные авторизованного пользователя, а также <b>token</b>, который нужно будет указывать для аутентификации в дальнейшем
	</td>
</tr>
<tr>
	<td><b>POST /api/logout</b> - выход из системы.</td>
	 <td>В заголовке необходимо передать Bearer Token, который был получен при авторизации. В случае успеха пользователь получит сообщение об этом, токены будут удалены из базы.
		</td>
</tr>
<tr>
	<td>
	<b>GET /api/books</b> - получение списка книг с именем автора. 
	</td>
	<td>Авторизация не обязательна. </td>
</tr>
<tr>
	<td>
	<b>GET /api/books/{id} </b> получение данных книги по id.
	</td>
	<td>Авторизация не обязательна. </td>
</tr>
<tr>
<td>
<b>PUT /api/books/{id}</b> - обновление данных книги
</td>
<td>
Необходима авторизация под автором книги. В заголовке необходимо передать token.
</td>
</tr>
<tr>
<td>
<b>DELETE /api/books/{id}</b> - удаление книги
</td>
<td>
Необходима авторизация под автором книги. В заголовке необходимо передать token.
</td>
</tr>
<tr>
<td>
<b>GET /api/authors</b> - получение списка авторов с указанием количества книг
</td>
<td>
Авторизация не обязательна
</td>
</tr>
<tr>
<td><b>GET /api/authors/{id} </b> - получение данных автора со списком книг</td>
<td>Авторизация не обязательна</td>
</tr>
<tr>
<td>
<b>PUT /api/authors/{id} </b> - обновление данных автора
</td>
<td>Авторизация под автором обязательна, можно редактировать только свои данные. В заголовке указывается token.</td>
</tr>
<tbody>
</table>

Кроме того, с префиксом admin имеются маршруты, которые необходимы для отправки данных на frontend.
