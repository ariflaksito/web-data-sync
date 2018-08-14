# Web Data Sync
Merupakan aplikasi web yang dikembangkan menggunakan Framework Slim php. 
Digunakan sebagai server REST API untuk sinkronisasi database di sisi server dan database di client.
Untuk lebih detail konsepnya bisa dipelajari artikel berikut:

[Penelitian Sinkronisasi Database](http://ojs.amikom.ac.id/index.php/semnasteknomedia/article/view/2146/1950)

### Memulai aplikasi
- import file amsosv2.sql ke database mysql
- sesuaikan konfigurasi db di file /src/config.php
- composer update
- php -S localhost:8090 -t public

### Fitur
- Menggunakan Eloquent untuk Database
- Menggunakan Slim Middleware untuk validasi token

### Branch
Silahkan clone masing-masing branch sesuai kebutuhnnya
- basic > slim tanpa eloquent, tanpa middleware validasi token
- updates/using_eloquent > slim menggunakan eloquent
- updates/auth_token, master > slim, eloquent, middleware validasi token
- run_apache > versi final yg berjalan dg web server apache
