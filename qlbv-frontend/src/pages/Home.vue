<template>
  <div class="home-container">
    <!-- Hero Section -->
    <section class="hero-section">
      <div class="hero-content">
        <div class="hero-text">
          <h1 class="hero-title">Hệ Thống Quản Lý Bệnh Viện</h1>
          <p class="hero-subtitle">Giải Pháp Quản Lý Chuyên Nghiệp Cho Các Cơ Sở Y Tế Hiện Đại</p>
          <div class="hero-buttons">
            <button class="btn btn-primary btn-lg" @click="scrollToFeatures">
              Khám Phá Tính Năng
            </button>
            <button class="btn btn-outline btn-lg" @click="navigate('/patients')">
              Bắt Đầu Ngay
            </button>
          </div>
        </div>
        <div class="hero-image">
          <div class="hero-illustration">
            <div class="hospital-icon">🏥</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
      <div class="stats-container">
        <div class="stat-card">
          <div class="stat-icon">👨‍⚕️</div>
          <div class="stat-number">500+</div>
          <div class="stat-label">Bác Sĩ</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">🏥</div>
          <div class="stat-number">50+</div>
          <div class="stat-label">Phòng Ban</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">👤</div>
          <div class="stat-number">10K+</div>
          <div class="stat-label">Bệnh Nhân</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">💊</div>
          <div class="stat-number">1K+</div>
          <div class="stat-label">Loại Thuốc</div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" ref="featuresSection">
      <div class="features-header">
        <h2>Tính Năng Chính</h2>
        <p>Các công cụ quản lý toàn diện cho hệ thống y tế</p>
      </div>

      <div class="features-grid">
        <!-- Feature 1: Doctor Management -->
        <div class="feature-card">
          <div class="feature-icon">👨‍⚕️</div>
          <h3>Quản Lý Bác Sĩ</h3>
          <p>Quản lý thông tin chi tiết, lịch khám, chuyên khoa và tương tác với bệnh nhân của các bác sĩ.</p>
          <button class="feature-btn" @click="navigate('/doctors')">
            Xem Chi Tiết →
          </button>
        </div>

        <!-- Feature 2: Department Management -->
        <div class="feature-card">
          <div class="feature-icon">🏥</div>
          <h3>Quản Lý Phòng Ban</h3>
          <p>Tổ chức hiệu quả các phòng ban, bộ phận y tế và cơ sở vật chất bệnh viện.</p>
          <button class="feature-btn" @click="navigate('/departments')">
            Xem Chi Tiết →
          </button>
        </div>

        <!-- Feature 3: Patient Management -->
        <div class="feature-card">
          <div class="feature-icon">👤</div>
          <h3>Quản Lý Bệnh Nhân</h3>
          <p>Lưu trữ and quản lý hồ sơ bệnh nhân, bệnh sử, tiền sử bệnh và thông tin y tế toàn diện.</p>
          <button class="feature-btn" @click="navigate('/patients')">
            Xem Chi Tiết →
          </button>
        </div>

        <!-- Feature 4: Appointment Management -->
        <div class="feature-card">
          <div class="feature-icon">📅</div>
          <h3>Quản Lý Lịch Hẹn</h3>
          <p>Lập lịch, quản lý và theo dõi các lịch hẹn khám bệnh một cách hiệu quả.</p>
          <button class="feature-btn" @click="navigate('/appointments')">
            Xem Chi Tiết →
          </button>
        </div>

        <!-- Feature 5: Medicine Management -->
        <div class="feature-card">
          <div class="feature-icon">💊</div>
          <h3>Quản Lý Thuốc</h3>
          <p>Quản lý kho thuốc, nhập xuất, tồn kho và theo dõi giá thành thuốc của bệnh viện.</p>
          <button class="feature-btn" @click="navigate('/medicines')">
            Xem Chi Tiết →
          </button>
        </div>

        <!-- Feature 6: Prescription Management -->
        <div class="feature-card">
          <div class="feature-icon">📋</div>
          <h3>Quản Lý Đơn Thuốc</h3>
          <p>Tạo, lưu trữ và quản lý đơn thuốc điện tử với lịch sử chi tiết.</p>
          <button class="feature-btn" @click="navigate('/prescriptions')">
            Xem Chi Tiết →
          </button>
        </div>
      </div>
    </section>

    <!-- Image Gallery Section -->
    <image-gallery />

    <!-- Benefits Section -->
    <section class="benefits-section">
      <div class="benefits-header">
        <h2>Lợi Ích Chính</h2>
        <p>Tại sao chọn hệ thống của chúng tôi</p>
      </div>

      <div class="benefits-grid">
        <div class="benefit-item">
          <div class="benefit-number">1</div>
          <h4>Tăng Hiệu Suất</h4>
          <p>Tự động hóa quy trình quản lý, tiết kiệm thời gian và chi phí vận hành</p>
        </div>

        <div class="benefit-item">
          <div class="benefit-number">2</div>
          <h4>Bảo Mật Dữ Liệu</h4>
          <p>Bảo vệ thông tin bệnh nhân với tiêu chuẩn bảo mật quốc tế</p>
        </div>

        <div class="benefit-item">
          <div class="benefit-number">3</div>
          <h4>Báo Cáo Chi Tiết</h4>
          <p>Phân tích dữ liệu, thống kê hoạt động bệnh viện một cách toàn diện</p>
        </div>

        <div class="benefit-item">
          <div class="benefit-number">4</div>
          <h4>Dễ Sử Dụng</h4>
          <p>Giao diện trực quan, thân thiện, dễ thao tác cho mọi người dùng</p>
        </div>

        <div class="benefit-item">
          <div class="benefit-number">5</div>
          <h4>Hỗ Trợ 24/7</h4>
          <p>Đội ngũ hỗ trợ kỹ thuật sẵn sàng giúp đỡ bất kỳ lúc nào</p>
        </div>

        <div class="benefit-item">
          <div class="benefit-number">6</div>
          <h4>Nâng Cấp Thường Xuyên</h4>
          <p>Cập nhật tính năng mới, cải tiến liên tục dựa trên phản hồi người dùng</p>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
      <div class="cta-content">
        <h2>Sẵn Sàng Chuyển Đổi Số?</h2>
        <p>Bắt đầu sử dụng hệ thống quản lý bệnh viện tiên tiến ngay hôm nay</p>
        <div class="cta-buttons">
          <button class="btn btn-primary btn-lg" @click="navigate('/doctors')">
            Khám Phá Tất Cả Tính Năng
          </button>
          <button class="btn btn-outline btn-lg" @click="contactSupport">
            Liên Hệ Hỗ Trợ
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import ImageGallery from '../components/ImageGallery.vue'

