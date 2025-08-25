Oke Sukri, berikut saya buatkan README.md lengkap, singkat, jelas, dan siap copy-paste untuk repo ci4-crud-ajax-generator, termasuk panduan instalasi, fitur, dan penggunaan JSON generator. Tinggal copas ke file README.md di repo-mu.

# CI4 CRUD AJAX Generator

🚀 Generator CRUD otomatis berbasis **CodeIgniter 4 + AJAX + Bootstrap + DataTables**.  
Memudahkan developer membuat modul CRUD lengkap: add, edit, view, delete, delete selected, export/import Excel & PDF, upload file, preview file/foto.

## ✨ Fitur Utama
- CRUD otomatis untuk tabel apapun
- Form modal AJAX untuk Add/Edit
- Preview file/foto di index
- Export ke Excel & PDF
- Import dari Excel
- Upload file (image, dokumen, all type)
- Modular Controller, Model, View
- CSRF & escaping siap pakai

## 📦 Instalasi Cepat

1. Clone repo:
```bash
git clone https://github.com/username/ci4-crud-ajax-generator.git
cd ci4-crud-ajax-generator


Install dependencies:

composer install


Copy .env:

cp env .env


Atur database di .env:

database.default.hostname = localhost
database.default.database = ci4crud
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi


Jalankan server:

php spark serve


Akses aplikasi di: http://localhost:8080

⚡ Cara Generate CRUD Baru

Buat JSON schema tabel, contoh pegawai.json:

{
  "table": "pegawai",
  "primaryKey": "id",
  "columns": [
    {"name": "id", "type": "int", "hidden": true},
    {"name": "nama", "type": "text", "label": "Nama", "col_md": 6},
    {"name": "jabatan", "type": "text", "label": "Jabatan", "col_md": 6},
    {"name": "photo", "type": "file", "label": "Foto", "accept": "image/*"}
  ]
}


Upload JSON melalui halaman CRUD Generator

Generator otomatis membuat:

Model

Controller

View (index, form add/edit)

Script AJAX

Export/Import Excel & PDF

🗂 Struktur Folder
ci4-crud-ajax-generator/
 ├── app/
 │   ├── Controllers/
 │   │   └── Backend/
 │   ├── Models/
 │   ├── Views/
 │   │   └── backend/
 │   └── Helpers/
 ├── public/
 ├── writable/
 ├── .gitignore
 ├── README.md
 └── composer.json

👨‍💻 Kontribusi

Fork repo & buat pull request

Pastikan coding sesuai standar PSR-4

📜 Lisensi

MIT License
