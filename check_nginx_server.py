import sys, io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8', errors='replace')
import paramiko

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect('103.189.234.229', username='gesvy', password='Gesvy123.', timeout=10)

def run(cmd, label=""):
    print(f"=== {label} ===")
    _, stdout, stderr = ssh.exec_command(cmd, timeout=300)
    out = stdout.read().decode('utf-8', errors='replace')
    err = stderr.read().decode('utf-8', errors='replace')
    if out.strip(): print(out.strip()[:5000])
    if err.strip(): print("STDERR:", err.strip()[:5000])
    print()

# Check current nginx config
run('cat /etc/nginx/sites-available/bps', "Current Nginx")

ssh.close()
