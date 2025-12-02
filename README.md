# BiayaKu - Sistem Perhitungan Biaya Usaha

Sistem web-based untuk menghitung biaya operasional, HPP (Harga Pokok Penjualan), dan biaya lain-lain terkait usaha dengan sistem takaran/resep terintegrasi. Dibangun dengan Laravel 12 dan mendukung multi-branch dengan subscription management.

## ✨ Fitur Utama

### 🏭 Manajemen Produksi
- **Material Management**: CRUD bahan baku dengan harga satuan terkini
- **Product Management**: Kelola produk dengan unit output
- **Recipe System**: Sistem takaran/resep per produk
- **Production Batch**: Pencatatan batch produksi dengan perhitungan HPP otomatis
- **HPP Calculation**: Hitung HPP berdasarkan resep + harga material real-time

### 💰 Manajemen Biaya
- **Biaya Operasional**: Pencatatan biaya harian/bulanan (gaji, sewa, listrik, dll)
- **Biaya Lain-lain**: Biaya tak terduga (administrasi, denda, perizinan)
- **Cost Categories**: Kategorisasi biaya untuk reporting yang lebih baik

### 📊 Dashboard & Reporting
- **Dashboard Overview**: Ringkasan biaya harian/bulanan/YTD
- **Advanced Reports**: Laporan HPP, biaya operasional, dan ringkasan keuangan
- **Export Features**: Export ke Excel (.xlsx) dan PDF
- **Date Filtering**: Filter laporan berdasarkan periode

### 🏢 Multi-Branch Support
- **Branch Management**: Kelola multiple cabang perusahaan
- **Branch Isolation**: Data terpisah per cabang
- **Branch Switching**: User dapat berpindah antar cabang

### 👥 User Management & Subscription
- **Role-Based Access**: Super Admin, Admin, User, Viewer
- **Subscription Plans**: Free, Pro, Enterprise dengan limits berbeda
- **Subscription Management**: Admin dapat approve/reject subscription
- **Automatic Expiration**: Subscription expired otomatis setelah periode berakhir

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js 18+ (untuk Vite)
- MySQL 8.0+ atau PostgreSQL
- Git

### Installation

1. **Clone Repository**
```bash
git clone https://github.com/your-username/biayaku.git
cd biayaku
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Configuration**
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biayaku
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Database Migration & Seeding**
```bash
php artisan migrate
php artisan db:seed
```

6. **Build Assets**
```bash
npm run build
# atau untuk development:
npm run dev
```

7. **Start Server**
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 📋 Default Accounts

### Super Admin
- **Email**: superadmin@example.com
- **Password**: password
- **Role**: Super Admin (akses penuh, unlimited)

### Admin
- **Email**: admin@example.com
- **Password**: password
- **Role**: Admin (kelola subscription, users)

### Regular User
- **Email**: user@example.com
- **Password**: password
- **Role**: User (akses terbatas sesuai subscription)

## 🏗️ Architecture

### Tech Stack
- **Backend**: Laravel 12 (PHP Framework)
- **Frontend**: Laravel Blade + Tailwind CSS
- **Database**: MySQL/PostgreSQL dengan Eloquent ORM
- **Authentication**: Laravel Breeze
- **Export**: Maatwebsite Excel, Barryvdh DomPDF
- **Scheduling**: Laravel Task Scheduling

### Database Schema
```sql
-- Core Tables
materials, products, recipes, production_batches
cost_accounts, operational_costs, miscellaneous_costs
users, branches, user_subscriptions, subscription_plans
```

### Key Services
- **HPPCalculationService**: Perhitungan HPP otomatis
- **SubscriptionMiddleware**: Kontrol akses berdasarkan subscription
- **ExpireSubscriptions**: Command untuk expire subscription

## 📊 Subscription Plans

| Feature | Free | Pro | Enterprise |
|---------|------|-----|------------|
| **Materials** | 10 | 200 | Unlimited |
| **Products** | 5 | 100 | Unlimited |
| **Production Batches** | 10 | 200 | Unlimited |
| **Users per Branch** | 1 | 10 | Unlimited |
| **Export Excel** | ❌ | ✅ | ✅ |
| **Export PDF** | ❌ | ✅ | ✅ |
| **Multi-branch** | ❌ | ✅ | ✅ |
| **Advanced Reports** | ❌ | ✅ | ✅ |
| **API Access** | ❌ | ❌ | ✅ |
| **Priority Support** | ❌ | ✅ | ✅ |

## 🛠️ Usage Guide

### 1. Setup Initial Data
```bash
# 1. Login sebagai Super Admin
# 2. Buat Branch baru (jika multi-branch)
# 3. Tambah Materials
# 4. Tambah Products
# 5. Buat Recipes untuk setiap product
```

### 2. Production Flow
```bash
# 1. Input Production Batch
# 2. Sistem auto-hitug HPP berdasarkan resep
# 3. Tambah biaya tambahan jika ada
# 4. Simpan dan lihat detail HPP
```

### 3. Cost Management
```bash
# 1. Input Biaya Operasional harian/bulanan
# 2. Input Biaya Lain-lain jika ada
# 3. Lihat dashboard untuk ringkasan
```

### 4. Reporting
```bash
# 1. Pilih periode laporan
# 2. Generate laporan HPP/Operasional/Summary
# 3. Export ke Excel atau PDF
```

## 🔧 Commands

### Subscription Management
```bash
# Expire subscriptions yang sudah habis masa berlaku
php artisan subscriptions:expire

