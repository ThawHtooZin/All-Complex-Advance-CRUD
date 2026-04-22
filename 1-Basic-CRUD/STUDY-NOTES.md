# Study notes: `1-Basic-CRUD` project

These notes are written for someone who already knows **basic PHP syntax** (variables, `if`, strings, arrays at a simple level) and knows that **PHP can be mixed inside HTML**. Everything else in this small project is explained **slowly**, in **separate chapters**, file by file.

---

## Chapter 0 — What this folder is trying to teach

**CRUD** is a common acronym in web programming:

| Letter | Meaning   | In this project |
|--------|-----------|-----------------|
| **C**  | Create    | `add.php` inserts a new row |
| **R**  | Read      | `index.php` lists all rows |
| **U**  | Update    | `update.php` changes an existing row |
| **D**  | Delete    | `delete.php` removes a row |

**Files you will read in this folder:**

| File | Role |
|------|------|
| `connect.php` | Opens a connection to the MySQL database (no HTML) |
| `index.php` | Home page: shows a table of users (Read) |
| `add.php` | Form + code to insert a user (Create) |
| `update.php` | Form + code to edit one user (Update) |
| `delete.php` | Short script that deletes one row (Delete) |
| `crud-projects-db.sql` | SQL script to create the database and table (run in phpMyAdmin or similar) |

**Typical flow for a visitor:**

1. Open `index.php` → see the list.
2. Click **Add** → `add.php` → fill form → submit → back to list.
3. Click **Update** on a row → `update.php?id=...` → change fields → submit → back to list.
4. Click **Delete** → `delete.php?id=...` → row removed → back to list.

---

## Chapter 1 — PHP inside HTML (mental model)

- A file whose name ends in **`.php`** is processed by the **PHP engine** on the server.
- When the server sees `<?php ... ?>`, it runs that code **first** (or when that part of the file is reached, depending on structure).
- **HTML** outside those tags is mostly sent to the browser **as-is** (like a normal web page).
- So one `.php` file can contain: **some PHP**, **some HTML**, **more PHP**, **more HTML**, and so on.

**`include` (preview):**  
`include 'connect.php';` means: “**paste the contents of `connect.php` here**.” So variables like `$pdo` defined in `connect.php` become available in the file that included it. Chapter 5 goes deeper on `add.php`.

---

## Chapter 2 — The database and `crud-projects-db.sql`

Before PHP can save users, MySQL needs a **database** and a **table**.

### What the SQL file defines (big picture)

The file `crud-projects-db.sql` is an export from **phpMyAdmin**. You do not need to memorize every line for the exam-style understanding of this PHP project. The **important** parts for your mental model:

1. **Database name:** `` `crud-projects-db` ``  
   This must match what `connect.php` uses (`MYSQL_DATABASE`).

2. **Table name:** `` `basic_crud` ``  
   This table stores each user as **one row** with columns:

   | Column     | Meaning |
   |------------|---------|
   | `id`       | Unique number for each row (primary key, auto-increment) |
   | `username` | Text |
   | `password` | Text (this demo stores plain text — real apps use hashing; you will learn that later) |
   | `email`    | Text |

3. **`CREATE TABLE`** — creates the empty structure.  
4. **`INSERT INTO`** — sample rows (optional demo data).  
5. **`PRIMARY KEY` / `AUTO_INCREMENT`** — MySQL automatically picks the next `id` when you insert without specifying `id`.

**Student takeaway:** PHP talks to **MySQL** using the table **`basic_crud`** inside the database **`crud-projects-db`**. If names do not match `connect.php`, the site will error.

---

## Chapter 3 — `connect.php` (line by line)

This file’s job: **create one database connection** and store it in **`$pdo`**. Other files `include` this file so they can reuse **`$pdo`**.

### Line 1: `<?php`

Starts a **PHP block**. The server switches from “send HTML” mode to “run PHP code” mode.

### Line 2

Empty line — only for human readability.

### Lines 3–6: `define(...)`

```php
define('MYSQL_USER','root');
define('MYSQL_PASSWORD','root');
define('MYSQL_HOST','localhost:8889');
define('MYSQL_DATABASE', 'crud-projects-db');
```

**What `define` does:**  
It creates a **constant**: a name that always refers to the same value in this script. By convention here, the names are in **ALL_CAPS**.

| Constant | Typical meaning in this MAMP-style setup |
|----------|------------------------------------------|
| `MYSQL_USER` | Database username (often `root` in local MAMP) |
| `MYSQL_PASSWORD` | Database password (often `root` in MAMP) |
| `MYSQL_HOST` | Where the database server lives; `localhost:8889` is a common **MAMP MySQL port** |
| `MYSQL_DATABASE` | Which database to use — must match the SQL file / phpMyAdmin |

