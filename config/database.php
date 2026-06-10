'mysql' => [
    'driver' => 'mysql',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', 'mysql.railway.internal'), // Replace 'mysql.railway.internal' with your RAW Railway MySQL Host if different
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'railway'),        // Replace 'railway' with your RAW Railway Database name
    'username' => env('DB_USERNAME', 'root'),           // Replace 'root' with your RAW Railway Username
    'password' => env('DB_PASSWORD'),                   // Put your RAW Railway MySQL password here as a string literal if env() fails
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
],