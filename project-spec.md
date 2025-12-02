# PROJECT SPECIFICATION: Sistem Perhitungan Biaya Usaha (Laravel 12)

**Tanggal**: 02 December 2025  
**Tujuan**: Membangun web app berbasis Laravel 12 untuk menghitung biaya operasional, HPP (Harga Pokok Penjualan), dan biaya lain-lain terkait usaha dengan sistem takaran/resep terintegrasi.

---

## 1. OVERVIEW PROJECT

### Latar Belakang
Usaha membutuhkan sistem perhitungan biaya yang akurat dan terstruktur untuk memantau, mengendalikan, serta menganalisis biaya operasional harian/bulanan, menghitung HPP secara dinamis dengan mempertimbangkan takaran material yang dipakai, dan merekap biaya lain yang mungkin timbul.

### Tujuan Utama
- Menyediakan platform perhitungan HPP otomatis berdasarkan resep/takaran material
- Mencatat dan merekap biaya operasional, produksi, dan lain-lain
- Menghasilkan laporan keuangan (HPP, biaya, laba-rugi) dalam format export (PDF/Excel)
- Mempermudah pengambilan keputusan bisnis melalui visualisasi data

### Target User
- Pemilik usaha/UKM (skala kecil hingga menengah)
- Admin keuangan/operasional
- Bisa single-user atau multi-user (dengan role management)

---

## 2. FITUR UTAMA

### 2.1 Manajemen Material/Bahan
**Tujuan**: Mengelola data material/bahan baku dengan harga satuan terkini.

**Sub-fitur**:
- CRUD material (nama, unit, harga satuan)
- Tampilkan daftar material dengan pencarian/filter
- History harga material (tracking perubahan harga)
- Input/update harga satuan material

**Kebutuhan**:
- Form input material (nama, unit: kg/liter/pcs/dsb, harga)
- Tabel daftar material dengan action (edit, delete, view history)
- Validasi input (nama tidak boleh kosong, harga > 0)

---

### 2.2 Manajemen Produk
**Tujuan**: Mengelola data produk yang akan diproduksi.

**Sub-fitur**:
- CRUD produk (nama, unit output, deskripsi)
- Tampilkan daftar produk dengan pencarian
- Link ke resep/takaran per produk

**Kebutuhan**:
- Form input produk (nama, unit output: pcs/kg/liter/dsb, deskripsi)
- Tabel daftar produk dengan action (edit, delete, view resep, view produksi history)
- Validasi input

---

### 2.3 Manajemen Resep/Takaran Produk
**Tujuan**: Menentukan komposisi material per unit output produk (takaran).

**Sub-fitur**:
- Buat resep untuk setiap produk
- Pilih material + masukkan jumlah takaran (berapa banyak material per unit output)
- Edit/hapus resep
- Tampilkan resep per produk dengan detail material

**Contoh Resep**:
- Produk: "Kopi Instan 1kg"
- Biji Kopi Arabika: 1.2 kg per output 1 kg
- Gula: 0.2 kg per output 1 kg
- Garam: 0.01 kg per output 1 kg

**Kebutuhan**:
- Form input resep (pilih produk → pilih material → input takaran)
- Tabel resep per produk dengan action (edit, delete)
- Validasi: takaran harus > 0

---

### 2.4 Pencatatan Batch Produksi & Perhitungan HPP Otomatis
**Tujuan**: Mencatat batch produksi dan auto-hitung HPP berdasarkan resep + harga material terkini.

**Sub-fitur**:
- Input batch produksi (pilih produk, input jumlah output, tanggal produksi)
- Sistem otomatis menghitung HPP total & HPP per unit berdasarkan resep
- Opsi: input biaya tambahan (upah, packaging, overhead per batch)
- Tampilkan detail HPP breakdown per batch
- Edit/delete batch (jika belum finalisasi)

**Rumus Perhitungan HPP**:
```
HPP Total Material = Σ (material_takaran × harga_satuan_material)
                   = (1.2kg × Rp100k) + (0.2kg × Rp20k) + (0.01kg × Rp10k)

Jika ada biaya tambahan:
Total HPP = HPP Material + Biaya Tambahan
HPP Per Unit = Total HPP / Jumlah Output
```

**Kebutuhan**:
- Form input batch (pilih produk, input jumlah output, tanggal, biaya tambahan optional)
- Tampilkan preview HPP otomatis sebelum simpan
- Tabel daftar batch dengan action (edit, delete, view detail)
- Detail batch menampilkan breakdown: material mana saja + harga, total, per unit

---

