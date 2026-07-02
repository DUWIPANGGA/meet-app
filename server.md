# Server Configuration

## Server Info

| Item | Value |
|------|-------|
| **IP Address** | `103.189.234.229` |
| **Hostname** | idcloudhost VPS |
| **OS** | Ubuntu 24.04.4 LTS |
| **User** | `gesvy` |
| **SSH Port** | `22` |
| **Provider** | idcloudhost.com |

---

## SSH Access

```bash
ssh gesvy@103.189.234.229
```

Password: `Gesvy123.`

> User `gesvy` has full `sudo` access.

---

## Stack

| Technology | Version |
|-----------|---------|
| **PHP** | 8.4.22 (FPM + CLI) |
| **Laravel** | 12.61.0 |
| **MySQL** | 8.0.46 |
| **Nginx** | 1.24.0 |
| **Node.js** | 18.19.1 |
| **npm** | 9.2.0 |
| **Redis** | 7.0.15 |
| **Composer** | 2.7.1 |
| **Python** | 3.12.3 |

---

## Project Location

```
/var/www/bps
```

---

## Database

| Item | Value |
|------|-------|
| **Engine** | MySQL 8.0 |
| **Database** | `bps` |
| **User** | `root` |
| **Password** | `Gesvy123.` |
| **Host** | `127.0.0.1` |
| **Port** | `3306` |

---

## Nginx

**Config file**: `/etc/nginx/sites-available/bps`

```nginx
server {
    listen 80;
    server_name 103.189.234.229;
    root /var/www/bps/public;
    index index.php;

    client_max_body_size 100M;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

**Commands**:
```bash
sudo nginx -t          # test config
sudo systemctl restart nginx
sudo systemctl reload nginx
```

---

## PHP-FPM

| Item | Value |
|------|-------|
| **Version** | 8.4 |
| **Socket** | `unix:/var/run/php/php8.4-fpm.sock` |
| **Config** | `/etc/php/8.4/fpm/` |

**Commands**:
```bash
sudo systemctl restart php8.4-fpm
sudo systemctl status php8.4-fpm
```

---

## Supervisor Services

All services managed by **Supervisor**. Config files in `/etc/supervisor/conf.d/`.

| Service | Command | Port |
|---------|---------|------|
| **bps-queue** | `php artisan queue:work` (×2 processes) | — |
| **bps-reverb** | `php artisan reverb:start` | `8080` |
| **bps-livekit** | `livekit-server` | `7880` (TCP), `7881` (TCP), `7882` (UDP) |
| **bps-whisper** | `python main.py` (faster-whisper) | `8001` |

### Supervisor Management
```bash
sudo supervisorctl status          # check all
sudo supervisorctl restart all     # restart all
sudo supervisorctl restart bps-queue:*
sudo supervisorctl restart bps-reverb
sudo supervisorctl restart bps-livekit
sudo supervisorctl restart bps-whisper
sudo supervisorctl tail -f bps-queue    # view logs
```

### Log Files
```
/var/log/bps-queue.log
/var/log/bps-reverb.log
/var/log/bps-livekit.log
/var/log/bps-whisper.log
```

---

## Firewall (UFW)

```bash
sudo ufw status
```

**Open Ports**:
| Port | Protocol | Service |
|------|----------|---------|
| `22` | TCP | SSH |
| `80` | TCP | HTTP (Nginx) |
| `443` | TCP | HTTPS |
| `7880` | TCP | LiveKit WebSocket |
| `7881` | TCP | LiveKit TCP |
| `7882` | UDP | LiveKit Media |
| `8080` | TCP | Reverb WebSocket |
| `8001` | TCP | Whisper API |

---

## Laravel

```bash
cd /var/www/bps

# Artisan
php8.4 artisan migrate
php8.4 artisan db:seed
php8.4 artisan optimize:clear
php8.4 artisan config:cache
php8.4 artisan route:cache

# Storage
php8.4 artisan storage:link
```

### Key `.env` Values

```
APP_URL=http://103.189.234.229
DB_DATABASE=bps
DB_USERNAME=root
DB_PASSWORD=Gesvy123.
REVERB_HOST=103.189.234.229
LIVEKIT_SERVER_URL=ws://103.189.234.229:7880
WHISPER_URL=http://103.189.234.229:8001/transcribe
DEEPSEEK_API_KEY=sk-28614c7c8b4147cdac99f74bb3dc0922
```

---

## LiveKit

**Binary**: `/var/www/bps/livekit-server`
**Config**: `/var/www/bps/docker/livekit.yaml`

```yaml
port: 7880
keys:
  devkey: secret
rtc:
  tcp_port: 7881
  udp_port: 7882
logging:
  level: info
```

---

## Whisper Server

**Location**: `/var/www/bps/whisper_server/`
**Python venv**: `/var/www/bps/whisper_server/venv/`
**Port**: `8001`

```bash
cd /var/www/bps/whisper_server
source venv/bin/activate
python main.py            # runs on port 8001
```

### Installed Packages
- `faster-whisper` (transcription)
- `fastapi` (HTTP API)
- `uvicorn` (ASGI server)
- `python-multipart` (file uploads)

---

## Redis

| Item | Value |
|------|-------|
| **Host** | `127.0.0.1` |
| **Port** | `6379` |
| **Service** | `redis-server` |

```bash
sudo systemctl restart redis-server
```

---

## File Permissions

```bash
# Storage & cache writable
sudo chown -R www-data:www-data /var/www/bps/storage /var/www/bps/bootstrap/cache
sudo chmod -R 775 /var/www/bps/storage /var/www/bps/bootstrap/cache

# Project owner
sudo chown -R gesvy:www-data /var/www/bps
```

---

## Deployment Notes

- Project cloned from: `https://github.com/DUWIPANGGA/gevsy.git`
- Composer installed with `--no-dev --optimize-autoloader` (ignore platform reqs)
- Vite assets built with `npm run build`
- Default PHP set to `php8.4` via `update-alternatives`
- MySQL root auth set to `mysql_native_password`
