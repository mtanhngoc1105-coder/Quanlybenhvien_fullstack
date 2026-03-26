<template>
  <footer class="app-footer">
    <div class="footer-content">
      <div class="footer-section">
        <h4> Về Hệ Thống</h4>
        <p style="color: #adb5bd; font-size: 0.9rem; line-height: 1.6;">
          Hệ thống quản lý bệnh viện chuyên nghiệp giúp quản lý hiệu quả các dịch vụ y tế, bệnh nhân, bác sĩ và các hoạt động bệnh viện.
        </p>
      </div>

      <div class="footer-section">
        <h4>Danh Mục</h4>
        <ul>
          <li><a href="javascript:" @click="$emit('navigate', '/patients')">Quản Lý Bệnh Nhân</a></li>
          <li><a href="javascript:" @click="$emit('navigate', '/doctors')">Quản Lý Bác Sĩ</a></li>
          <li><a href="javascript:" @click="$emit('navigate', '/departments')">Danh Sách Phòng Ban</a></li>
          <li><a href="javascript:" @click="$emit('navigate', '/prescriptions')">Đơn Thuốc</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h4>Liên Hệ</h4>
        <ul>
          <li>Điện Thoại: 0123 456 789</li>
          <li>Email: info@hospital.vn</li>
          <li>Địa Chỉ: Hà Nội, Việt Nam</li>
          <li>Giờ Làm Việc: 8:00 - 18:00</li>
        </ul>
      </div>

      <div class="footer-section">
        <h4>Mạng Xã Hội</h4>
        <ul>
          <li><a href="javascript:">Facebook</a></li>
          <li><a href="javascript:">Twitter</a></li>
          <li><a href="javascript:">Instagram</a></li>
          <li><a href="javascript:">LinkedIn</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; 2024 Hệ Thống Quản Lý Bệnh Viện. Bảo Lưu Mọi Quyền.</p>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter',
}
</script>

<style scoped>
.app-footer {
  background: linear-gradient(135deg, #0f3460 0%, #0a1f35 100%);
  color: white;
  padding: 3rem 1.5rem 1.5rem;
  margin-top: auto;
  border-top: 3px solid #0066cc;
  box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
}

.footer-content {
  max-width: 1400px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.footer-section h4 {
  margin-bottom: 1.25rem;
  font-size: 1.05rem;
  color: #ffffff;
  font-weight: 600;
  letter-spacing: 0.3px;
}

.footer-section ul {
  list-style: none;
}

.footer-section ul li {
  margin-bottom: 0.75rem;
  color: #b3c0d1;
  font-size: 0.95rem;
}

.footer-section a {
  color: #b3c0d1;
  text-decoration: none;
  transition: all 0.3s ease;
  font-weight: 500;
}

.footer-section a:hover {
  color: #0099ff;
  transform: translateX(4px);
}

.footer-section p {
  margin: 0;
  color: #b3c0d1;
  line-height: 1.6;
  font-size: 0.95rem;
}

.footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.2);
  padding-top: 1.5rem;
  text-align: center;
  color: #7a8fa3;
  font-size: 0.9rem;
  font-weight: 500;
}

.footer-bottom p {
  margin: 0;
}
</style>
