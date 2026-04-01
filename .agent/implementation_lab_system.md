# 📋 RENCANA IMPLEMENTASI SISTEM LABORATORIUM SEKOLAH
## Sistem Layanan Laboratorium Berbasis Web dengan Multi-Role & Data Synchronization

---

## 🎯 OBJEKTIF UTAMA

Membangun sistem laboratorium yang:
1. **Multi-Role System** - Admin Lab, Kepala Lab, Kepala Sekolah, Waka Akademik, Guru, Siswa
2. **Data Synchronization** - Siswa dari Sistem Akademik otomatis tersinkronisasi
3. **Real-time Status Updates** - Semua perubahan status otomatis sync ke semua role
4. **Maintainable Architecture** - Clean code, mudah maintenance, tidak mengganggu fungsi lain
5. **Real School Operations** - Mengikuti logika operasional sekolah nyata

---

## 📊 ANALISIS SISTEM YANG ADA

### ✅ Yang Sudah Ada:
1. **Database Tables:**
   - `users` (dengan role: super_admin, admin_lab, siswa, guru, dll)
   - `siswa` (linked to users via user_id)
   - `labor` (laboratorium master)
   - `laboratorium` (jadwal laboratorium)
   - `inventaris` (inventory equipment)
   - `pinjam_labor` (borrowing laboratory)
   - `pinjam_inventaris` (borrowing equipment)
   - `laporan_kerusakan` (damage reports)

2. **Models:**
   - User, Siswa, Labor, Laboratorium, Inventaris, dll
   - Relasi sudah defined (User hasOne Siswa, dll)

3. **Controllers:**
   - Admin Controllers (Inventaris, Laboratorium, Kerusakan, etc)
   - Siswa Controllers (Labor, Jadwal, Inventaris, Laporan)
   - Multi-role system sudah dimulai

### ⚠️ Yang Perlu Diperbaiki/Ditambahkan:

1. **Data Synchronization:**
   - ❌ Belum ada mekanisme otomatis sync data siswa dari Sistem Akademik ke Lab
   - ❌ Status changes tidak otomatis tersinkronisasi ke semua role
   - ❌ Tidak ada Observer/Event untuk tracking perubahan

2. **Multi-Role Management:**
   - ❌ Belum ada role: `kepala_lab`, `kepala_sekolah`, `waka_akademik`
   - ❌ Dashboard per role belum lengkap
   - ❌ Permission management belum terstruktur

3. **Laboratory Features:**
   - ⚠️ Borrowing system masih basic
   - ⚠️ Approval workflow belum lengkap (Kepala Lab → Kepala Sekolah → Waka Akademik)
   - ❌ Notification system belum ada
   - ❌ Activity logs belum ada

4. **Inventory Management:**
   - ⚠️ Kondisi equipment perlu 5-tier system
   - ❌ Belum ada pemisahan jelas antara Alat vs Bahan
   - ❌ Stock tracking untuk Bahan consumable belum ada

---

## 🏗️ ARSITEKTUR IMPLEMENTASI

### Phase 1: Foundation & Data Synchronization ⭐
**Prioritas TINGGI - Fundamental untuk sistem**

#### 1.1 Database Migrations
```
📁 database/migrations/lab_system/
├── 2026_02_10_001_add_lab_roles_to_users.php
├── 2026_02_10_002_create_lab_settings_table.php
├── 2026_02_10_003_enhance_inventaris_table.php
├── 2026_02_10_004_create_pinjam_alat_table.php
├── 2026_02_10_005_create_pinjam_eksternal_table.php
├── 2026_02_10_006_create_pengadaan_table.php
├── 2026_02_10_007_create_kerusakan_enhanced_table.php
└── 2026_02_10_008_create_activity_logs_table.php
```

**Actions:**
- Add roles: `kepala_lab`, `kepala_sekolah`, `waka_akademik`
- Enhance `inventaris` table with kategori (Alat/Bahan), kondisi 5-tier
- Create proper borrowing tables with approval workflow
- Create activity logs for tracking all changes

#### 1.2 Models & Relationships
```
📁 app/Models/Lab/
├── LabSetting.php          # Konfigurasi sistem lab
├── PinjamAlat.php          # Equipment borrowing
├── PinjamEksternal.php     # External party borrowing
├── Pengadaan.php           # Procurement requests
├── KerusakanEnhanced.php   # Enhanced damage reports
└── ActivityLog.php         # Activity tracking
```

**Relations:**
- User → hasMany(PinjamAlat)
- PinjamAlat → belongsTo(User, Inventaris, Labor)
- Pengadaan → belongsTo(User) with approval chains
- All models → morphMany(ActivityLog)