### 2.5 Pencatatan Biaya Operasional
**Tujuan**: Mencatat biaya operasional harian/bulanan (gaji, sewa, listrik, transportasi, dsb).

**Sub-fitur**:
- Daftar kategori biaya operasional (Gaji, Sewa, Listrik, Transportasi, ATK, dll)
- CRUD biaya operasional (pilih kategori, input nominal, tanggal, deskripsi)
- Filter/search berdasarkan kategori, periode, deskripsi
- Tampilkan total biaya operasional per kategori, per periode

**Kebutuhan**:
- Form input biaya (kategori, nominal, tanggal, deskripsi)
- Tabel daftar biaya operasional dengan action (edit, delete)
- Validasi input

---

### 2.6 Pencatatan Biaya Lain-lain
**Tujuan**: Mencatat biaya sekali-sekali atau tak terduga (administrasi, denda, perizinan, dll).

**Sub-fitur**:
- Input biaya lain-lain (kategori, nominal, tanggal, deskripsi)
- Filter/search berdasarkan kategori, periode
- Tampilkan total biaya lain-lain

**Kebutuhan**:
- Form input biaya (mirip dengan biaya operasional)
- Tabel daftar biaya lain-lain
- Validasi input

---

### 2.7 Dashboard & Ringkasan Biaya
**Tujuan**: Menampilkan ringkasan/overview biaya dalam satu halaman.

**Sub-fitur**:
- Tampilkan ringkasan total biaya (operasional + HPP batch + biaya lain-lain) per periode
- Filter periode (bulan, custom date range)
- Breakdown per kategori/jenis biaya
- Widget/card: total biaya hari ini, bulan ini, year-to-date

**Kebutuhan**:
- Layout dashboard dengan card-card ringkasan
- Grafik/chart (optional tapi recommended): tren biaya per hari/minggu/bulan

---

### 2.8 Laporan & Export
**Tujuan**: Menghasilkan laporan komprehensif dan export ke PDF/Excel.

**Sub-fitur**:
- Laporan HPP: daftar batch, HPP detail per batch, total HPP periode
- Laporan Biaya Operasional: breakdown per kategori, total per periode
- Laporan Biaya Lain-lain: detail dan total
- Laporan Ringkas Keuangan (Laba-Rugi): total HPP + biaya operasional + biaya lain-lain = total pengeluaran
- Export ke Excel (.xlsx) dan/atau PDF
- Filter laporan berdasarkan periode (start date - end date)

**Kebutuhan**:
- Halaman report dengan filter date range, kategori
- Tombol export Excel & PDF
- Template laporan yang clean dan professional

---

### 2.9 User Management & Authentication
**Tujuan**: Mengelola akses pengguna (opsional jika multi-user).

**Sub-fitur**:
- Login/Register
- Role: Admin (full access), User (view/input data), Viewer (view only)
- (Opsional) Dashboard per user atau global

**Kebutuhan**:
- Setup Breeze/Jetstream authentication
- Middleware untuk role check
- Halaman login/register

---

## 3. DATABASE SCHEMA

### Tabel: `materials`
```sql
id (PK)
nama (string, unique)
unit (string: kg, liter, pcs, dsb)
harga_satuan (decimal 15,2)
created_at
updated_at
```

### Tabel: `material_prices` (optional - history harga)
```sql
id (PK)
material_id (FK → materials)
harga_satuan (decimal 15,2)
tanggal_berlaku (date)
created_at
updated_at
```

### Tabel: `products`
```sql
id (PK)
nama (string)
unit_output (string: kg, pcs, liter, dsb)
deskripsi (text, nullable)
created_at
updated_at
```

### Tabel: `recipes` (Product Materials / Takaran)
```sql
id (PK)
product_id (FK → products)
material_id (FK → materials)
jumlah_takaran (decimal 10,4) — jumlah material per unit output
catatan (text, nullable)
created_at
updated_at

INDEX: (product_id, material_id) — unique constraint
```

### Tabel: `production_batches`
```sql
id (PK)
product_id (FK → products)
jumlah_output (decimal 10,4) — berapa unit output
tanggal_produksi (date)
hpp_total (decimal 15,2) — otomatis: sum(material takaran × harga)
hpp_per_unit (decimal 15,2) — otomatis: hpp_total / jumlah_output
biaya_tambahan (decimal 15,2, default 0) — upah, packaging, overhead
total_hpp_dengan_tambahan (decimal 15,2) — hpp_total + biaya_tambahan
total_hpp_per_unit_final (decimal 15,2) — total dengan tambahan / jumlah_output
keterangan (text, nullable)
created_at
updated_at

INDEX: (product_id, tanggal_produksi)
```

