import pty
import os
import select

def ssh_command(host, user, password, command):
    pid, fd = pty.fork()
    if pid == 0:
        os.execvp("ssh", ["ssh", "-o", "StrictHostKeyChecking=no", f"{user}@{host}", command])
    else:
        output = b""
        while True:
            r, w, e = select.select([fd], [], [], 10.0)
            if fd in r:
                try:
                    data = os.read(fd, 1024)
                    if not data:
                        break
                    output += data
                    if b"password:" in data.lower():
                        os.write(fd, password.encode() + b"\n")
                except OSError:
                    break
            else:
                break
        return output.decode()

def scp_command(host, user, password, local_file, remote_file):
    pid, fd = pty.fork()
    if pid == 0:
        os.execvp("scp", ["scp", "-o", "StrictHostKeyChecking=no", local_file, f"{user}@{host}:{remote_file}"])
    else:
        output = b""
        while True:
            r, w, e = select.select([fd], [], [], 10.0)
            if fd in r:
                try:
                    data = os.read(fd, 1024)
                    if not data:
                        break
                    output += data
                    if b"password:" in data.lower():
                        os.write(fd, password.encode() + b"\n")
                except OSError:
                    break
            else:
                break
        return output.decode()

host = "103.89.4.245"
user = "ubuntu"
password = "MandalalokaUMKM40195"

files_to_deploy = [
    {
        "local": "/home/aeropro/MandalalokaApps/resources/views/superadmin/umkm/modal-detail.blade.php",
        "remote_tmp": "/home/ubuntu/superadmin_umkm_modal.blade.php",
        "remote_final": "/var/www/html/resources/views/superadmin/umkm/modal-detail.blade.php"
    },
    {
        "local": "/home/aeropro/MandalalokaApps/resources/views/superadmin/umkm/show.blade.php",
        "remote_tmp": "/home/ubuntu/superadmin_umkm_show.blade.php",
        "remote_final": "/var/www/html/resources/views/superadmin/umkm/show.blade.php"
    },
    {
        "local": "/home/aeropro/MandalalokaApps/resources/views/admin/umkm/show.blade.php",
        "remote_tmp": "/home/ubuntu/admin_umkm_show.blade.php",
        "remote_final": "/var/www/html/resources/views/admin/umkm/show.blade.php"
    },
    {
        "local": "/home/aeropro/MandalalokaApps/resources/views/admin/verifikasi/show.blade.php",
        "remote_tmp": "/home/ubuntu/admin_verifikasi_show.blade.php",
        "remote_final": "/var/www/html/resources/views/admin/verifikasi/show.blade.php"
    },
    {
        "local": "/home/aeropro/MandalalokaApps/resources/views/petugas/umkm/show.blade.php",
        "remote_tmp": "/home/ubuntu/petugas_umkm_show.blade.php",
        "remote_final": "/var/www/html/resources/views/petugas/umkm/show.blade.php"
    },
    {
        "local": "/home/aeropro/MandalalokaApps/resources/views/superadmin/pengajuan/modal-detail.blade.php",
        "remote_tmp": "/home/ubuntu/superadmin_pengajuan_modal.blade.php",
        "remote_final": "/var/www/html/resources/views/superadmin/pengajuan/modal-detail.blade.php"
    },
    {
        "local": "/home/aeropro/MandalalokaApps/resources/views/admin/pengajuan/modal-detail.blade.php",
        "remote_tmp": "/home/ubuntu/admin_pengajuan_modal.blade.php",
        "remote_final": "/var/www/html/resources/views/admin/pengajuan/modal-detail.blade.php"
    }
]

for f in files_to_deploy:
    print(f"Uploading {os.path.basename(f['local'])}...")
    print(scp_command(host, user, password, f['local'], f['remote_tmp']))

print("Deploying inside Docker...")
cmd_parts = []
for f in files_to_deploy:
    cmd_parts.append(f"sudo docker cp {f['remote_tmp']} mandalaloka-app-1:{f['remote_final']}")
cmd_parts.append("sudo docker exec mandalaloka-app-1 php artisan view:clear")

cmd = " && ".join(cmd_parts)
print(ssh_command(host, user, password, cmd))