**Why use constants?**  
So you change username/password/host/database **in one place** instead of in every page.

### Lines 8–10: `$options` array

```php
$options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);
```

- **`$options`** is a normal PHP **array**: a list of settings.
- **`PDO`** is PHP’s built-in **database layer** (PHP Data Objects).
- **`PDO::ATTR_ERRMODE`** — how PDO reports errors.
- **`PDO::ERRMODE_EXCEPTION`** — “when something goes wrong, **throw an exception**” (a stricter, clearer way to fail than silent errors).

**Student takeaway:** This line makes database mistakes **visible** during development.

### Lines 12–14: `new PDO(...)`

```php
$pdo = new PDO(
  'mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DATABASE,MYSQL_USER,MYSQL_PASSWORD,$options
  )
```

**Reading this slowly:**

1. **`new PDO(...)`** — creates a **new PDO object** (a “connection helper” to MySQL).
2. The **first argument** is a **DSN** (Data Source Name): a **string** that tells PDO:
   - driver: `mysql:`
   - host: value of `MYSQL_HOST`
   - database name: `dbname=` + `MYSQL_DATABASE`
3. The **`.` operator** **joins strings** in PHP:  
   `'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DATABASE`  
   builds one long string, for example:  
   `mysql:host=localhost:8889;dbname=crud-projects-db`
4. The **second argument** is the username (`MYSQL_USER`).
5. The **third argument** is the password (`MYSQL_PASSWORD`).
6. The **fourth argument** is the `$options` array.

The result is stored in **`$pdo`**. Every other script will use **`$pdo->prepare(...)`** etc.

The full **`$pdo = new PDO(...)`** assignment ends with a **semicolon** `;` after the closing `)` — in PHP, almost every complete statement must end that way.

### Line 16: `?>`

**Optional closing tag** for PHP. Many style guides omit it at the end of a **PHP-only** file to avoid accidental whitespace after `?>` breaking headers. Here it is harmless.

### What `connect.php` does *not* do

It does **not** show HTML. It only defines constants, sets options, and creates **`$pdo`**.

---

## Chapter 4 — SQL you will see in this project (mini glossary)

You do not need to be a SQL expert yet, but you should recognize **patterns**:

| SQL snippet | Plain English |
|-------------|----------------|
| `SELECT * FROM basic_crud` | “Give me **all columns** of **all rows** from table `basic_crud`.” |
| `SELECT * FROM basic_crud WHERE id = 5` | “Give me the row whose `id` is 5.” |
| `INSERT INTO basic_crud (username, password, email) VALUES (...)` | “Add a **new row** with these three values.” |
| `UPDATE basic_crud SET username='...', ... WHERE id=5` | “Change columns **for the row** where `id` is 5.” |
| `DELETE FROM basic_crud WHERE id=5` | “Remove the row where `id` is 5.” |

**`$stmt = $pdo->prepare("... SQL ...");`**  
- **`prepare`** — PDO gets the SQL **ready** to run (often used with **placeholders** in safer code).  
- In this beginner project, some queries **glue variables into the string**; that is easy to read but **unsafe** if malicious input is sent (SQL injection). Treat that as “**we will improve this in a later course**,” not as a pattern for production.

**`$stmt->execute();`**  
Actually runs the prepared statement.

**`fetchAll()` vs `fetch()`:**  
- **`fetchAll()`** — many rows → **array of rows** (used on the list page).  
- **`fetch(PDO::FETCH_ASSOC)`** — one row → **one associative array** like `['id'=>1,'username'=>'...',...]` (used on the update page).

---

## Chapter 5 — `add.php` (includes, forms, `$_POST`, insert)

This file is ideal for learning because it combines **include**, **HTML form**, and **PHP handling POST**.

### Line 1: `<?php include 'connect.php'; ?>`

Two ideas on one line:

1. **`include 'connect.php'`**  
   PHP **loads and runs** `connect.php` at this point. After it runs, **`$pdo` exists** in `add.php`.

2. **Why on the same line as `?>`**  
   The author immediately switches back to HTML on line 2. That is a style choice; you could also write:

   ```php
   <?php
   include 'connect.php';
   ?>
   ```

**Path:** `'connect.php'` means “file in the **same folder**.” If the file lived elsewhere, the path would change.

### Lines 2–9: normal HTML document start

- **`<!DOCTYPE html>`** — tells the browser “this is HTML5.”
- **`<html lang="en" dir="ltr">`** — language left-to-right English.
- **`<head>`** — metadata, title, **CSS link**.
- **Bootstrap CSS** (CDN link) — a **stylesheet** from the internet that makes buttons and forms look styled without writing much CSS yourself.