### Tabel: `cost_accounts` (Kategori Biaya)
```sql
id (PK)
nama (string)
tipe (string: operational, miscellaneous, dll)
deskripsi (text, nullable)
created_at
updated_at
```

### Tabel: `operational_costs` (Biaya Operasional)
```sql
id (PK)
cost_account_id (FK → cost_accounts)
nominal (decimal 15,2)
tanggal (date)
deskripsi (text, nullable)
created_at
updated_at

INDEX: (cost_account_id, tanggal)
```

### Tabel: `miscellaneous_costs` (Biaya Lain-lain)
```sql
id (PK)
cost_account_id (FK → cost_accounts)
nominal (decimal 15,2)
tanggal (date)
deskripsi (text, nullable)
created_at
updated_at

INDEX: (cost_account_id, tanggal)
```

### Tabel: `users` (jika multi-user)
```sql
id (PK)
name (string)
email (string, unique)
password (string)
role (enum: admin, user, viewer — default: user)
email_verified_at (nullable)
created_at
updated_at
```

---

## 4. USER FLOWS

### Flow 1: Setup Initial Data
1. Admin login
2. Buka Manajemen Material → Input material (nama, unit, harga)
3. Buka Manajemen Produk → Input produk (nama, unit output)
4. Buka Manajemen Resep → Pilih produk → Pilih material + input takaran → Simpan

### Flow 2: Pencatatan Produksi & Perhitungan HPP
1. User/Admin login
2. Buka "Input Batch Produksi"
3. Pilih produk → Inputkan jumlah output → Input tanggal produksi
4. Sistem otomatis tampilkan preview HPP (breakdown material × harga)
5. (Opsional) Input biaya tambahan
6. Tekan "Simpan" → HPP per unit langsung tertampil & disimpan ke database

### Flow 3: Pencatatan Biaya Operasional/Lain-lain
1. User/Admin login
2. Buka "Input Biaya Operasional" atau "Input Biaya Lain-lain"
3. Pilih kategori → Input nominal → Input tanggal → Input deskripsi
4. Tekan "Simpan"

### Flow 4: Lihat Dashboard & Laporan
1. User/Admin login → Dashboard menampilkan ringkasan biaya hari ini, bulan ini, YTD
2. Buka "Laporan" → Pilih periode (date range) → Pilih jenis laporan (HPP, Operasional, Lain-lain, atau Ringkas)
3. Sistem tampilkan laporan → Tekan "Export Excel" atau "Export PDF"

---

## 5. KEBUTUHAN TEKNIS

### Stack Teknologi
- **Framework**: Laravel 12
- **Database**: PostgreSQL (recommended) atau MySQL 8.0+
- **Frontend**: Laravel Blade atau Inertia + Vue.js 3
- **Authentication**: Laravel Breeze atau Jetstream
- **Package Utama**:
  - `maatwebsite/excel` — Export Excel
  - `barryvdh/laravel-dompdf` — Export PDF
  - `spatie/laravel-permissions` — Role & Permission (opsional)

### Environment Setup
- PHP 8.2+
- Composer
- Node.js 18+ (jika menggunakan Vite/Inertia)
- Git untuk version control

### Struktur Folder Laravel
```
app/
  Models/
    Material.php
    Product.php
    Recipe.php
    ProductionBatch.php
    CostAccount.php
    OperationalCost.php
    MiscellaneousCost.php
    User.php
  Http/
    Controllers/
      MaterialController.php
      ProductController.php
      RecipeController.php
      ProductionBatchController.php
      CostController.php
      ReportController.php
      DashboardController.php
    Requests/
      StoreMaterialRequest.php
      StoreProductRequest.php
      (dll...)
  Services/
    HPPCalculationService.php
    ReportService.php
database/
  migrations/
    (semua migration file)
  seeders/
    (seeder untuk cost_accounts, users, dll)
routes/
  web.php
resources/
  views/
    layouts/app.blade.php
    materials/
    products/
    recipes/
    batches/
    costs/
    reports/
    dashboard.blade.php
```

---

## 6. FITUR DETAIL PER HALAMAN

### 6.1 Halaman Material
**Route**: `/materials`  
**Method**: GET, POST, PUT, DELETE

**GET /materials** — Tampilkan daftar material
- Tabel: ID, Nama, Unit, Harga Satuan, Action (Edit, Delete, History)
- Search by nama
- Pagination

**GET /materials/create** — Form input material baru
- Input: Nama, Unit (dropdown), Harga Satuan
- Tombol: Submit, Cancel

**POST /materials** — Store material ke database
- Validasi: nama unique, harga > 0
- Return: redirect + success message

