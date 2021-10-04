# PTD1-Server-Code
I am attempting to recreate the PTD1 Server code so a local server can be ran and the swf will work fine with just a /etc/hosts change to a different ip.

# My Nginx Config
```nginx
# nginx version: nginx/1.20.1 (don't think the syntax will change in the future but if it does, the version is listed here)
server {
    listen 80;
    server_name ptd1.jordanplayz158.xyz www.sndgames.com;

    access_log /var/log/nginx/www.sndgames.com.access.log;
    error_log /var/log/nginx/www.sndgames.com.error.log;

    index index.html index.php;
    root /var/www/PTD1-Server-Code/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        
        # This was written in PHP 8.0.10 (cli) (built: Aug 26 2021 10:26:33) ( NTS )
        # Copyright (c) The PHP Group
        # Zend Engine v4.0.10, Copyright (c) Zend Technologies
        fastcgi_pass unix:/run/php-fpm/php-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param PHP_VALUE "upload_max_filesize = 100M \n post_max_size=100M";
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTP_PROXY "";
        fastcgi_intercept_errors off;
        fastcgi_buffer_size 16k;
        fastcgi_buffers 4 16k;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_read_timeout 300;
        include /etc/nginx/fastcgi_params;
    }
}

server {
  listen 443 ssl http2;
  server_name ptd1.jordanplayz158.xyz www.sndgames.com;

  access_log /var/log/nginx/www.sndgames.com.access.log;
  error_log /var/log/nginx/www.sndgames.com.error.log;
  
  index index.php;
  root /var/www/PTD1-Server-Code/public;

  # Allow large attachments
  client_max_body_size 128M;

  # SSL Configuration
  ssl_certificate /etc/letsencrypt/live/jordanplayz158.xyz/fullchain.pem;
  ssl_certificate_key /etc/letsencrypt/live/jordanplayz158.xyz/privkey.pem;
  ssl_session_cache shared:SSL:10m;
  ssl_protocols TLSv1.2 TLSv1.3;
  ssl_ciphers "ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDH$
  ssl_prefer_server_ciphers on;

  # See https://hstspreload.org/ before uncommenting the line below.
  # add_header Strict-Transport-Security "max-age=15768000; preload;";
  add_header X-Content-Type-Options nosniff;
  add_header X-XSS-Protection "1; mode=block";
  add_header X-Robots-Tag none;
  add_header Content-Security-Policy "frame-ancestors 'self'";
  add_header X-Frame-Options DENY;
  add_header Referrer-Policy same-origin;

  location / {
      try_files $uri $uri/ /index.php?$query_string;
  }

  location ~ \.php$ {
      fastcgi_split_path_info ^(.+\.php)(/.+)$;
        
      # This was written in PHP 8.0.10 (cli) (built: Aug 26 2021 10:26:33) ( NTS )
      # Copyright (c) The PHP Group
      # Zend Engine v4.0.10, Copyright (c) Zend Technologies
      fastcgi_pass unix:/run/php-fpm/php-fpm.sock;
      fastcgi_index index.php;
      include fastcgi_params;
      fastcgi_param PHP_VALUE "upload_max_filesize = 100M \n post_max_size=100M";
      fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
      fastcgi_param HTTP_PROXY "";
      fastcgi_intercept_errors off;
      fastcgi_buffer_size 16k;
      fastcgi_buffers 4 16k;
      fastcgi_connect_timeout 300;
      fastcgi_send_timeout 300;
      fastcgi_read_timeout 300;
      include /etc/nginx/fastcgi_params;
  }
}
```

# Contributors
## Coders
KGMats - https://github.com/KGMats (Made the keygen.php code - removed java 16 dependency)
## Testers
KGMats - https://github.com/KGMats  
Gaminator#2433 (816098181707333652)  
PlantSceptile123#4592 (326373002398924801)
