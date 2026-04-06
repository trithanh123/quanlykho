<p align="center">
    <img src="public/images/logo.png" width="150" alt="Warehouse Logo">
</p>

<h1 align="center">Hệ Thống Quản Lý Kho Vận (WMS)</h1>

<p align="center">
    <a href="#"><img src="https://img.shields.io/badge/Laravel-11.x-red" alt="Laravel Version"></a>
    <a href="#"><img src="https://img.shields.io/badge/Tailwind-CSS-blue" alt="Tailwind CSS"></a>
    <a href="#"><img src="https://img.shields.io/badge/MySQL-Latest-orange" alt="MySQL"></a>
    <a href="#"><img src="https://img.shields.io/badge/Status-Developing-green" alt="Status"></a>
</p>

---

## 📦 Giới thiệu dự án
Đây là dự án **Hệ thống Quản lý Kho vận** được xây dựng trên nền tảng Laravel Framework. Hệ thống hỗ trợ doanh nghiệp/cửa hàng kiểm soát luồng hàng hóa nhập - xuất, quản lý tồn kho và phân quyền nhân viên một cách chặt chẽ và trực quan.

**Thông tin sinh viên thực hiện:**
- **Họ và tên:** Phạm Trí Thành
- **MSSV:** DH52201466

---

## ✨ Tính năng chính

### 🔐 Phân quyền người dùng (Role-based Access Control)
- **Admin:** Quản lý toàn bộ hệ thống, nhân viên, hàng hóa và xem tất cả các loại phiếu.
- **Thủ kho (Manager):** Lập phiếu nhập/xuất kho, quản lý danh sách hàng hóa.
- **Tài xế/Nhân viên khác:** Xem danh sách hàng hóa và phiếu xuất kho được giao.

### 📈 Quản lý kho hàng
- **Dashboard trực quan:** Hiển thị danh sách phiếu nhập/xuất mới nhất ngay tại trang chủ.
- **Quản lý sản phẩm:** Thêm mới, chỉnh sửa thông tin hàng hóa, theo dõi số lượng tồn kho thực tế.
- **Phiếu Nhập Kho (Receipts):** Lập phiếu nhập hàng theo mảng sản phẩm, tự động cộng dồn số lượng vào kho.
- **Phiếu Xuất Kho (Issues):** Lập phiếu xuất hàng, tự động trừ số lượng tồn kho và cảnh báo nếu hàng không đủ.

### 💻 Công nghệ sử dụng
- **Backend:** Laravel 11.x (PHP 8.2+)
- **Frontend:** Tailwind CSS, Blade Template, Alpine.js
- **Database:** MySQL
- **Authentication:** Laravel Breeze

---

## 🚀 Hướng dẫn cài đặt (Local)

1. **Clone dự án:**
git clone [https://github.com/trithanh123/quanlykho.git](https://github.com/trithanh123/quanlykho.git)
   cd quanlykho
2. **Cài đặt thư viện:**
composer install
npm install && npm run build
3. **Cấu hình môi trường:**
Copy file .env.example thành .env

Cấu hình DB_DATABASE, DB_USERNAME, DB_PASSWORD phù hợp với máy của bạn.

Chạy lệnh: php artisan key:generate
4. **Chạy Migration & Seeder:**
php artisan migrate --seed
5. **Khởi động Server:**
php artisan serve
   ```bash
   git clone [https://github.com/trithanh123/quanlykho.git](https://github.com/trithanh123/quanlykho.git)
   cd quanlykho
