import json
import urllib.request

dot_content = """
graph ERD {
    layout=fdp;
    overlap=false;
    splines=true;
    node [fontname="Helvetica", fontsize=10];
    edge [fontname="Helvetica", fontsize=10];

    // Entities
    node [shape=box, style=filled, fillcolor="#DAE8FC", color="#6C8EBF"];
    Users; Pemiliks; Umkms; Pengajuans; Kelurahans; Rws; Rts;
    Kategori_umkms; Sektor_umkms; Berita; Dss_analyses; Aktivitas;
    Notifications; Settings;

    // Relationships
    node [shape=diamond, style=filled, fillcolor="#FFF2CC", color="#D6B656"];
    R_Memiliki_UMKM [label="Memiliki"];
    R_Mengkategorikan [label="Mengkategorikan"];
    R_Mengelompokkan [label="Mengelompokkan"];
    R_Mendata [label="Mendata"];
    R_Mengajukan [label="Mengajukan"];
    R_Kel_RW [label="Memiliki"];
    R_RW_RT [label="Memiliki"];
    R_Menganalisis [label="Menganalisis"];
    R_Melakukan [label="Melakukan"];
    R_Tercatat [label="Tercatat"];
    R_Menerima [label="Menerima"];
    R_Ditugaskan [label="Ditugaskan"];

    // Relationships edges
    Pemiliks -- R_Memiliki_UMKM [label="1"];
    R_Memiliki_UMKM -- Umkms [label="N"];

    Kategori_umkms -- R_Mengkategorikan [label="1"];
    R_Mengkategorikan -- Umkms [label="N"];

    Sektor_umkms -- R_Mengelompokkan [label="1"];
    R_Mengelompokkan -- Umkms [label="N"];

    Users -- R_Mendata [label="1"];
    R_Mendata -- Umkms [label="N"];

    Umkms -- R_Mengajukan [label="1"];
    R_Mengajukan -- Pengajuans [label="N"];

    Kelurahans -- R_Kel_RW [label="1"];
    R_Kel_RW -- Rws [label="N"];

    Rws -- R_RW_RT [label="1"];
    R_RW_RT -- Rts [label="N"];

    Users -- R_Menganalisis [label="1"];
    R_Menganalisis -- Dss_analyses [label="N"];

    Users -- R_Melakukan [label="1"];
    R_Melakukan -- Aktivitas [label="N"];

    Umkms -- R_Tercatat [label="1"];
    R_Tercatat -- Aktivitas [label="N"];

    Users -- R_Menerima [label="1"];
    R_Menerima -- Notifications [label="N"];

    Kelurahans -- R_Ditugaskan [label="1"];
    R_Ditugaskan -- Users [label="N"];

    // Attributes
    node [shape=ellipse, style=filled, fillcolor="#FFFFFF", color="#000000"];
    
    // Kelurahans
    Kel_id [label=<<U>id</U>>];
    Kelurahans -- Kel_id;
    Kelurahans -- kode_kelurahan;
    Kelurahans -- nama_kelurahan;

    // Rws
    Rw_id [label=<<U>id</U>>];
    Rws -- Rw_id;
    Rws -- nomor_rw;
    Rw_kel_id [label="kelurahan_id", style=dashed];
    Rws -- Rw_kel_id;

    // Rts
    Rt_id [label=<<U>id</U>>];
    Rts -- Rt_id;
    Rts -- nomor_rt;
    Rt_rw_id [label="rw_id", style=dashed];
    Rts -- Rt_rw_id;

    // Kategori
    Kat_id [label=<<U>id</U>>];
    Kategori_umkms -- Kat_id;
    Kategori_umkms -- nama_kategori;

    // Sektor
    Sek_id [label=<<U>id</U>>];
    Sektor_umkms -- Sek_id;
    Sektor_umkms -- nama_sektor;
    Sektor_umkms -- deskripsi;

    // Users
    U_id [label=<<U>id</U>>];
    Users -- U_id;
    Users -- name;
    Users -- email;
    Users -- password;
    Users -- nik;
    U_id_kelurahan [label="id_kelurahan", style=dashed];
    Users -- U_id_kelurahan;

    // Pemiliks
    P_id [label=<<U>id_pemilik</U>>];
    Pemiliks -- P_id;
    Pemiliks -- P_nik [label="nik"];
    Pemiliks -- nama_lengkap;
    Pemiliks -- tempat_lahir;
    Pemiliks -- tanggal_lahir;
    Pemiliks -- jenis_kelamin;
    P_id_kelurahan [label="id_kelurahan", style=dashed];
    Pemiliks -- P_id_kelurahan;

    // Umkms
    U_id_umkm [label=<<U>id_umkm</U>>];
    Umkms -- U_id_umkm;
    Umkms -- no_pendaftaran;
    Umkms -- nama_usaha;
    U_id_pem [label="id_pemilik", style=dashed];
    U_id_kat [label="id_kategori", style=dashed];
    U_id_sek [label="id_sektor", style=dashed];
    Umkms -- U_id_pem;
    Umkms -- U_id_kat;
    Umkms -- U_id_sek;
    node [shape=ellipse, peripheries=2];
    Umkms -- foto_gallery;
    Umkms -- media_sosial;
    node [shape=ellipse, peripheries=1];

    // Pengajuans
    Pg_id [label=<<U>id_pengajuan</U>>];
    Pengajuans -- Pg_id;
    Pg_umkm_id [label="umkm_id", style=dashed];
    Pengajuans -- Pg_umkm_id;
    Pengajuans -- jenis_pengajuan;
    Pengajuans -- nominal;
    Pengajuans -- status;

    // Berita
    B_id [label=<<U>id</U>>];
    Berita -- B_id;
    Berita -- judul;

    // Dss_analyses
    Dss_id [label=<<U>id</U>>];
    Dss_analyses -- Dss_id;
    Dss_user_id [label="user_id", style=dashed];
    Dss_analyses -- Dss_user_id;

    // Aktivitas
    Akt_id [label=<<U>id</U>>];
    Aktivitas -- Akt_id;
    Akt_user_id [label="user_id", style=dashed];
    Akt_umkm_id [label="umkm_id", style=dashed];
    Aktivitas -- Akt_user_id;
    Aktivitas -- Akt_umkm_id;

    // Notifications
    Notif_id [label=<<U>id</U>>];
    Notifications -- Notif_id;
    Notif_id_type [label="notifiable_id", style=dashed];
    Notifications -- Notif_id_type;

    // Settings
    Set_id [label=<<U>id</U>>];
    Settings -- Set_id;
    Settings -- key;
}
"""

data = json.dumps({"graph": dot_content, "format": "png"}).encode("utf-8")
req = urllib.request.Request("https://quickchart.io/graphviz", data=data, headers={"Content-Type": "application/json"})
try:
    with urllib.request.urlopen(req) as response:
        with open("/home/aeropro/.gemini/antigravity-ide/brain/0088e856-0f2b-4f9e-aac0-cc300a868e0d/erd_mandalaloka_full.png", "wb") as f:
            f.write(response.read())
    print("Success")
except Exception as e:
    print("Error:", e)