**GET /materials/{id}/edit** — Form edit material
- Pre-fill existing data
- Tombol: Update, Cancel

**PUT /materials/{id}** — Update material
- Validasi sama seperti store
- Return: redirect + success message

**DELETE /materials/{id}** — Delete material
- Soft delete (optional) atau hard delete
- Return: redirect + success message

**GET /materials/{id}/price-history** — History harga material
- Tabel: Tanggal, Harga Satuan
- Chart: tren harga

---

### 6.2 Halaman Produk
**Route**: `/products`  
**Method**: GET, POST, PUT, DELETE

Sama seperti Material, dengan tambahan:
- Link ke halaman Resep per produk
- Link ke halaman Production Batches per produk

---

### 6.3 Halaman Resep/Takaran
**Route**: `/products/{product_id}/recipes`  
**Method**: GET, POST, PUT, DELETE

**GET /products/{product_id}/recipes** — Tampilkan resep per produk
- Tabel: Material Nama, Unit, Takaran, Harga Satuan, Action (Edit, Delete)
- Total HPP preview (sum takaran × harga)

**GET /products/{product_id}/recipes/create** — Form input resep
- Pilih Material (dropdown)
- Input Takaran
- Tombol: Add, Cancel

**POST /products/{product_id}/recipes** — Store resep
- Validasi: product_id ada, material_id ada, takaran > 0, unique (product + material)
- Return: redirect dengan preview HPP updated

**PUT /products/{product_id}/recipes/{id}** — Update resep
**DELETE /products/{product_id}/recipes/{id}** — Delete resep

---

### 6.4 Halaman Production Batch & HPP Calculation
**Route**: `/production-batches`  
**Method**: GET, POST, PUT, DELETE

**GET /production-batches** — Daftar batch produksi
- Tabel: Product, Output Qty, Tanggal, HPP Total, HPP Per Unit, Action (Edit, Delete, View Detail)
- Filter by product, date range
- Pagination

**GET /production-batches/create** — Form input batch
- Pilih Product (dropdown)
- Input Jumlah Output
- Input Tanggal Produksi
- Input Biaya Tambahan (optional)
- Preview HPP otomatis muncul (berdasarkan resep + harga material)
- Tombol: Submit, Cancel

**POST /production-batches** — Store batch
- Validasi input
- **Kalkulasi HPP otomatis**: ambil resep produk → sum(takaran × harga material saat itu) → simpen ke database
- Return: redirect dengan detail batch baru

**GET /production-batches/{id}** — View detail batch
- Tampilkan: Product, Output, Tanggal, Breakdown per material (nama, unit, takaran, harga satuan, total), HPP Material, Biaya Tambahan, Total HPP, HPP Per Unit
- Tombol: Edit, Delete, Back

**PUT /production-batches/{id}** — Update batch
**DELETE /production-batches/{id}** — Delete batch

---

### 6.5 Halaman Biaya Operasional
**Route**: `/costs/operational`  
**Method**: GET, POST, PUT, DELETE

**GET /costs/operational** — Daftar biaya operasional
- Tabel: Tanggal, Kategori, Nominal, Deskripsi, Action (Edit, Delete)
- Filter by kategori, date range
- Summary per kategori
- Pagination

**GET /costs/operational/create** — Form input
- Pilih Kategori (dropdown)
- Input Nominal
- Input Tanggal
- Input Deskripsi (optional)
- Tombol: Submit, Cancel

**POST /costs/operational** — Store
- Validasi: kategori ada, nominal > 0
- Return: redirect

**PUT /costs/operational/{id}** — Update
**DELETE /costs/operational/{id}** — Delete

---

### 6.6 Halaman Biaya Lain-lain
**Route**: `/costs/miscellaneous`  
Sama seperti Operational Costs

---

### 6.7 Dashboard
**Route**: `/dashboard`  
**Method**: GET

**Tampilkan**:
- Card Ringkasan (Total Biaya Hari Ini, Bulan Ini, YTD)
- Card: Total HPP, Total Operasional, Total Biaya Lain-lain
- Chart/Graph: Tren biaya 30 hari terakhir
- Latest Transactions: 5 transaksi terbaru (HPP batch, biaya operasional, biaya lain-lain)
- Filter periode (date picker)

---

### 6.8 Halaman Laporan
**Route**: `/reports`  
**Method**: GET, POST

**GET /reports** — Form filter laporan
- Pilih Jenis Laporan: HPP, Operasional, Lain-lain, Ringkas/Summary
- Input Date Range (dari-sampai)
- Tombol: Generate, Export Excel, Export PDF

