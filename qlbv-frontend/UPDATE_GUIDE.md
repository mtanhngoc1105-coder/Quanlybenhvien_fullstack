# Hướng Dẫn Cập Nhật Các Trang Index Còn Lại

## ✅ Đã Hoàn Thành:
1. ✅ **CSS chính** (`src/assets/main.css`) - Toàn bộ styling chuyên nghiệp
2. ✅ **App.vue** - Header, Navigation, Footer layout chuyên nghiệp  
3. ✅ **AppFooter.vue** - Component Footer tái sử dụng
4. ✅ **prescription_index.vue** - Ví dụ #1 thiết kế chuyên nghiệp
5. ✅ **medicine_index.vue** - Ví dụ #2 thiết kế chuyên nghiệp

## 📋 Cần Cập Nhật:

### 1. **doctor_index.vue** (👨‍⚕️ Quản Lý Bác Sĩ)
**Màu chủ đề:** `#0056b3` (xanh lam)

**Thay đổi chính:**
```javascript
// Hero Section
- Tiêu đề: "Quản Lý Bác Sĩ"
- Icon: 👨‍⚕️
- Màu gradient: linear-gradient(135deg, #0056b3 0%, #003d82 100%)

// Info Items (trong card)
- 👤 Tên: {{ item.name }}
- 🏥 Chuyên khoa: {{ item.specialty }}
- 🏢 Phòng ban: {{ item.department?.name }}
- 📞 Điện thoại: {{ item.phone }}
- 📧 Email: {{ item.email }}
- 🆔 Mã bác sĩ: {{ item.code }}

// Stats
- Tổng bác sĩ: {{ list.length }}
- Kết quả tìm kiếm: {{ filteredDoctors.length }}
```

**Các file để copy:**
- Refer đến `prescription_index.vue` cho cấu trúc HTML
- Refer đến `medicine_index.vue` cho cấu trúc Script logic
- Cập nhật màu từ `#ff6b6b` (medicine) → `#0056b3` (doctor)
- Cập nhật các emoji icons thích hợp

---

### 2. **patient_index.vue** (👤 Quản Lý Bệnh Nhân)
**Màu chủ đề:** `#17a2b8` (xanh lục)

**Thay đổi chính:**
```javascript
// Hero Section
- Tiêu đề: "Quản Lý Bệnh Nhân"
- Icon: 👤
- Màu gradient: linear-gradient(135deg, #17a2b8 0%, #138496 100%)

// Info Items
- 👤 Tên: {{ item.name }}
- 🎂 Ngày sinh: {{ item.dob }}
- 👫 Giới tính: {{ formatGender(item.gender) }}
- 📞 Điện thoại: {{ item.phone }}
- 📧 Email: {{ item.email }}
- 📍 Địa chỉ: {{ item.address }}

// Stats
- Tổng bệnh nhân: {{ list.length }}
- Kết quả tìm kiếm: {{ filteredList.length }}
```

---

### 3. **appointment_index.vue** (📅 Quản Lý Lịch Hẹn)
**Màu chủ đề:** `#28a745` (xanh lá)

**Thay đổi chính:**
```javascript
// Hero Section
- Tiêu đề: "Quản Lý Lịch Hẹn"
- Icon: 📅
- Màu gradient: linear-gradient(135deg, #28a745 0%, #1e7e34 100%)

// Info Items
- 👤 Bệnh nhân: {{ item.patient?.name }}
- 👨‍⚕️ Bác sĩ: {{ item.doctor?.name }}
- 📅 Ngày: {{ item.date }}
- 🕐 Giờ: {{ item.time }}
- 📋 Trạng thái: {{ item.status === 'pending' ? '⏳ Chờ xử lý' : '✅ Hoàn tất' }}
- 📝 Ghi chú: {{ item.note }}

// Stats
- Tổng lịch hẹn: {{ list.length }}
- Dự kiến hôm nay: (tính toán từ date)

// Trạng thái badges
- pending: Màu vàng (#ffc107)
- done: Màu xanh (#28a745)
```

---

### 4. **department_index.vue** (🏢 Quản Lý Phòng Ban)
**Màu chủ đề:** `#ffc107` (vàng)

