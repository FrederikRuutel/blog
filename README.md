# Blog

A Laravel 13 learning project for MM24. The home page follows the teacher's MM24 blog: a Dracula theme, shared navigation, post cards and pagination. The original Tailwind-only Bootstrap primary button exercise is preserved at `/buttons`.

## Requirements

- Git and Visual Studio Code.
- PHP **8.4.1 or newer** (PHP 8.5 recommended). Although Laravel itself allows PHP 8.3, the packages in this project's `composer.lock` require a newer version.
- Composer 2.
- Bun 1.x.
- Node.js 22.12+ or a newer supported LTS release, used by the Vite tooling.
- SQLite support in PHP. No separate database server is required.

The instructions below use **Windows PowerShell**. Run each code block in order. After installing programs, close and reopen your terminal and VS Code so they can find the new PATH entries. On macOS or Linux, install the same tools using their official installers, then follow the project setup steps below; the project commands work in both PowerShell and Bash.

## 1. Install Git, VS Code, PHP and Node.js

Open PowerShell and run:

```powershell
winget install --exact --id Git.Git
winget install --exact --id Microsoft.VisualStudioCode
winget install --exact --id PHP.PHP.8.5
winget install --exact --id OpenJS.NodeJS.LTS
```

If `winget` is unavailable, install Microsoft's App Installer from the Microsoft Store, or use the official downloads: [Git](https://git-scm.com/downloads), [VS Code](https://code.visualstudio.com/download), [PHP](https://www.php.net/downloads.php) and [Node.js](https://nodejs.org/en/download).

Reopen PowerShell and check:

```powershell
git --version
code --version
php -v
node --version
```