**Tampilkan Report**:
- **HPP Report**: Daftar batch, detail material per batch, HPP per unit, total HPP
- **Operasional Report**: Breakdown per kategori, total operasional, detail transaksi
- **Miscellaneous Report**: Breakdown per kategori, total
- **Summary Report**: Total HPP + Operasional + Miscellaneous = Total Pengeluaran

**Export**:
- Excel (.xlsx) dengan multiple sheet (jika laporan summary)
- PDF dengan formatting rapi

---

## 7. VALIDASI & BUSINESS RULES

### Material
- Nama tidak boleh kosong, unique
- Unit harus dipilih dari daftar (kg, liter, pcs, dsb)
- Harga satuan > 0

### Product
- Nama tidak boleh kosong
- Unit output harus dipilih
- Satu produk minimal harus punya resep sebelum bisa input batch

### Recipe
- Product + Material harus unik (tidak boleh ada duplikasi)
- Takaran > 0
- Material harus sudah ada di database

### Production Batch
- Product harus ada
- Jumlah Output > 0
- Tanggal produksi tidak boleh melebihi hari ini
- Biaya tambahan >= 0
- Resep produk harus sudah lengkap

### Biaya (Operasional & Lain-lain)
- Kategori harus dipilih
- Nominal > 0
- Tanggal tidak boleh kosong

---

## 8. TIMELINE & DELIVERABLES

### Phase 1: Setup & Database (2-3 hari)
- [ ] Setup Laravel 12 project
- [ ] Setup database & migration
- [ ] Setup authentication (Breeze)
- [ ] Buat model & relationship
- [ ] Buat seeder (cost_accounts)

### Phase 2: CRUD Operasional (4-5 hari)
- [ ] Material CRUD + views
- [ ] Product CRUD + views
- [ ] Recipe CRUD + views
- [ ] Cost Account setup

### Phase 3: Production Batch & HPP Calculation (3-4 hari)
- [ ] Production Batch CRUD
- [ ] HPP Calculation Service
- [ ] Batch detail views dengan breakdown
- [ ] Testing HPP calculation

### Phase 4: Biaya Operasional & Lain-lain (2 hari)
- [ ] Operational Cost CRUD
- [ ] Miscellaneous Cost CRUD
- [ ] Views & filtering

### Phase 5: Dashboard & Reporting (4-5 hari)
- [ ] Dashboard overview
- [ ] Report page & filtering
- [ ] Export Excel (maatwebsite/excel)
- [ ] Export PDF (barryvdh/dompdf)

### Phase 6: Testing & Refinement (2-3 hari)
- [ ] Unit testing & feature testing
- [ ] UI/UX refinement
- [ ] Performance optimization
- [ ] Bug fixing

### **Total Estimasi: 17-23 hari** (tergantung kompleksitas & revisi)

---

## 9. TESTING CHECKLIST

### Functional Testing
- [ ] Material: CRUD, search, filter
- [ ] Product: CRUD, search
- [ ] Recipe: CRUD, validation (unique product+material)
- [ ] Production Batch: CRUD, HPP calculation, biaya tambahan
- [ ] Operational/Miscellaneous Cost: CRUD
- [ ] Dashboard: summary calculation, chart rendering
- [ ] Reports: filter, calculation, export Excel, export PDF

### Data Validation
- [ ] Negative numbers blocked
- [ ] Empty fields validated
- [ ] Date range validation
- [ ] Unique constraints enforced

### Performance
- [ ] Load time dashboard < 2s
- [ ] Report generation < 5s
- [ ] Export Excel/PDF < 10s

---

## 10. NOTES & ASSUMPTIONS

1. **Single atau Multi-user**: Awal development bisa single-user (no role check), bisa ditambah later
2. **Currency**: Defaultnya Rupiah (IDR), bisa dikonfigurasi
3. **Audit Trail**: Optional, bisa ditambah later (track who created/updated)
4. **Material Price History**: Optional, bisa ditambah later jika perlu tracking harga historical
5. **Backup Database**: Pastikan production punya backup regular
6. **Timezone**: Set ke Asia/Jakarta
7. **Soft Delete**: Rekomendasi gunakan soft delete untuk data critical (product, material)

---

## 11. DEPLOYMENT CONSIDERATIONS

- VPS atau Shared Hosting (Laravel-compatible)
- HTTPS mandatory
- Database backup strategy
- Environment variables (.env) sudah di-ignore di git
- Storage untuk export files (local atau S3)

---

**END OF SPECIFICATION**

Silakan serahkan document ini ke AI Agent untuk development. Jika ada pertanyaan atau klarifikasi lebih lanjut, hubungi stakeholder.