#### 1.3 Observers untuk Data Sync ⭐⭐⭐
```
📁 app/Observers/
├── SiswaObserver.php       # Auto-sync siswa changes
├── PinjamObserver.php      # Track borrowing status changes
├── InventarisObserver.php  # Track inventory changes
└── KerusakanObserver.php   # Track damage report changes
```

**Functionality:**
- `SiswaObserver`: When Siswa created/updated in Akademik → auto available in Lab
- `PinjamObserver`: When status changed → notify all related roles
- Real-time synchronization menggunakan Eloquent Events

#### 1.4 Service Layer (Business Logic)
```
📁 app/Services/Lab/
├── SynchronizationService.php   # Data sync logic
├── BorrowingService.php         # Borrowing workflow
├── ApprovalService.php          # Multi-level approval
├── NotificationService.php      # Notification handling
└── ActivityLogService.php       # Activity tracking
```

---

### Phase 2: Multi-Role Dashboards
**Prioritas TINGGI - User Experience**

#### 2.1 Controllers per Role
```
📁 app/Http/Controllers/Lab/
├── KepalaLabDashboardController.php
├── KepalaSekolahDashboardController.php
├── WakaAkademikDashboardController.php
├── AdminLabDashboardController.php
├── GuruLabController.php
└── SiswaLabController.php (enhance existing)
```

**Responsibilities:**
- **Kepala Lab**: Approve borrowing, manage inventory, procurement recommendations
- **Kepala Sekolah**: Final approval for external borrowing, procurement approval
- **Waka Akademik**: Monitor academic-related lab usage, approve special requests
- **Admin Lab**: Day-to-day operations, manage schedules, inventory
- **Guru**: Request equipment/room, view schedules
- **Siswa**: View schedules, borrow equipment (for use in lab), report damage

#### 2.2 Dashboard Views
```
📁 resources/views/lab/
├── kepala_lab/
│   ├── dashboard.blade.php
│   ├── approvals.blade.php
│   ├── procurement-recommendations.blade.php
│   └── reports.blade.php
├── kepala_sekolah/
│   ├── dashboard.blade.php
│   ├── approvals.blade.php
│   └── procurement-approvals.blade.php
├── waka_akademik/
│   ├── dashboard.blade.php
│   └── monitoring.blade.php
└── shared/
    ├── statistics.blade.php
    └── notifications.blade.php
```

---

### Phase 3: Laboratory Operations
**Prioritas SEDANG - Core Features**

#### 3.1 Borrowing System

**3.1.1 Equipment Borrowing (for Students/Teachers)**
- Students can borrow **Alat** (Equipment) - ONLY for use within the lab
- Teachers can borrow **Alat** and **Ruang Lab** (Lab Room)
- Admin can input on behalf of users
- Approval workflow:
  - Student → Admin Lab → Approved
  - Teacher → Kepala Lab → Approved

**3.1.2 External Borrowing**
- External parties can request equipment
- Workflow: External Party → Admin Lab → Kepala Lab → Kepala Sekolah → Approved

#### 3.2 Inventory Management

**3.2.1 Equipment (Alat)**
- 5-tier condition system:
  - Sangat Baik (96-100%)
  - Baik (76-95%)
  - Cukup (51-75%)
  - Kurang (26-50%)
  - Rusak (0-25%)
- Track usage, maintenance, repairs

**3.2.2 Materials (Bahan)**
- Consumable items (no condition rating)
- Stock tracking with minimum threshold
- Automatic procurement alerts

#### 3.3 Damage Reporting
- Enhanced with photos, severity levels
- Auto-notify: Reporter → Admin Lab → Teknisi → Kepala Lab
- Track repair status and history

#### 3.4 Procurement System
- Kepala Lab recommends procurement
- Kepala Sekolah approves/rejects
- Auto-update inventory when approved

---

### Phase 4: Advanced Features
**Prioritas RENDAH - Nice to Have**

#### 4.1 Notification System
- Real-time notifications using Laravel Notifications
- Channels: Database, Mail (optional)
- Notify on:
  - Borrowing requests
  - Approval/rejection
  - Damage reports
  - Procurement updates

#### 4.2 Activity Logs
- Track all significant actions
- Polymorphic relationship for flexibility
- Display in dashboards for transparency

#### 4.3 Reports & Analytics
- Equipment usage statistics
- Borrowing trends
- Damage frequency
- Procurement history

#### 4.4 Settings & Configuration
- Lab-specific settings (can equipment be borrowed outside?)
- Business hours
- Approval workflows customization

---

## 🔧 IMPLEMENTATION STEPS

### Step 1: Setup Foundation (Phase 1)
1. ✅ Create migrations for new tables
2. ✅ Update existing migrations for enhancements
3. ✅ Run migrations
4. ✅ Create Models with proper relationships
5. ✅ Create Observers for data sync
6. ✅ Register Observers in AppServiceProvider
7. ✅ Create Service classes
8. ✅ Test data synchronization

