import sys, io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8', errors='replace')
import paramiko

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect('103.189.234.229', username='gesvy', password='Gesvy123.', timeout=10)

nginx_conf = """server {
    listen 80;
    server_name 103.189.234.229 meet-bps.my.id;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name 103.189.234.229 meet-bps.my.id;

    root /var/www/bps/public;
    index index.php;

    client_max_body_size 100M;
    charset utf-8;

    ssl_certificate /etc/letsencrypt/live/meet-bps.my.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/meet-bps.my.id/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # LiveKit WebSocket
    location /rtc {
        proxy_pass http://127.0.0.1:7880;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_read_timeout 86400;
    }

    # Whisper WebSocket (live transcription)
    location /ws/transcribe {
        proxy_pass http://127.0.0.1:8001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_read_timeout 86400;
    }

    # Reverb WebSocket (Pusher)
    location /app/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_read_timeout 86400;
    }

    location /broadcasting/auth {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
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
"""

# Write Nginx config
stdin, stdout, stderr = ssh.exec_command('sudo tee /etc/nginx/sites-available/bps > /dev/null', timeout=30)
stdin.write(nginx_conf.encode())
stdin.channel.shutdown_write()
stdout.read()
stderr.read()

def run(cmd, label=""):
    print(f"=== {label} ===")
    _, stdout, stderr = ssh.exec_command(cmd, timeout=300)
    out = stdout.read().decode('utf-8', errors='replace')
    err = stderr.read().decode('utf-8', errors='replace')
    if out.strip(): print(out.strip()[:5000])
    if err.strip(): print("STDERR:", err.strip()[:5000])
    print()

run('sudo nginx -t 2>&1', "Nginx test")
run('sudo systemctl reload nginx 2>&1', "Reload Nginx")
run('sudo supervisorctl status', "All services")

ssh.close()
print("Done!")