If PHP reports a missing Visual C++ runtime, install the [Microsoft Visual C++ Redistributable](https://learn.microsoft.com/en-us/cpp/windows/latest-supported-vc-redist) matching your PHP architecture.

## 2. Configure PHP extensions

Find the configuration file used by the command-line PHP installation:

```powershell
php --ini
php -r 'echo PHP_BINARY, PHP_EOL;'
```

Open the file shown next to **Loaded Configuration File** in VS Code. If it says `(none)`, open the directory containing the actual `php.exe` printed above, copy `php.ini-development` to `php.ini`, then run `php --ini` again. Make sure the file is named `php.ini`, not `php.ini.txt`.

In `php.ini`, enable these lines by removing the leading semicolon (`;`). Do not add a second copy if a line is already enabled:

```ini
extension_dir = "ext"
extension=curl
extension=fileinfo
extension=mbstring
extension=openssl
extension=pdo_sqlite
extension=sqlite3
extension=zip
```

If PHP cannot find extension DLLs, set `extension_dir` to the absolute path of the `ext` folder in that PHP installation. `pdo_sqlite` provides the database connection; `sqlite3` also enables PHP's native SQLite API. `zip` is useful for extracting Composer packages.

The following modules must also be available: `ctype`, `date`, `dom`, `filter`, `hash`, `iconv`, `json`, `libxml`, `pcre`, `PDO`, `Phar`, `Reflection`, `session`, `SimpleXML`, `tokenizer`, `xml` and `xmlwriter`. They are normally built into the Windows PHP distribution; do not invent extension lines for modules that are already loaded.

Save the file and verify:

```powershell
php -m
```

Check that the modules listed above appear in the output. Restart any running PHP server after changing `php.ini`.

## 3. Install Composer

Download and run the official [Composer Windows installer](https://getcomposer.org/Composer-Setup.exe):

```powershell
Invoke-WebRequest https://getcomposer.org/Composer-Setup.exe -OutFile "$env:TEMP\Composer-Setup.exe"
Start-Process -FilePath "$env:TEMP\Composer-Setup.exe" -Wait
```

Follow the installer and select the same `php.exe` checked in step 2. Reopen PowerShell, then verify:

```powershell
composer --version
```

For macOS or Linux, follow the [Composer installation instructions](https://getcomposer.org/doc/00-intro.md).

## 4. Install Bun

Use the official [Bun installation instructions](https://bun.sh/docs/installation). In PowerShell:

```powershell
powershell -c "irm bun.sh/install.ps1|iex"
```

Reopen PowerShell, then verify:

```powershell
bun --version
```

## 5. Clone the repository

Open a terminal in the folder where you keep your projects:

```sh
git clone https://github.com/FrederikRuutel/blog.git
cd blog
code .
```

Run all remaining commands from this `blog` directory, which contains `artisan`, `composer.json` and `package.json`.

If you already have a clone, open its directory and run `git pull` instead of cloning again. Commit or stash your own changes before pulling.

## 6. Install project dependencies

```sh
composer install
bun install
composer check-platform-reqs
```

Composer installs the PHP packages into `vendor`. Bun installs frontend packages into `node_modules`. Keep the lock files: they record the project's dependency versions. Use `composer install`, not `composer update`, when setting up an existing project.

## 7. Create the environment file

This command copies the example only if `.env` does not already exist:

```sh
php -r "file_exists('.env') || copy('.env.example', '.env');"
```

Open `.env` and keep these settings for local development:

```dotenv
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
```

Leave `DB_DATABASE` commented out to use `database/database.sqlite`. The example also uses the database for sessions, cache and queues, so migrations are required even for the home page. Never commit `.env` or share its secrets.

## 8. Generate the application key

```sh
php artisan key:generate
```

This writes `APP_KEY` to `.env`. Generate a key once for a fresh installation; do not regenerate it every time you start the project.

## 9. Create the SQLite database and run migrations

Create the database file without overwriting an existing database:

```sh
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --seed
```

If Laravel asks whether to create the SQLite database, answer `yes`. The migrations create the required tables. The post seeder adds 1,000 sample posts only when the posts table is empty; running it again keeps existing posts unchanged. The `database` directory and database file must be writable. No MySQL username or password is needed.

## 10. Start the application

```sh
composer run dev
```

Keep the terminal open. This project's Composer script runs `php artisan dev`, which starts the development processes, including the PHP server and Vite.

Open **[http://localhost:8000](http://localhost:8000)**. The Vite port is for frontend assets; it is not the blog URL. Use **Ctrl+C** to stop the development processes.

To run the PHP server and Vite separately if the combined runner fails, use two terminals in the project directory:

```sh
php artisan serve
```

```sh
bun run dev
```

## 11. Check the result

The home page should show post cards with **Read more** links and pagination. **Admin → Posts** opens the teacher's post table at `/admin/posts`. Its create, edit and delete actions remain unfinished examples, as in the teacher's repository.

Open **Buttons** in the navigation, or visit [http://localhost:8000/buttons](http://localhost:8000/buttons). This page contains our **Primary** and **Disabled primary** buttons alongside the teacher's button examples. Hover over Primary, hold the mouse button down, and use Tab to check keyboard focus. The disabled example must not activate.

Compare it with [Bootstrap 5.3 buttons](https://getbootstrap.com/docs/5.3/components/buttons/). The demo in `resources/views/buttons.blade.php` uses Tailwind utility classes, no Bootstrap CSS and no custom button CSS. The project already includes daisyUI, but our primary-button exercise does not use its button classes. The surrounding blog and the teacher's other examples use daisyUI.

You can also verify that frontend assets compile:

```sh
bun run build
```

## Troubleshooting

| Problem | Fix |
| --- | --- |
| A command is not recognized | Reopen PowerShell and VS Code. Check that the program is installed and its directory is on PATH. |
| Composer reports an unsupported PHP version | Run `php -v` and `where.exe php`; use PHP 8.4.1+ for the locked dependencies. |
| Composer reports a missing extension | Run `php --ini` and `php -m`, then enable the missing extension in the loaded CLI configuration. |
| `could not find driver` | Enable `pdo_sqlite` in the correct `php.ini`, then restart the PHP server. |
| `No application encryption key has been specified` | Create `.env`, then run `php artisan key:generate`. |
| Missing database file or `no such table` | Complete step 9 and check `DB_CONNECTION=sqlite`. |
| `Vite manifest not found` or missing styles | Run `bun install`, then keep `bun run dev` running, or generate assets with `bun run build`. |
| Port 8000 is already in use | Stop the other server, or run `php artisan serve --port=8001` and open `http://localhost:8001`. |
| Environment changes do not take effect | Run `php artisan config:clear` and restart the development processes. |

After pulling future changes, run `composer install`, `bun install` and `php artisan migrate --seed` before starting the app again.

## Teacher reference

The layout and post examples were adapted from [Kasparsu/mm24blog](https://github.com/Kasparsu/mm24blog/tree/c42e33a) (commit `c42e33a`, checked on 13 October 2026). The shared layout, Dracula theme, desktop four-column post grid, navigation and admin post table follow that version. Sample post text is generated locally, so it will differ from the teacher's database.

Our complete installation guide and Tailwind-only button exercise are retained. The `learnphp` user CRUD belongs to the separate [learnphp repository](https://github.com/FrederikRuutel/learnphp).