**Thay đổi chính:**
```javascript
// Hero Section
- Tiêu đề: "Quản Lý Phòng Ban"
- Icon: 🏢
- Màu gradient: linear-gradient(135deg, #ffc107 0%, #ff9800 100%)

// Info Items
- 🏢 Tên phòng: {{ item.name }}
- 📍 Mã phòng: {{ item.code }}
- 📝 Mô tả: {{ item.description }}

// Stats
- Tổng phòng ban: {{ list.length }}
- Kết quả tìm kiếm: {{ filteredList.length }}
```

---

## 🎨 Hướng Dẫn Cập Nhật Từng File

### Bước 1: Copy Cấu Trúc HTML từ `medicine_index.vue`
Tất cả các file index cần có:
- Hero section (thay đổi icon, title, màu)
- Search section (giữ nguyên)
- Stats section (thay đổi stats items)
- Cards grid với info items (thay đổi emoji và labels)
- Empty state (thay đổi icon, message)
- Pagination (giữ nguyên)
- Modal form (thay đổi labels và fields)

### Bước 2: Copy Logic từ `prescription_index.vue`
- Cấu trúc data properties
- Computed properties (filteredList, totalPages, paginated)
- Methods (fetchData, goDetail, openAdd, openEdit, save, confirmDelete, deleteItem, closePopup)
- Chỉ cần customization:
  - API endpoint
  - Form field names
  - Validation rules
  - Format functions (nếu cần)

### Bước 3: Copy CSS Styling
- Sử dụng chính xác từ `medicine_index.vue`
- Thay đổi màu theme từ `#ff6b6b` → màu của module
- Thay đổi `medicine-page` → `[module]-page`
- Thay đổi class names tương ứng

### Bước 4: Customization
- Data properties
- Form validation
- Info items display
- Stats calculation
- Color theme

---

## 🚀 Ví Dụ Chi Tiết - Doctor Index

### Template Structure:
```vue
<template>
  <div class="doctor-page">
    <!-- Hero Section -->
    <div class="hero-section">
      <div class="hero-content">
        <div class="hero-icon">👨‍⚕️</div>
        <h1>Quản Lý Bác Sĩ</h1>
        <p>Quản lý danh sách bác sĩ và chuyên khoa một cách hiệu quả</p>
      </div>
      <button class="btn btn-primary btn-lg" @click="openAdd">
        <span>➕</span> Thêm Bác Sĩ Mới
      </button>
    </div>
    
    <!-- Rest follows medicine_index.vue structure -->
  </div>
</template>
```

### Script Changes:
```javascript
// Chỉ cần thay API endpoint:
async fetchDoctors() {
  const res = await axios.get("http://127.0.0.1:8000/api/doctors");
  this.doctors = res.data.data || [];
}

// Form fields:
form: {
  name: "",
  specialty: "",
  phone: "",
  email: "",
  code: ""
}
```

### Style Theme:
```css
/* Thay từ #ff6b6b sang #0056b3 */
.hero-section {
  background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
}

.pagination-btn {
  color: #0056b3;
}
/* etc... */
```

---

## ✨ Tips Nhanh

1. **Tìm & Replace:**
   - `#ff6b6b` → màu module
   - `medicine` → tên module
   - Emoji icons theo loại

2. **Reuse Pattern:**
   - Tất cả đều dùng `hero-section`, `search-section`, `stats-section`
   - Chỉ thay fields và dữ liệu

3. **Testing:**
   - Sau mỗi update, chạy `npm run dev` để verify
   - Kiểm tra filter, pagination, add/edit/delete

4. **Consistency:**
   - Sử dụng cùng button styles
   - Sử dụng cùng form control styles
   - Sử dụng cùng card layout

---

## 📝 Checklist Khi Cập Nhật

- [ ] Thay icon trên hero section
- [ ] Thay title trên hero section
- [ ] Thay description trên hero section
- [ ] Thay màu gradient (hero + buttons + pagination)
- [ ] Cập nhật info items với correct emojis
- [ ] Cập nhật stats items
- [ ] Cập nhật form fields
- [ ] Cập nhật API endpoints
- [ ] Cập nhật empty state message
- [ ] Test filtering, pagination, add/edit/delete

---

Bạn có thể bắt đầu bằng `doctor_index.vue` vì nó có cấu trúc tương tự `prescription_index.vue`.
Sau đó là `patient_index.vue` vì nó cũng tương tự.
Cuối cùng là `appointment_index.vue` và `department_index.vue`.

Tất cả đều có thể hoàn thành bằng cách copy + customize!
