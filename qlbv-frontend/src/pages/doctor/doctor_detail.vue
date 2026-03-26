<template>
  <div class="page-container">
    <!-- HEADER -->
    <div class="detail-header">
      <div class="header-left">
        <button class="btn-back" @click="$router.back()">⬅ Quay Lại</button>
        <h1>Chi Tiết Bác Sĩ</h1>
      </div>
      <div class="header-right">
        <button
          class="btn btn-primary"
          @click="isEdit = true"
          v-if="!isEdit"
        >
          Sửa
        </button>
        <button
          class="btn btn-danger"
          @click="confirmDelete"
          v-if="!isEdit"
        >
           Xoá
        </button>
      </div>
    </div>

    <!-- CONTENT -->
    <div v-if="doctor && !isEdit" class="doctor-detail">
      <!-- MAIN CARD -->
      <div class="main-card">
        <div class="card-grid">
          <div class="left-section">
            <div class="profile-avatar">
              <div class="avatar-large">👨‍⚕️</div>
              <div class="status-badge">Hoạt Động</div>
            </div>

            <!-- RATING -->
            <div class="rating-section">
              <h3>Đánh Giá</h3>
              <div class="rating">
                <span
                  v-for="star in 5"
                  :key="star"
                  :class="{ active: star <= (doctor.rating || 5) }"
                >
                  ⭐
                </span>
              </div>
              <p class="rating-text">{{ doctor.rating || 5 }}/5 ({{ (doctor.rating || 5) * 20 }} đánh giá)</p>
            </div>
          </div>

          <div class="right-section">
            <!-- BASIC INFO -->
            <div class="info-section">
              <h2>{{ doctor.name }}</h2>
              <div class="specialty-badge">{{ doctor.specialty || "Chuyên khoa" }}</div>

              <div class="info-grid">
                <div class="info-row">
                  <span class="label"> Mã Bác Sĩ:</span>
                  <span class="value">{{ doctor.code || "N/A" }}</span>
                </div>
                <div class="info-row">
                  <span class="label"> Phòng Ban:</span>
                  <span class="value">{{ doctor.department?.name || "Chưa xác định" }}</span>
                </div>
                <div class="info-row">
                  <span class="label"> Điện Thoại:</span>
                  <span class="value">{{ doctor.phone || "Không có" }}</span>
                </div>
                <div class="info-row">
                  <span class="label"> Email:</span>
                  <span class="value">{{ doctor.email || "Không có" }}</span>
                </div>
              </div>
            </div>

            <!-- BIOGRAPHY -->
            <div class="bio-section">
              <h3>Giới Thiệu</h3>
              <p>
                {{ doctor.bio || "Bác sĩ nội tổng quát đóng vai trò là bác sĩ chăm sóc ban đầu, là điểm tiếp xúc đầu tiên trong hệ thống y tế cho người lớn. Họ có kiến thức sâu rộng về nhiều hệ cơ quan trong cơ thể, giúp chẩn đoán và điều trị các bệnh nội khoa không cần phẫu thuật." }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- ADDITIONAL INFO -->
      <div class="additional-cards">
        <div class="info-card">
          <div class="card-icon">📋</div>
          <h4>Kinh Nghiệm</h4>
          <p>Hơn 10 năm kinh nghiệm trong ngành</p>
        </div>
        <div class="info-card">
          <div class="card-icon">👥</div>
          <h4>Bệnh Nhân</h4>
          <p>Đã điều trị hơn 5000 bệnh nhân</p>
        </div>
        <div class="info-card">
          <div class="card-icon">⭐</div>
          <h4>Đánh Giá Cao</h4>
          <p>Tỷ lệ hài lòng 98%</p>
        </div>
      </div>
    </div>

    <!-- EDIT MODE -->
    <div v-else-if="isEdit" class="edit-section">
      <div class="edit-card">
        <h2>Cập Nhật Thông Tin Bác Sĩ</h2>

        <div class="form-group">
          <label>Tên Bác Sĩ *</label>
          <input v-model="form.name" placeholder="Nhập tên bác sĩ" />
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Chuyên Khoa *</label>
            <input v-model="form.specialty" placeholder="Ví dụ: Tim Mạch" />
          </div>
          <div class="form-group">
            <label>Mã Bác Sĩ</label>
            <input v-model="form.code" placeholder="Mã" />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>SĐT</label>
            <input v-model="form.phone" placeholder="0123456789" />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input v-model="form.email" type="email" placeholder="email@example.com" />
          </div>
        </div>

        <div class="form-group">
          <label>Giới Thiệu</label>
          <textarea v-model="form.bio" placeholder="Nhập giới thiệu bác sĩ" rows="5"></textarea>
        </div>

        <div class="form-actions">
          <button class="btn btn-secondary" @click="isEdit = false">Huỷ</button>
          <button class="btn btn-primary" @click="updateDoctor" v-if="!loading">
            Cập Nhật
          </button>
          <span v-else class="loading-text">Đang lưu...</span>
        </div>
      </div>
    </div>

    <!-- LOADING/EMPTY STATE -->
    <div v-else class="empty-state">
      <p>Không có dữ liệu</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: ["id"],

  data() {
    return {
      loading: false,
      doctor: null,
      isEdit: false,
      form: {
        name: "",
        specialty: "",
        phone: "",
        email: "",
        code: "",
        bio: "",
      },
    };
  },

  mounted() {
    this.fetchDoctor();
  },

  methods: {
    async fetchDoctor() {
      this.loading = true;
      try {
        const res = await axios.get(
          `http://127.0.0.1:8000/api/doctors/${this.id}`
        );
        this.doctor = res.data;

        this.form = {
          name: this.doctor.name || "",
          specialty: this.doctor.specialty || "",
          phone: this.doctor.phone || "",
          email: this.doctor.email || "",
          code: this.doctor.code || "",
          bio: this.doctor.bio || "",
        };
      } catch (error) {
        console.error(error);
        alert("Lỗi load dữ liệu");
      } finally {
        this.loading = false;
      }
    },

    async updateDoctor() {
      if (!this.form.name || !this.form.specialty) {
        alert("Vui lòng nhập đầy đủ thông tin");
        return;
      }

      this.loading = true;
      try {
        await axios.put(
          `http://127.0.0.1:8000/api/doctors/${this.id}`,
          this.form
        );
        alert("Cập nhật thành công");
        this.isEdit = false;
        this.fetchDoctor();
      } catch (error) {
        alert("Cập nhật thất bại: " + error.response?.data?.message);
      } finally {
        this.loading = false;
      }
    },

    confirmDelete() {
      if (confirm("Bạn có chắc chắn muốn xoá bác sĩ này không?")) {
        this.deleteDoctor();
      }
    },

    async deleteDoctor() {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/doctors/${this.id}`);
        alert("Đã xoá");
        this.$router.push("/doctors");
      } catch (error) {
        alert("Lỗi khi xoá");
      }
    },
  },
};
</script>

<style scoped>
.page-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 0;
}

.detail-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.header-left {
  display: flex;
  align-items: center;
  gap: 20px;
}

.btn-back {
  background: none;
  border: none;
  font-size: 16px;
  color: #0066cc;
  cursor: pointer;
  font-weight: 600;
  padding: 0;
}

.btn-back:hover {
  color: #0052a3;
}

.header-left h1 {
  margin: 0;
  font-size: 24px;
  color: #333;
}

.header-right {
  display: flex;
  gap: 10px;
}

/* DETAIL CONTENT */
.doctor-detail {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.main-card {
  padding: 30px;
}

.card-grid {
  display: grid;
  grid-template-columns: 200px 1fr;
  gap: 40px;
}

.left-section {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.profile-avatar {
  position: relative;
  margin-bottom: 30px;
}

.avatar-large {
  width: 120px;
  height: 120px;
  border-radius: 12px;
  background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 60px;
  box-shadow: 0 4px 12px rgba(0, 102, 204, 0.2);
}

.status-badge {
  position: absolute;
  bottom: 10px;
  right: 10px;
  background: #4caf50;
  color: white;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.rating-section {
  text-align: center;
  width: 100%;
}

.rating-section h3 {
  margin: 0 0 10px 0;
  color: #333;
  font-size: 14px;
}

.rating {
  display: flex;
  justify-content: center;
  gap: 5px;
  margin-bottom: 10px;
}

.rating span {
  font-size: 24px;
  filter: grayscale(100%) brightness(1.5);
}

.rating span.active {
  filter: grayscale(0%) brightness(1);
}

.rating-text {
  margin: 0;
  color: #999;
  font-size: 12px;
}

/* RIGHT SECTION */
.right-section {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.info-section h2 {
  margin: 0 0 10px 0;
  color: #333;
  font-size: 28px;
}

.specialty-badge {
  display: inline-block;
  background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
  color: white;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 20px;
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.info-row {
  display: flex;
  justify-content: space-between;
  padding: 12px;
  background: #f8f9fa;
  border-radius: 8px;
  align-items: center;
}

.info-row .label {
  font-weight: 600;
  color: #333;
  font-size: 13px;
}

.info-row .value {
  color: #666;
  font-size: 13px;
  text-align: right;
  max-width: 150px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.bio-section {
  border-top: 2px solid #e0e6ed;
  padding-top: 20px;
}

.bio-section h3 {
  margin: 0 0 12px 0;
  color: #333;
  font-size: 16px;
}

.bio-section p {
  margin: 0;
  color: #666;
  line-height: 1.6;
  font-size: 14px;
}

/* ADDITIONAL CARDS */
.additional-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  padding: 30px;
  background: #f8f9fa;
}

.info-card {
  background: white;
  padding: 20px;
  border-radius: 12px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.card-icon {
  font-size: 40px;
  margin-bottom: 10px;
}

.info-card h4 {
  margin: 0 0 8px 0;
  color: #333;
  font-size: 14px;
}

.info-card p {
  margin: 0;
  color: #999;
  font-size: 13px;
}

/* EDIT SECTION */
.edit-section {
  background: white;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.edit-card h2 {
  margin: 0 0 25px 0;
  color: #333;
  font-size: 22px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #333;
  font-size: 13px;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 10px 12px;
  border: 2px solid #e0e6ed;
  border-radius: 6px;
  font-size: 14px;
  font-family: inherit;
  transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #0066cc;
  box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

.form-group textarea {
  resize: vertical;
  min-height: 100px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 25px;
  padding-top: 20px;
  border-top: 2px solid #e0e6ed;
}

.loading-text {
  color: #0066cc;
  font-weight: 600;
}

/* BUTTONS */
.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.btn-primary {
  background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
  color: white;
}

.btn-primary:hover {
  box-shadow: 0 4px 12px rgba(0, 102, 204, 0.3);
  transform: translateY(-2px);
}

.btn-secondary {
  background: white;
  border: 2px solid #e0e6ed;
  color: #333;
}

.btn-secondary:hover {
  border-color: #0066cc;
  color: #0066cc;
}

.btn-danger {
  background: #ff4444;
  color: white;
}

.btn-danger:hover {
  background: #cc0000;
  box-shadow: 0 4px 12px rgba(255, 68, 68, 0.3);
}

.empty-state {
  text-align: center;
  padding: 40px;
  background: white;
  border-radius: 12px;
  color: #999;
}

@media (max-width: 768px) {
  .card-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .detail-header {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .additional-cards {
    grid-template-columns: 1fr;
  }

  .info-row {
    flex-direction: column;
    align-items: flex-start;
  }

  .info-row .value {
    text-align: left;
    max-width: 100%;
  }
}
</style>
