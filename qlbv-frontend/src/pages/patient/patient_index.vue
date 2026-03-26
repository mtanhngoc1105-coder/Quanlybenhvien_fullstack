<template>
  <div class="page-container">
    <!-- PAGE HEADER -->
    <div class="page-header">
      <div class="header-left">
        <h1>Quản Lý Bệnh Nhân</h1>
        <p>Danh sách toàn bộ bệnh nhân đang theo dõi</p>
      </div>
      <div class="header-right">
        <button class="btn btn-primary" @click="openAdd">
          <span>+</span> Thêm Bệnh Nhân
        </button>
      </div>
    </div>

    <!-- FILTERS & ACTIONS -->
    <div class="toolbar">
      <input
        type="text"
        v-model="searchText"
        placeholder="Tìm kiếm bệnh nhân..."
        class="search-input"
      />
      <button class="btn btn-secondary" @click="fetchData">
        Làm mới
      </button>
    </div>

    <!-- CARDS GRID -->
    <div v-if="paginated.length > 0" class="cards-grid">
      <div
        v-for="(item, index) in paginated"
        :key="item.id"
        class="patient-card"
        @click="goDetail(item.id)"
      >
        <div class="card-header">
          <div class="patient-avatar">
            {{ index + 1 + (currentPage - 1) * pageSize }}
          </div>
          <div class="card-actions" @click.stop>
            <button class="icon-btn" @click="openEdit(item)" title="Sửa">
              
            </button>
            <button
              class="icon-btn danger"
              @click="confirmDelete(item.id)"
              title="Xoá"
            >
              
            </button>
          </div>
        </div>

        <div class="card-body">
          <h3 class="patient-name">{{ item.name }}</h3>

          <div class="patient-info">
            <div class="info-item">
              <span class="label">Giới tính:</span>
              <span class="value">{{ formatGender(item.gender) }}</span>
            </div>
            <div class="info-item">
              <span class="label">Ngày sinh:</span>
              <span class="value">{{ item.dob }}</span>
            </div>
            <div class="info-item">
              <span class="label">SĐT:</span>
              <span class="value">{{ item.phone || "—" }}</span>
            </div>
            <div class="info-item">
              <span class="label">Email:</span>
              <span class="value">{{ item.email || "—" }}</span>
            </div>
          </div>

          <div class="card-footer">
            <span class="badge">ID: {{ item.id }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- EMPTY STATE -->
    <div v-else class="empty-state">
      <div class="empty-icon">👤</div>
      <h3>Không có bệnh nhân nào</h3>
      <p>Hãy thêm bệnh nhân mới để bắt đầu.</p>
      <button class="btn btn-primary" @click="openAdd">+ Thêm Bệnh Nhân</button>
    </div>

    <!-- PAGINATION -->
    <div v-if="totalPages > 1" class="pagination-container">
      <button
        class="btn-page"
        :disabled="currentPage === 1"
        @click="currentPage--"
      >
        ⬅ Trước
      </button>

      <div class="page-info">
        Trang <strong>{{ currentPage }}</strong> / <strong>{{ totalPages }}</strong>
        ({{ list.length }} bệnh nhân)
      </div>

      <button
        class="btn-page"
        :disabled="currentPage === totalPages"
        @click="currentPage++"
      >
        Sau ➡
      </button>
    </div>

    <!-- MODAL ADD/EDIT -->
    <div v-if="showPopup" class="modal-overlay" @click.self="closePopup">
      <div class="modal-box">
        <div class="modal-header">
          <h2>{{ isEdit ? "Sửa Bệnh Nhân" : "Thêm Bệnh Nhân Mới" }}</h2>
          <button class="close-btn" @click="closePopup">✕</button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label>Tên Bệnh Nhân *</label>
            <input v-model="form.name" placeholder="Nhập tên bệnh nhân" />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Ngày Sinh *</label>
              <input v-model="form.dob" type="date" />
            </div>

            <div class="form-group">
              <label>Giới Tính</label>
              <select v-model="form.gender">
                <option value="male">Nam</option>
                <option value="female">Nữ</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>SĐT</label>
              <input v-model="form.phone" placeholder="0123456789" />
            </div>

            <div class="form-group">
              <label>Email</label>
              <input
                v-model="form.email"
                type="email"
                placeholder="email@example.com"
              />
            </div>
          </div>

          <div class="form-group">
            <label>Địa Chỉ</label>
            <textarea v-model="form.address" placeholder="Nhập địa chỉ" rows="3"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" @click="closePopup">Huỷ</button>
          <button class="btn btn-primary" @click="save">
            {{ isEdit ? "Cập Nhật" : "Thêm Mới" }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      list: [],
      searchText: "",
      currentPage: 1,
      pageSize: 6,

      showPopup: false,
      isEdit: false,
      editId: null,

      form: {
        name: "",
        dob: "",
        gender: "male",
        phone: "",
        email: "",
        address: "",
      },
    };
  },

  computed: {
    filteredList() {
      return this.list.filter((item) => {
        const search = this.searchText.toLowerCase();
        return (
          item.name.toLowerCase().includes(search) ||
          item.phone?.toLowerCase().includes(search) ||
          item.email?.toLowerCase().includes(search)
        );
      });
    },

    totalPages() {
      return Math.ceil(this.filteredList.length / this.pageSize);
    },

    paginated() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.filteredList.slice(start, start + this.pageSize);
    },
  },

  mounted() {
    this.fetchData();
  },

  methods: {
    async fetchData() {
      try {
        const res = await axios.get("http://127.0.0.1:8000/api/patients");
        this.list = res.data.data || [];
        this.currentPage = 1;
      } catch (error) {
        console.error(error);
        alert("Lỗi load dữ liệu");
      }
    },

    formatGender(g) {
      return g === "male" ? "Nam" : "Nữ";
    },

    goDetail(id) {
      this.$router.push(`/patients/${id}`);
    },

    openAdd() {
      this.isEdit = false;
      this.form = {
        name: "",
        dob: "",
        gender: "male",
        phone: "",
        email: "",
        address: "",
      };
      this.showPopup = true;
    },

    openEdit(item) {
      this.isEdit = true;
      this.editId = item.id;
      this.form = { ...item };
      this.showPopup = true;
    },

    async save() {
      if (!this.form.name || !this.form.dob) {
        alert("Vui lòng nhập đầy đủ thông tin");
        return;
      }

      try {
        if (this.isEdit) {
          await axios.put(
            `http://127.0.0.1:8000/api/patients/${this.editId}`,
            this.form
          );
        } else {
          await axios.post("http://127.0.0.1:8000/api/patients", this.form);
        }

        alert("Thành công");
        this.closePopup();
        this.fetchData();
      } catch (error) {
        console.error(error);
        alert("Lỗi khi lưu");
      }
    },

    confirmDelete(id) {
      if (confirm("Bạn có chắc chắn muốn xoá bệnh nhân này không?"))
        this.deleteItem(id);
    },

    async deleteItem(id) {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/patients/${id}`);
        alert("Đã xoá");
        this.fetchData();
      } catch (error) {
        console.error(error);
        alert("Lỗi khi xoá");
      }
    },

    closePopup() {
      this.showPopup = false;
    },
  },
};
</script>

<style scoped>
.page-container {
  background: white;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  border-bottom: 2px solid #e0e6ed;
  padding-bottom: 20px;
}

.header-left h1 {
  margin: 0;
  font-size: 28px;
  color: #333;
}

.header-left p {
  margin: 5px 0 0 0;
  color: #999;
  font-size: 13px;
}

.header-right {
  display: flex;
  gap: 10px;
  align-items: center;
}

.toolbar {
  display: flex;
  gap: 15px;
  margin-bottom: 25px;
  flex-wrap: wrap;
}

.search-input {
  flex: 1;
  min-width: 250px;
  padding: 10px 15px;
  border: 2px solid #e0e6ed;
  border-radius: 8px;
  font-size: 14px;
  transition: border-color 0.3s ease;
}

.search-input:focus {
  outline: none;
  border-color: #0066cc;
  box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

.cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.patient-card {
  background: white;
  border: 2px solid #e0e6ed;
  border-radius: 12px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
}

.patient-card:hover {
  border-color: #0066cc;
  box-shadow: 0 8px 24px rgba(0, 102, 204, 0.12);
  transform: translateY(-4px);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  background: linear-gradient(135deg, #f8f9fa 0%, #e8eef7 100%);
  border-bottom: 2px solid #e0e6ed;
}

.patient-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 16px;
}

.card-actions {
  display: flex;
  gap: 5px;
}

.icon-btn {
  width: 32px;
  height: 32px;
  border-radius: 6px;
  border: 1px solid #ddd;
  background: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  transition: all 0.3s ease;
}

.icon-btn:hover {
  background: #f5f6fa;
  border-color: #667eea;
  color: #667eea;
}

.icon-btn.danger:hover {
  background: #ffe5e5;
  border-color: #ff4444;
  color: #ff4444;
}

.card-body {
  padding: 15px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.patient-name {
  margin: 0 0 12px 0;
  font-size: 18px;
  font-weight: 700;
  color: #333;
}

.patient-info {
  margin-bottom: 12px;
  flex: 1;
}

.info-item {
  display: flex;
  justify-content: space-between;
  font-size: 12px;
  margin-bottom: 6px;
  padding: 4px 0;
}

.info-item .label {
  color: #999;
  font-weight: 500;
}

.info-item .value {
  color: #333;
  font-weight: 600;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  max-width: 150px;
}

.card-footer {
  display: flex;
  gap: 8px;
  border-top: 1px solid #e0e6ed;
  padding-top: 10px;
}

.badge {
  display: inline-block;
  padding: 4px 8px;
  background: #667eea;
  color: white;
  border-radius: 4px;
  font-size: 10px;
  font-weight: 600;
  text-transform: uppercase;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
}

.empty-icon {
  font-size: 64px;
  margin-bottom: 15px;
  opacity: 0.5;
}

.empty-state h3 {
  margin: 0 0 10px 0;
  color: #333;
  font-size: 20px;
}

.empty-state p {
  margin: 0 0 20px 0;
  color: #999;
}

.pagination-container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
  padding-top: 20px;
  border-top: 2px solid #e0e6ed;
}

.btn-page {
  padding: 8px 16px;
  background: white;
  border: 2px solid #e0e6ed;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-page:hover:not(:disabled) {
  border-color: #667eea;
  color: #667eea;
  background: #f8f9fa;
}

.btn-page:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-info {
  color: #666;
  font-size: 14px;
}

/* MODAL STYLES */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.modal-box {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  animation: slideUp 0.3s ease;
}

@keyframes slideUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 2px solid #e0e6ed;
}

.modal-header h2 {
  margin: 0;
  font-size: 20px;
  color: #333;
}

.close-btn {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #999;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.close-btn:hover {
  color: #333;
}

.modal-body {
  padding: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #333;
  font-size: 13px;
}

.form-group input,
.form-group select,
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
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.form-row .form-group {
  margin-bottom: 0;
}

.form-group textarea {
  resize: vertical;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 20px;
  border-top: 2px solid #e0e6ed;
}

/* BUTTON STYLES */
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
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.btn-primary:hover {
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
  transform: translateY(-2px);
}

.btn-secondary {
  background: white;
  color: #333;
  border: 2px solid #e0e6ed;
}

.btn-secondary:hover {
  border-color: #667eea;
  color: #667eea;
  background: #f8f9fa;
}

@media (max-width: 768px) {
  .page-container {
    padding: 20px 15px;
  }

  .page-header {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
  }

  .toolbar {
    flex-direction: column;
  }

  .search-input {
    min-width: 100%;
  }

  .cards-grid {
    grid-template-columns: 1fr;
    gap: 15px;
  }

  .modal-box {
    width: 95%;
  }

  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>