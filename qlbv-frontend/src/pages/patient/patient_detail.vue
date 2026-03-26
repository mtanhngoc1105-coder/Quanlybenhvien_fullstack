<template>
  <div class="page-container">
    <div class="detail-header">
      <button class="back-btn" @click="$router.back()">⬅ Quay lại</button>
      <h1>Chi Tiết Bệnh Nhân</h1>
      <div class="actions">
        <button class="btn btn-primary" v-if="!isEdit" @click="isEdit = true">✎ Chỉnh Sửa</button>
        <button class="btn btn-danger" v-if="!isEdit" @click="confirmDelete">🗑 Xóa</button>
      </div>
    </div>

    <div v-if="item && !isEdit" class="detail-content">
      <div class="profile-card">
        <div class="avatar-section">
          <div class="avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)">👤</div>
          <span class="badge">ID: {{ item.id }}</span>
        </div>

        <div class="info-section">
          <h2>{{ item.name }}</h2>
          <div class="info-grid">
            <div class="info-item">
              <span class="info-label">Ngày Sinh:</span>
              <span class="info-value">{{ item.dob }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Giới Tính:</span>
              <span class="info-value">{{ formatGender(item.gender) }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Điện Thoại:</span>
              <span class="info-value">{{ item.phone }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Email:</span>
              <span class="info-value">{{ item.email }}</span>
            </div>
            <div class="info-item full">
              <span class="info-label">Địa Chỉ:</span>
              <span class="info-value">{{ item.address }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="isEdit" class="edit-form-container">
      <h2>Chỉnh Sửa Thông Tin Bệnh Nhân</h2>
      <div class="form-grid">
        <div class="form-group">
          <label>Tên</label>
          <input v-model="form.name" placeholder="Nhập tên" />
        </div>
        <div class="form-group">
          <label>Ngày Sinh</label>
          <input type="date" v-model="form.dob" />
        </div>
        <div class="form-group">
          <label>Giới Tính</label>
          <select v-model="form.gender">
            <option value="male">Nam</option>
            <option value="female">Nữ</option>
          </select>
        </div>
        <div class="form-group">
          <label>Điện Thoại</label>
          <input v-model="form.phone" placeholder="Nhập số điện thoại" />
        </div>
        <div class="form-group">
          <label>Email</label>
          <input v-model="form.email" placeholder="Nhập email" />
        </div>
        <div class="form-group full">
          <label>Địa Chỉ</label>
          <input v-model="form.address" placeholder="Nhập địa chỉ" />
        </div>
      </div>
      <div class="form-actions">
        <button class="btn btn-primary" @click="update">Lưu</button>
        <button class="btn btn-secondary" @click="isEdit = false">Hủy</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: ["id"],

  data() {
    return {
      item: null,
      isEdit: false,
      form: {},
    };
  },

  mounted() {
    this.fetch();
  },

  methods: {
    async fetch() {
      try {
        const res = await axios.get(
          `http://127.0.0.1:8000/api/patients/${this.id}`
        );
        this.item = res.data;
        this.form = { ...this.item };
      } catch (error) {
        console.error(error);
        alert("Lỗi load dữ liệu");
      }
    },

    formatGender(g) {
      return g === "male" ? "Nam" : "Nữ";
    },

    async update() {
      try {
        await axios.put(
          `http://127.0.0.1:8000/api/patients/${this.id}`,
          this.form
        );
        alert("Cập nhật thành công");
        this.isEdit = false;
        this.fetch();
      } catch (error) {
        console.error(error);
        alert("Lỗi cập nhật");
      }
    },

    confirmDelete() {
      if (confirm("Bạn có chắc chắn muốn xóa bệnh nhân này không?"))
        this.deleteItem();
    },

    async deleteItem() {
      try {
        await axios.delete(
          `http://127.0.0.1:8000/api/patients/${this.id}`
        );
        alert("Đã xóa");
        this.$router.push("/patients");
      } catch (error) {
        console.error(error);
        alert("Lỗi xóa");
      }
    },
  },
};
</script>

<style scoped>
.page-container {
  padding: 30px;
  max-width: 900px;
  margin: 0 auto;
}

.detail-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 30px;
  border-bottom: 2px solid #e0e6ed;
  padding-bottom: 20px;
}

.back-btn {
  background: none;
  border: none;
  font-size: 16px;
  cursor: pointer;
  color: #667eea;
  font-weight: 600;
  transition: all 0.3s ease;
}

.back-btn:hover { transform: translateX(-4px); }

.detail-header h1 {
  margin: 0;
  font-size: 24px;
  color: #333;
  flex: 1;
  text-align: center;
}

.actions {
  display: flex;
  gap: 10px;
}

.profile-card {
  background: white;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
  display: grid;
  grid-template-columns: 150px 1fr;
  gap: 30px;
}

.avatar-section {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 48px;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
  margin-bottom: 12px;
}

.badge {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.info-section h2 {
  margin: 0 0 20px 0;
  font-size: 22px;
  color: #333;
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.info-item.full { grid-column: span 2; }

.info-label {
  display: block;
  color: #999;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  margin-bottom: 4px;
}

.info-value {
  display: block;
  font-size: 15px;
  color: #333;
  font-weight: 500;
}

.detail-content {
  margin-bottom: 30px;
}

.edit-form-container {
  background: white;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.edit-form-container h2 {
  margin: 0 0 20px 0;
  color: #333;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin-bottom: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.full { grid-column: span 2; }

.form-group label {
  font-weight: 600;
  color: #333;
  margin-bottom: 8px;
  font-size: 13px;
}

.form-group input,
.form-group select {
  padding: 10px 12px;
  border: 2px solid #e0e6ed;
  border-radius: 6px;
  font-size: 14px;
  font-family: inherit;
  transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
  outline: none;
  border-color: #667eea;
}

.form-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
}

.btn {
  padding: 10px 20px;
  border-radius: 6px;
  border: none;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.btn-primary:hover { box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); transform: translateY(-2px); }

.btn-secondary {
  background: white;
  border: 2px solid #e0e6ed;
  color: #333;
}

.btn-secondary:hover { border-color: #667eea; color: #667eea; }

.btn-danger {
  background: #ffe5e5;
  color: #ff4444;
  border: 2px solid #ffcccc;
}

.btn-danger:hover { background: #ff4444; color: white; }

@media (max-width: 768px) {
  .profile-card { grid-template-columns: 1fr; }
  .form-grid { grid-template-columns: 1fr; }
  .info-grid { grid-template-columns: 1fr; }
  .info-item.full { grid-column: span 1; }
  .form-group.full { grid-column: span 1; }
}
</style>