export default {
  name: 'Home',
  components: {
    ImageGallery
  },
  methods: {
    navigate(path) {
      this.$router.push(path);
    },
    scrollToFeatures() {
      this.$refs.featuresSection?.scrollIntoView({ behavior: 'smooth' });
    },
    contactSupport() {
      alert('Liên hệ: info@hospital.vn | 0123 456 789');
    }
  }
}
</script>

<style scoped>
.home-container {
  width: 100%;
}

/* ============ HERO SECTION ============ */
.hero-section {
  background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
  color: white;
  padding: 4rem 2rem;
  border-radius: 8px;
  margin-bottom: 3rem;
  box-shadow: 0 4px 15px rgba(0, 102, 204, 0.3);
}

.hero-content {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  align-items: center;
}

.hero-text {
  animation: slideInLeft 0.8s ease-out;
}

.hero-title {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 1rem;
  line-height: 1.2;
}

.hero-subtitle {
  font-size: 1.1rem;
  opacity: 0.95;
  margin-bottom: 2rem;
  line-height: 1.6;
}

.hero-buttons {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.btn-lg {
  padding: 0.8rem 2rem;
  font-size: 1rem;
}

.hero-image {
  display: flex;
  justify-content: center;
  animation: slideInRight 0.8s ease-out;
}

.hero-illustration {
  width: 300px;
  height: 300px;
  background: rgba(255, 255, 255, 0.15);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.hospital-icon {
  font-size: 8rem;
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}

/* ============ STATS SECTION ============ */
.stats-section {
  background: white;
  padding: 3rem 2rem;
  border-radius: 8px;
  margin-bottom: 3rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.stats-container {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 2rem;
}

.stat-card {
  text-align: center;
  padding: 2rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 8px;
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 102, 204, 0.2);
}

.stat-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.stat-number {
  font-size: 2rem;
  font-weight: 700;
  color: #0066cc;
  margin: 0.5rem 0;
}

.stat-label {
  font-size: 1rem;
  color: #666;
  font-weight: 500;
}

/* ============ FEATURES SECTION ============ */
.features-section {
  padding: 3rem 2rem;
  background: #f8f9fa;
  border-radius: 8px;
  margin-bottom: 3rem;
}

.features-header {
  text-align: center;
  margin-bottom: 3rem;
  max-width: 1200px;
  margin-left: auto;
  margin-right: auto;
}

.features-header h2 {
  font-size: 2rem;
  color: #1a3a52;
  margin-bottom: 0.5rem;
}

.features-header p {
  font-size: 1.1rem;
  color: #666;
}

.features-grid {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}

.feature-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  border: 1px solid #e9ecef;
}

.feature-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0, 102, 204, 0.2);
  border-color: #0066cc;
}