None of this is PHP; the browser receives it after PHP has already run the PHP parts.

### Lines 10–20: PHP block — “did the user submit the form?”

```php
<?php
if($_POST){
  ...
}
?>
```

#### What is `$_POST`?

- **`$_POST`** is a **superglobal**: a special **array** PHP fills with **form data** when the form uses **`method="post"`**.
- **`if($_POST)`** — in PHP, an **empty array** is treated as “falsy”; a **non-empty array** is “truthy.” So this means: “**if there is POST data** (usually because the form was submitted), run the code inside.”

**Caution for exams:** This is a **loose** check. A more explicit beginner-friendly check is `if ($_SERVER['REQUEST_METHOD'] === 'POST')` — you may see that in other tutorials.

#### Lines 12–14: reading fields from the form

```php
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
```

The **string keys** `'username'`, `'password'`, `'email'` must **match** the **`name="..."`** attributes on the `<input>` tags **below** in the HTML. That is how the browser knows which box goes into which key.

#### Lines 16–17: INSERT with PDO

```php
$stmt = $pdo->prepare("INSERT INTO basic_crud(username, password, email) VALUES('$username','$password','$email')");
$stmt->execute();
```

**Plain English:** “Insert one new row into `basic_crud` with these three column values.”

The variables **`$username`**, **`$password`**, **`$email`** are inserted into the SQL string using **single quotes** inside **double-quoted** SQL… wait: the SQL here uses **double quotes** on the outside in PHP: `"INSERT ..."`  
Inside that string, the parts like **`'$username'`** are built by PHP **variable interpolation** inside the outer **double-quoted** string. So `"VALUES('$username', ...)"` becomes `VALUES('alice', ...)` after PHP substitutes the variable values.

#### Line 18: echo JavaScript

```php
echo "<script>alert('Added Successfuly!'); window.location.href='index.php';</script>";
```

- **`echo`** sends text to the browser as part of the response.
- Here the text is a **`<script>`** tag:
  - **`alert(...)`** — popup message.
  - **`window.location.href='index.php'`** — browser navigates to the list page.

So after a successful insert, the user sees a popup and is sent back to **`index.php`**.

### Lines 21–41: HTML layout + form

#### Lines 21–26: Bootstrap “card” and header

A **container** centers content; **card** is a Bootstrap panel; **Back** links to `index.php`.

#### Lines 28–37: the `<form>` — this is what makes `$_POST` exist

```html
<form action="add.php" method="post">
```

| Attribute | Meaning |
|-----------|---------|
| **`action="add.php"`** | When submitted, send the data to **`add.php`** (this same file). |
| **`method="post"`** | Put fields in the **HTTP request body** (not visible in the URL). PHP receives them in **`$_POST`**. |

If you used **`method="get"`**, the same fields would appear in **`$_GET`** and in the URL query string — fine for search boxes, **not** for passwords.

#### Each input

```html
<input type="text" name="username" class="form-control" placeholder="Enter Username" required>
```

| Part | Meaning |
|------|---------|
| **`type="text"`** | Single-line text. |
| **`type="password"`** | Masked characters. |
| **`type="email"`** | Browser does simple email format checking. |
| **`name="username"`** | This is the **key** in **`$_POST['username']`**. |
| **`class="form-control"`** | Bootstrap styling. |
| **`required`** | Browser refuses submit if empty. |
| **`placeholder`** | Hint text inside the box. |

#### Submit button

```html
<button type="submit" class="btn btn-primary">Add</button>
```

**`type="submit"`** — clicking it **submits** the form to **`action`** with **`method`**.

### Order of execution when you visit `add.php` the first time (GET)

1. `include connect.php` → **`$pdo`** ready.
2. `if($_POST)` → usually **false** (no body yet) → skip insert.
3. Browser receives HTML with **empty form**.

### Order when you submit the form (POST)

1. `include connect.php`
2. `if($_POST)` → **true**
3. Read **`$_POST`**, run **INSERT**, **`echo`** script → redirect to **`index.php`**

---

## Chapter 6 — `index.php` (list / Read)

### Line 1

Same as `add.php`: **`include 'connect.php'`** so **`$pdo`** exists.

### Lines 10–14: fetch all rows

```php
$stmt = $pdo->prepare("SELECT * FROM basic_crud");
$stmt->execute();
$datas = $stmt->fetchall();
```

- **`SELECT *`** — all columns.
- **`fetchall()`** — returns an array of rows. Each row can be accessed like **`$data['username']`** (associative array keys matching column names).

**Note:** PHP’s canonical method name is **`fetchAll()`** (capital **A**). PHP function names are **case-insensitive** for many built-ins, so **`fetchall()`** may still work — your course might prefer the documented spelling **`fetchAll()`**.

