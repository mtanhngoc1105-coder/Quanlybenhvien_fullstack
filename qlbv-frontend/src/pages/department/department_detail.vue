<template>
  <div class="page-container">
    <div class="detail-header">
      <button class="back-btn" @click="$router.back()">Quay lại</button>
      <h1>Chi Tiết Phòng Ban</h1>
      <div class="actions">
        <button class="btn btn-primary" v-if="!isEdit" @click="isEdit = true">Chỉnh Sửa</button>
        <button class="btn btn-danger" v-if="!isEdit" @click="confirmDelete">Xóa</button>
      </div>
    </div>

    <div v-if="dept && !isEdit" class="detail-content">
      <div class="info-card">
        <div class="card-header" style="background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%)">
          <div class="card-icon">🏥</div>
          <div>
            <h2>{{ dept.name }}</h2>
            <p class="code">Mã: {{ dept.code }}</p>
          </div>
        </div>
        <div class="card-body">
          <div class="info-grid">
            <div class="info-item">
              <span class="info-label">Mã Phòng Ban:</span>
              <span class="info-value">{{ dept.code }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Phòng Ban Cha:</span>
              <span class="info-value">{{ dept.parent?.name || '-' }}</span>
            </div>
            <div class="info-item full">
              <span class="info-label">Mô Tả:</span>
              <span class="info-value">{{ dept.description || '-' }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="isEdit" class="edit-form-container">
      <h2>Chỉnh Sửa Thông Tin Phòng Ban</h2>
      <div class="form-grid">
        <div class="form-group">
          <label>Tên Phòng Ban</label>
          <input v-model="form.name" placeholder="Nhập tên phòng ban" />
        </div>
        <div class="form-group">
          <label>Mã Phòng Ban</label>
          <input v-model="form.code" placeholder="Nhập mã" />
        </div>
        <div class="form-group">
          <label>Parent ID</label>
          <input v-model="form.parent_id" placeholder="Nhập Parent ID" />
        </div>
        <div class="form-group full">
          <label>Mô Tả</label>
          <textarea v-model="form.description" placeholder="Nhập mô tả"></textarea>
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
      dept: null,
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
          `http://127.0.0.1:8000/api/departments/${this.id}`
        );
        this.dept = res.data;
        this.form = {
          name: this.dept.name,
          code: this.dept.code,
          parent_id: this.dept.parent_id,
          description: this.dept.description,
        };
      } catch (error) {
        console.error(error);
        alert("Lỗi load dữ liệu");
      }
    },

    async update() {
      try {
        await axios.put(
          `http://127.0.0.1:8000/api/departments/${this.id}`,
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
      if (confirm("Bạn có chắc chắn muốn xóa phòng ban này không?")) {
        this.deleteDept();
      }
    },

    async deleteDept() {
      try {
        await axios.delete(
          `http://127.0.0.1:8000/api/departments/${this.id}`
        );
        alert("Đã xóa");
        this.$router.push("/departments");
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
  color: #2ecc71;
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

.detail-content {
  margin-bottom: 30px;
}

.info-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.card-header {
  padding: 25px;
  color: white;
  display: flex;
  align-items: center;
  gap: 15px;
}

.card-icon {
  font-size: 40px;
}

.card-header h2 {
  margin: 0 0 5px 0;
  font-size: 22px;
}

.card-header .code {
  margin: 0;
  opacity: 0.9;
  font-size: 13px;
}

.card-body {
  padding: 25px;
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
.form-group textarea {
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
  border-color: #2ecc71;
}

.form-group textarea { resize: vertical; min-height: 80px; }

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
  background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
  color: white;
}

.btn-primary:hover { box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3); transform: translateY(-2px); }

.btn-secondary {
  background: white;
  border: 2px solid #e0e6ed;
  color: #333;
}

.btn-secondary:hover { border-color: #2ecc71; color: #2ecc71; }

.btn-danger {
  background: #ffe5e5;
  color: #ff4444;
  border: 2px solid #ffcccc;
}

.btn-danger:hover { background: #ff4444; color: white; }

@media (max-width: 768px) {
  .form-grid { grid-template-columns: 1fr; }
  .info-grid { grid-template-columns: 1fr; }
  .info-item.full { grid-column: span 1; }
  .form-group.full { grid-column: span 1; }
}
</style>