# Dry run (lihat yang akan di-expire tanpa eksekusi)
php artisan subscriptions:expire --dry-run
```

### Database Management
```bash
# Fresh migration dengan seeder
php artisan migrate:fresh --seed

# Jalankan scheduler (untuk production)
php artisan schedule:run
```

## 🔐 Security Features

- **Role-Based Access Control**: Super Admin, Admin, User, Viewer
- **Branch Data Isolation**: User hanya akses data branch mereka
- **Subscription Enforcement**: Limits diterapkan berdasarkan plan
- **CSRF Protection**: Laravel built-in CSRF protection
- **Input Validation**: Comprehensive validation pada semua form
- **SQL Injection Prevention**: Eloquent ORM protection

## 📈 Performance

- **Database Indexing**: Optimized indexes pada foreign keys dan search fields
- **Query Optimization**: Eager loading untuk relationships
- **Caching**: Laravel caching untuk frequent queries
- **Pagination**: Efficient pagination untuk large datasets

## 🚀 Deployment

### Production Setup
```bash
# 1. Setup production server (VPS/Cloud)
composer install --optimize-autoloader --no-dev
npm run build

# 2. Environment configuration
cp .env.example .env
# Edit .env dengan production values

# 3. Database setup
php artisan migrate --seed
php artisan key:generate

# 4. Storage setup
php artisan storage:link

# 5. Queue worker (jika menggunakan queue)
php artisan queue:work

# 6. Scheduler setup (crontab)
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Environment Variables
```env
APP_NAME="BiayaKu"
APP_ENV=production
APP_KEY=base64:key
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=biayaku_prod
DB_USERNAME=prod_user
DB_PASSWORD=secure_password

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=secure-password
```

## 🐛 Troubleshooting

### Common Issues

1. **Subscription limits not working**
```bash
# Check user subscription status
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->hasActiveSubscription()
>>> $user->getUsageLimit('products')
```

2. **HPP calculation issues**
```bash
# Check recipe completeness
php artisan tinker
>>> $product = App\Models\Product::find(1);
>>> $product->recipes
```

3. **Permission issues**
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

- **Documentation**: [Wiki](https://github.com/your-username/biayaku/wiki)
- **Issues**: [GitHub Issues](https://github.com/your-username/biayaku/issues)
- **Email**: support@biayaku.com

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Maatwebsite Excel
- Barryvdh DomPDF
- Laravel Community

---

**BiayaKu** - Membuat perhitungan biaya usaha jadi lebih mudah dan akurat! 🚀