### Lines 33–48: `foreach` to print table rows

```php
foreach ($datas as $data) {
  ?>
  <tr>...</tr>
  <?php
}
```

**Pattern:** “**For each** row in **`$datas`**, temporarily call it **`$data`**, then output one **`<tr>`**.”

Inside the row:

```php
<td><?php echo $data['id']; ?></td>
```

**Short idea:** “**Echo**” prints into the HTML stream at that spot.

**Update link:**

```php
<a href="update.php?id=<?php echo $data['id']; ?>" ...>
```

- **`?id=5`** is a **query string**: passes **`id`** to **`update.php`** via **`$_GET`**.

**Delete link:**

```php
<a href="delete.php?id=<?php echo $data['id']; ?>" ...>
```

Same idea: **`delete.php`** receives **`$_GET['id']`**.

---

## Chapter 7 — `update.php` (Read one row + Update with POST)

### Line 1

Include **`connect.php`**.

### Line 11: `$_GET['id']`

```php
$id = $_GET['id'];
```

When you open `update.php?id=5`, PHP fills **`$_GET`** with **`['id' => '5']`**. So **`$id`** becomes which record you are editing.

### Lines 12–20: if POST, run UPDATE

Same **`if($_POST)`** idea as **`add.php`**, but SQL is **`UPDATE ... SET ... WHERE id=$id`**.

**Important behavior in this file:**  
The **SELECT** that loads the form (lines 22–26) runs **after** the POST block. So after you submit, it **updates** first, then **reads** the row again (now with new values) before rendering HTML — as long as **`$id`** is still valid.

### Lines 22–26: load one row for the form

```php
$stmt = $pdo->prepare("SELECT * FROM basic_crud WHERE id=$id");
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
```

- **`WHERE id=$id`** limits to one row.
- **`fetch(PDO::FETCH_ASSOC)`** — one associative array, e.g. **`$data['username']`**.

### Lines 34–42: form differences vs `add.php`

```html
<form action="" method="post">
```

**`action=""`** means “submit to **the same URL**,” including the **`?id=...`** part. So **`$_GET['id']`** is still available on POST in this setup.

**Pre-filled values:**

```html
value="<?php echo $data['username']; ?>"
```

So the user sees current data before editing.

---

## Chapter 8 — `delete.php` (Delete)

Entire script is PHP logic + redirect — no big HTML page.

```php
<?php
include 'connect.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM basic_crud WHERE id=$id");
$stmt->execute();
echo "<script>alert('Deleted Successfully!'); window.location.href='index.php';</script>";
?>
```

**Flow:**

1. **`include`** → **`$pdo`**
2. Read **`id`** from URL (**`$_GET`**)
3. **`DELETE`** that row
4. Alert + go to **`index.php`**

**UX note:** There is **no “Are you sure?”** in PHP here — one click deletes. Real apps often add confirmation.

---

## Chapter 9 — How the files depend on each other (diagram)

```text
crud-projects-db.sql  →  creates DB + table `basic_crud`
         ↑
   connect.php  (defines $pdo)
         ↑
    included by
         │
   index.php ──links──► add.php
      │                    │
      │                    └── POST back to self → INSERT
      │
      ├──links──► update.php?id=… ──POST──► UPDATE
      │
      └──links──► delete.php?id=… ────────► DELETE
```

---

## Chapter 10 — Glossary (quick reference)

| Term | Meaning |
|------|---------|
| **Server** | Runs PHP and talks to MySQL; sends HTML to browser |
| **Browser** | Shows HTML, runs JS (like `alert`), submits forms |
| **`include`** | Reuse another PHP file’s code in place |
| **`$pdo`** | Database connection object from PDO |
| **`prepare` / `execute`** | Two-step run of SQL |
| **`$_GET`** | Data from URL query string (`?id=5`) |
| **`$_POST`** | Data from form with `method="post"` |
| **`echo`** | Output text/HTML/JS into the response |
| **`foreach`** | Loop over each item in an array |
| **Bootstrap** | CSS framework for quick layout |

---

## Chapter 11 — Study checklist (can you explain these out loud?)

1. Why does almost every page start with **`include 'connect.php'`**?  
2. What creates the keys inside **`$_POST`** on **`add.php`**?  
3. Why does **`update.php`** need **`$_GET['id']`** in addition to **`$_POST`**?  
4. What is the difference between **`SELECT *`** on **`index.php`** and **`SELECT ... WHERE id=...`** on **`update.php`**?  
5. What happens in the browser when **`echo "<script>... window.location.href=..."` runs?

If you can answer those in your own words, you understand this project’s “story” end to end.

---

*End of study notes for `1-Basic-CRUD`.*