### Step 2: Build Multi-Role System (Phase 2)
1. ✅ Create Controllers for each role
2. ✅ Create Routes with proper middleware
3. ✅ Create Dashboard Views
4. ✅ Implement role-based redirection
5. ✅ Test access control

### Step 3: Implement Core Features (Phase 3)
1. ✅ Equipment borrowing system
2. ✅ External borrowing system
3. ✅ Inventory management
4. ✅ Damage reporting
5. ✅ Procurement system

### Step 4: Polish & Advanced Features (Phase 4)
1. ✅ Notification system
2. ✅ Activity logs
3. ✅ Reports & analytics
4. ✅ Settings & configuration

---

## 🎨 UI/UX CONSIDERATIONS

### Design Principles:
1. **Card-based layouts** untuk lab/equipment grouping
2. **Color-coded status** untuk quick visual identification
3. **Responsive tables** dengan filtering dan sorting
4. **Modal forms** untuk quick actions
5. **Toast notifications** untuk feedback

### Status Colors:
- 🟢 **Approved** - Green
- 🟡 **Pending** - Yellow/Orange
- 🔴 **Rejected** - Red
- 🔵 **In Progress** - Blue
- ⚫ **Completed** - Gray

---

## 📝 NAMING CONVENTIONS

### Database:
- Tables: `snake_case` plural (e.g., `pinjam_alat`, `activity_logs`)
- Columns: `snake_case` (e.g., `user_id`, `tanggal_pinjam`)

### PHP:
- Models: `PascalCase` singular (e.g., `PinjamAlat`, `ActivityLog`)
- Controllers: `PascalCase` with suffix (e.g., `KepalaLabDashboardController`)
- Services: `PascalCase` with suffix (e.g., `SynchronizationService`)
- Methods: `camelCase` (e.g., `syncSiswaData()`, `approveBorrowing()`)

### Routes:
- Route names: `dot.notation` (e.g., `lab.kepala_lab.dashboard`)
- URLs: `kebab-case` (e.g., `/lab/kepala-lab/dashboard`)

---

## 🔒 SECURITY & VALIDATION

### Middleware:
- `auth` - All lab routes require authentication
- `role:kepala_lab,admin_lab` - Role-based access control
- Custom middleware for ownership verification

### Validation:
- Form Request classes for complex validations
- Server-side validation for all inputs
- CSRF protection on all forms

### Authorization:
- Policy classes for resource authorization
- Gates for specific permissions
- Prevent unauthorized data access

---

## 🧪 TESTING STRATEGY

### Unit Tests:
- Service classes logic
- Observer functionality
- Model relationships

### Feature Tests:
- Controller actions
- Route accessibility
- Data synchronization

### Manual Testing Scenarios:
1. Admin Akademik creates Siswa → Auto available in Lab
2. Siswa borrows equipment → Admin Lab approves → Status updates
3. Damage report → Notifications sent → Repair tracked
4. Kepala Lab recommends procurement → Kepala Sekolah approves

---

## 📚 DOCUMENTATION

### Code Documentation:
- PHPDoc for all classes and methods
- Inline comments for complex logic

### User Documentation:
- User guide per role
- FAQ section
- Video tutorials (optional)

---

## ✅ SUCCESS CRITERIA

1. ✅ Data Siswa otomatis tersinkronisasi dari Sistem Akademik
2. ✅ Semua role memiliki dashboard yang sesuai dengan kebutuhan
3. ✅ Status changes otomatis sync ke semua role terkait
4. ✅ Approval workflow berjalan sesuai hierarki
5. ✅ No disruption to existing features
6. ✅ Clean, maintainable code architecture
7. ✅ Real-time notifications berfungsi
8. ✅ Activity logs mencatat semua perubahan penting

---

## 🚀 ROLLOUT PLAN

### Development (Week 1-2):
- Phase 1: Foundation & Data Sync
- Phase 2: Multi-Role Dashboards

### Testing (Week 3):
- Internal testing
- User acceptance testing (UAT)
- Bug fixes

### Deployment (Week 4):
- Phase 3: Core Features
- Phase 4: Advanced Features
- Production deployment with monitoring

---

## 📞 SUPPORT & MAINTENANCE

### Post-Deployment:
- Monitor error logs
- Collect user feedback
- Iterative improvements
- Performance optimization

### Backup Strategy:
- Database daily backups
- Code versioning with Git
- Rollback plan if issues arise

---

**Created by:** AI Developer Assistant @ Antigravity  
**Date:** 2026-02-10  
**Version:** 1.0