.feature-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.feature-card h3 {
  font-size: 1.3rem;
  color: #1a3a52;
  margin-bottom: 0.75rem;
}

.feature-card p {
  color: #666;
  line-height: 1.6;
  flex-grow: 1;
  margin-bottom: 1.5rem;
}

.feature-btn {
  background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
  align-self: flex-start;
}

.feature-btn:hover {
  transform: translateX(5px);
  box-shadow: 0 4px 12px rgba(0, 102, 204, 0.3);
}

/* ============ BENEFITS SECTION ============ */
.benefits-section {
  padding: 3rem 2rem;
  background: white;
  border-radius: 8px;
  margin-bottom: 3rem;
}

.benefits-header {
  text-align: center;
  margin-bottom: 3rem;
  max-width: 1200px;
  margin-left: auto;
  margin-right: auto;
}

.benefits-header h2 {
  font-size: 2rem;
  color: #1a3a52;
  margin-bottom: 0.5rem;
}

.benefits-header p {
  font-size: 1.1rem;
  color: #666;
}

.benefits-grid {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
}

.benefit-item {
  padding: 2rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  border-radius: 8px;
  border-left: 4px solid #0066cc;
  transition: all 0.3s ease;
}

.benefit-item:hover {
  transform: translateX(5px);
  box-shadow: 0 8px 16px rgba(0, 102, 204, 0.15);
}

.benefit-number {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
  color: white;
  border-radius: 50%;
  font-weight: 700;
  font-size: 1.2rem;
  margin-bottom: 1rem;
}

.benefit-item h4 {
  font-size: 1.1rem;
  color: #1a3a52;
  margin-bottom: 0.5rem;
}

.benefit-item p {
  color: #666;
  font-size: 0.95rem;
  line-height: 1.6;
}

/* ============ CTA SECTION ============ */
.cta-section {
  background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
  color: white;
  padding: 4rem 2rem;
  border-radius: 8px;
  text-align: center;
  margin-bottom: 2rem;
  box-shadow: 0 4px 15px rgba(0, 102, 204, 0.3);
}

.cta-content {
  max-width: 800px;
  margin: 0 auto;
}

.cta-section h2 {
  font-size: 2.2rem;
  margin-bottom: 1rem;
  font-weight: 700;
}

.cta-section p {
  font-size: 1.1rem;
  opacity: 0.95;
  margin-bottom: 2rem;
}

.cta-buttons {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

/* ============ ANIMATIONS ============ */
@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* ============ RESPONSIVE ============ */
@media (max-width: 768px) {
  .hero-content {
    grid-template-columns: 1fr;
    gap: 2rem;
  }

  .hero-title {
    font-size: 2rem;
  }

  .hero-subtitle {
    font-size: 0.95rem;
  }

  .hero-illustration {
    width: 250px;
    height: 250px;
  }

  .hospital-icon {
    font-size: 5rem;
  }

  .hero-buttons {
    flex-direction: column;
  }

  .btn-lg {
    width: 100%;
  }

  .features-grid,
  .benefits-grid,
  .stats-container {
    grid-template-columns: 1fr;
  }

  .cta-buttons {
    flex-direction: column;
  }

  .hero-section,
  .features-section,
  .benefits-section,
  .cta-section {
    padding: 2rem 1rem;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.5rem;
  }

  .features-header h2,
  .benefits-header h2,
  .cta-section h2 {
    font-size: 1.5rem;
  }

  .stat-number {
    font-size: 1.5rem;
  }
}
</style>
