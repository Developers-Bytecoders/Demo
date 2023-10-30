<p align="center">
  <img src="https://i.ibb.co/FJZQhkQ/White-2.png" alt="Bytenexus Logo" width="300" />
</p>

# Bievenido a su Task Manager! :rocket:

## Prerequisites :bookmark_tabs:

- PHP >= 8.2
- Composer
- Database Server (MySQL, PostgreSQL, SQLite, etc.)
 

## Instalación del proyecto  :running_man:

### 1. Clonación del repositorio :busts_in_silhouette:

[![N|Solid](https://i.ibb.co/0FbhP12/Vite-installation-for-styles-3.png)](https://nodesource.com/products/nsolid)

   - Vaya al repositorio de GitHub y haga clic en "Clonar o descargar" para copiar el enlace.

### 2. Abra su consola en la carpeta donde guardara el proyecto :desktop_computer:
   - De click derecho y de click en  `Git Bash Here`para abrir la consola
   
### 4. Clone el repositorio
   - en la conosola debera ejecutar el siguiente comando:
     ```bash
     git clone https://github.com/Developers-Bytecoders/Demo.git
     ```

### 5. Ubique el repositorio descargado :mag_right:
   - abra su explorador de archivos y ubique donde se guardo el proyecto

### 6. Abra el proyecto con visual estudio :rocket:
[![N|Solid](https://i.ibb.co/3sh1h5Z/laravel-project.png)](https://nodesource.com/products/nsolid)

   - Arrastre la carpeta completa del proyecto a una ventana de Visual Studio Code o desde visual elija el proyecto.
   - Corra el siguiente comando para actualizar composer
  ```bash
  composer update
   ```

### 7. Change passwords 
   - Copie y pegue el `".env.example"` y cambie el nombre a `".env"`
  y cree una base de datos que coincida con el nombre que le asignara en su `".env"`

### 8.Configure la base de datos

Edite el `.env` para configurar las variables y el nombre su bd

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=username
DB_PASSWORD=password
```

### 9.Asigne una key al .env

corra el siguiente comando.

```env
php artisan key:generate
```


## 10. Corra migraciones :books:	:bookmark:	

[![N|Solid](https://i.ibb.co/YDYgWSk/migrationsandseeders.png)](https://nodesource.com/products/nsolid)

Para ejecutar las migraciones y sembrar su base de datos con datos iniciales, ejecute el siguiente comando:

```bash
php artisan migrate --seed
```

## 11. Iniciar el servidor para visualizar su proyecto :rocket: :desktop_computer:	

[![N|Solid](https://i.ibb.co/k25rtmc/images.png)](https://nodesource.com/products/nsolid)


Para iniciar el servidor de desarrollo de Laravel corra el siguinte comando, run: :runner: :green_circle:	

```bash
php artisan serve
```

Esto iniciará el servidor y podrá acceder al proyecto a través del navegador en la URL proporcionada en la consola, que generalmente es: :keyboard:	

```bash
http://127.0.0.1:8000
```