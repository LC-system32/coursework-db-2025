<link rel="icon" href="'programming-olympiad/public/favicon.ico" type="image/x-icon">

## Система обліку результатів учнівської олімпіади з програмування
---

## 🏛️ Опис

**Репозиторій курсової роботи з дисципліни "Організація баз даних і знань"**  
**Тема:** Розробка інформаційної системи обліку результатів учнівських олімпіад з програмування  
**Мова реалізації:** SQL, HTML/Blade, JavaScript, PHP (Laravel), Node.js

---

## 🎯 Мета проєкту

Система призначена для:

- Збереження інформації про учасників, школи, олімпіади;
- Аналізу результатів за різними параметрами (бал, мова програмування, статус виконання);
- Виведення статистики за допомогою графіків (Chart.js);
- Роботи з базою даних через інтерфейс і API.

---

## ⚙️ Використані технології

| Компонент          | Технологія                           |
| ------------------ | ------------------------------------ |
| База даних         | PostgreSQL                           |
| Серверна логіка    | Node.js + Express + Sequelize        |
| API                | REST (JSON)                          |
| Клієнтська частина | Laravel Blade + Bootstrap + Chart.js |
| Формати даних      | SQL, JSON, HTML                      |

## 📁 Структура репозиторію

---

```
├───API
│   ├───.vs
│   │   ├───...
│   ├───node_modules
│   │   ├───...
│   ├───uploads
│   │   ├───...
│   ├───package.json
│   ├───package-lock.json
│   ├───server.js
│
├───programming-olympiad
│   ├───app
│   │   ├───...
│   ├───bootstrap
│   │   ├───...
│   ├───config
│   │   ├───...
│   ├───database
│   │   ├───...
│   ├───node_modules
│   │   ├───...
│   ├───public
│   │   ├───...
│   ├───resources
│   │   ├───...
│   ├───routes
│   │   ├───...
│   ├───storage
│   │   ├───...
│   ├───tests
│   │   ├───...
│   ├───vendor
│   │   ├───...
│   ├───.editorconfig
│   ├───.env
│   ├───.env.example
│   ├───.gitattributes
│   ├───.gitignore
│   ├───artisan
│   ├───composer
│   ├───composer.lock
│   ├───package
│   ├───package-lock
│   ├───phpunit.xml
│   ├───test
│   ├───vite.config.js
│
├───SQL scripts
│   ├───FUNCTION
│   │   ├───...
│   ├───SPECIAL QUERY FOR COURSEWORK
│   │   ├───...
│   ├───TRIGGER
│   │   ├───...
│   ├───VALIDATE FUNCTION
│   │   ├───...
│   └───CREATE TABLE.sql
│
├───ReadMe.md
├───БД_КС22_МартиненкоОС.docx
├───БД_КС22_МартиненкоОС.html
└───Тема.txt
```

---

## ⚙️ Встановлення

### ✅ Передумови

- **PHP 7.4+** (CLI + Apache)
- **Apache 2.4+** (встановлений вручну, не через XAMPP)
- **MySQL або MariaDB**
- **Node.js 18+ + npm**
- **Composer** (опційно)

---

### 📦 Крок 1: Клонувати репозиторій

```bash
git clone https://github.com/LC-system32/coursework-db-2025.git
cd coursework-db-2025
```

---

### 🧪 Крок 2: Імпорт бази даних

1. Створіть базу:

```sql
CREATE DATABASE coursework2025;
USE coursework2025;
```

2. Виконайте SQL-скрипти:

```
📁 SQL scripts/
├───CREATE TABLE.sql           ← спочатку цей
├───FUNCTION/                  ← потім ці
├───TRIGGER/
├───VALIDATE FUNCTION/
├───SPECIAL QUERY FOR COURSEWORK/
```

> 📌 Можна виконувати через MySQL CLI, phpMyAdmin або будь-який SQL GUI.

---

### ⚙️ Крок 3: Налаштування PHP-сайту

У файлі `functions.php` або `.env` вкажіть:

```php
$conn = new mysqli("localhost", "root", "your_password", "coursework2025");
```

---

### 🌐 Крок 4: Розгортання на Apache 2.4

1. **VirtualHost (httpd-vhosts.conf):**

```
<VirtualHost *:80>
    DocumentRoot "C:/path/to/programming-olympiad"
    ServerName coursework.local
    <Directory "C:/path/to/programming-olympiad">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

2. **hosts файл**:

```
127.0.0.1    coursework.local
```

3. **Перезапуск Apache**:

```bash
httpd -k restart
```

4. Відкрий:

```
http://coursework.local/students.php
```

---

### 🔌 Крок 5: Запуск API

1. Перейдіть у директорію:

```bash
cd ../API
```

2. Встановіть залежності:

```bash
npm install
```

3. Запустіть сервер:

```bash
node server.js
```

4. За замовчуванням сервер запускається на:

```
http://localhost:3000
```

Якщо хочете змінити порт — відкрий `server.js` і змініть `app.listen(...)`.

---

## 📌 Автор

LC-system32  
Курсова робота з дисципліни "Організація баз даних і знань"  
2025 рік

Контакти  
GitHub: [LC-system32](https://github.com/LC-system32)

---

## 📄 Ліцензія

Цей проєкт є навчальним і не призначений для комерційного використання.  
Усі права належать автору курсової роботи.