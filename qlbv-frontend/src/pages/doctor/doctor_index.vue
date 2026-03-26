<template>
  <div class="page-container">
    <!-- PAGE HEADER -->
    <div class="page-header">
      <div class="header-left">
        <h1> Quản Lý Bác Sĩ</h1>
        <p>Danh sách toàn bộ bác sĩ trong bệnh viện</p>
      </div>
      <div class="header-right">
        <button class="btn btn-primary" @click="openAdd">
          <span>+</span> Thêm Bác Sĩ
        </button>
      </div>
    </div>

    <!-- FILTERS & ACTIONS -->
    <div class="toolbar">
      <input
        type="text"
        v-model="searchText"
        placeholder="Tìm kiếm bác sĩ..."
        class="search-input"
      />
      <button class="btn btn-secondary" @click="fetchDoctors">
        Làm mới
      </button>
    </div>

    <!-- CARDS GRID -->
    <div v-if="paginatedDoctors.length > 0" class="cards-grid">
      <div
        v-for="(doctor, index) in paginatedDoctors"
        :key="doctor.id"
        class="doctor-card"
        @click="goDetail(doctor.id)"
      >
        <div class="card-header">
          <div class="doctor-avatar">
            {{ index + 1 + (currentPage - 1) * pageSize }}
          </div>
          <div class="card-actions" @click.stop>
            <button class="icon-btn" @click="openEdit(doctor)" title="Sửa">
              
            </button>
            <button
              class="icon-btn danger"
              @click="confirmDelete(doctor.id)"
              title="Xoá"
            >
              
            </button>
          </div>
        </div>

        <div class="card-body">
          <h3 class="doctor-name">{{ doctor.name }}</h3>
          <p class="doctor-specialty">{{ doctor.specialty || "Chuyên khoa..." }}</p>

          <div class="doctor-info">
            <div class="info-item">
              <span class="label">Khoa:</span>
              <span class="value">{{ doctor.department?.name || "—" }}</span>
            </div>
            <div class="info-item">
              <span class="label">SĐT:</span>
              <span class="value">{{ doctor.phone || "—" }}</span>
            </div>
            <div class="info-item">
              <span class="label">Email:</span>
              <span class="value">{{ doctor.email || "—" }}</span>
            </div>
          </div>

          <div class="card-footer">
            <span class="badge">ID: {{ doctor.id }}</span>
            <span class="badge secondary">{{ doctor.code || "N/A" }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- EMPTY STATE -->
    <div v-else class="empty-state">
      <div class="empty-icon">👨‍⚕️</div>
      <h3>Không có bác sĩ nào</h3>
      <p>Hãy thêm bác sĩ mới để bắt đầu.</p>
      <button class="btn btn-primary" @click="openAdd">+ Thêm Bác Sĩ</button>
    </div>

    <!-- PAGINATION -->
   <template>
  <div class="q-pa-lg">
    <div class="q-gutter-md">
      <q-pagination
        v-model="current"
        max="5"
        direction-links
        flat
        color="grey"
        active-color="primary"
      />

      <q-pagination
        v-model="current"
        max="5"
        direction-links
        outline
        color="orange"
        active-design="unelevated"
        active-color="brown"
        active-text-color="orange"
      />

      <q-pagination
        v-model="current"
        max="5"
        direction-links
        push
        color="teal"
        active-design="push"
        active-color="orange"
      />

      <q-pagination
        v-model="current"
        :max="5"
        direction-links
        unelevated
        color="black"
        active-color="purple"
      />
    </div>
  </div>
</template>
    <!-- MODAL ADD/EDIT -->
    <div v-if="showPopup" class="modal-overlay" @click.self="closePopup">
      <div class="modal-box">
        <div class="modal-header">
          <h2>{{ isEdit ? "Sửa Bác Sĩ" : "Thêm Bác Sĩ Mới" }}</h2>
          <button class="close-btn" @click="closePopup">✕</button>
        </div>

        <div class="modal-body">
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
              <label>SĐT</label>
              <input v-model="form.phone" placeholder="0123456789" />
            </div>
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

        <div class="modal-footer">
          <button class="btn btn-secondary" @click="closePopup">Huỷ</button>
          <button class="btn btn-primary" @click="saveDoctor">
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
  name: "DoctorIndex",

  data() {
    return {
      doctors: [],
      searchText: "",
      currentPage: 1,
      pageSize: 6,

      showPopup: false,
      isEdit: false,
      editId: null,

      form: {
        name: "",
        specialty: "",
        phone: "",
        email: "",
      },
    };
  },

  computed: {
    filteredDoctors() {
      return this.doctors.filter((doctor) => {
        const search = this.searchText.toLowerCase();
        return (
          doctor.name.toLowerCase().includes(search) ||
          doctor.specialty?.toLowerCase().includes(search) ||
          doctor.email?.toLowerCase().includes(search)
        );
      });
    },

    totalPages() {
      return Math.ceil(this.filteredDoctors.length / this.pageSize);
    },

    paginatedDoctors() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.filteredDoctors.slice(start, start + this.pageSize);
    },
  },

  mounted() {
    this.fetchDoctors();
  },

  methods: {
    async fetchDoctors() {
      try {
        const res = await axios.get("http://127.0.0.1:8000/api/doctors");
        this.doctors = res.data.data || [];
        this.currentPage = 1;
      } catch (error) {
        console.error(error);
        alert("Lỗi load dữ liệu");
      }
    },

    goDetail(id) {
      this.$router.push(`/doctors/${id}`);
    },

    openAdd() {
      this.isEdit = false;
      this.editId = null;
      this.form = { name: "", specialty: "", phone: "", email: "" };
      this.showPopup = true;
    },

    openEdit(doctor) {
      this.isEdit = true;
      this.editId = doctor.id;
      this.form = { ...doctor };
      this.showPopup = true;
    },

    async saveDoctor() {
      if (!this.form.name || !this.form.specialty) {
        alert("Vui lòng nhập đầy đủ thông tin");
        return;
      }

      try {
        if (this.isEdit) {
          await axios.put(
            `http://127.0.0.1:8000/api/doctors/${this.editId}`,
            this.form
          );
        } else {
          await axios.post("http://127.0.0.1:8000/api/doctors", this.form);
        }

        alert("Lưu thành công");
        this.closePopup();
        this.fetchDoctors();
      } catch (error) {
        console.error(error);
        alert("Lỗi khi lưu");
      }
    },

    confirmDelete(id) {
      if (confirm("Bạn có chắc chắn muốn xoá bác sĩ này không?")) {
        this.deleteDoctor(id);
      }
    },

    async deleteDoctor(id) {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/doctors/${id}`);
        alert("Đã xoá");
        this.fetchDoctors();
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
  animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
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
  font-weight: 700;
}

.header-left p {
  margin: 5px 0 0 0;
  color: #999;
  font-size: 13px;
  font-weight: 400;
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
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

.doctor-card {
  background: white;
  border: 2px solid #e0e6ed;
  border-radius: 12px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
}

.doctor-card:hover {
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

.doctor-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 16px;
  box-shadow: 0 2px 8px rgba(0, 102, 204, 0.15);
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
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.icon-btn:hover {
  background: #f5f6fa;
  border-color: #0066cc;
  color: #0066cc;
  transform: scale(1.05);
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

.doctor-name {
  margin: 0 0 5px 0;
  font-size: 18px;
  font-weight: 700;
  color: #333;
}

.doctor-specialty {
  margin: 0 0 12px 0;
  color: #0066cc;
  font-size: 13px;
  font-weight: 600;
}

.doctor-info {
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
  background: #0066cc;
  color: white;
  border-radius: 4px;
  font-size: 10px;
  font-weight: 600;
  text-transform: uppercase;
}

.badge.secondary {
  background: #e0e6ed;
  color: #666;
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
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-page:hover:not(:disabled) {
  border-color: #0066cc;
  color: #0066cc;
  background: #f8f9fa;
  transform: translateY(-1px);
}

.btn-page:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-info {
  color: #666;
  font-size: 14px;
  font-weight: 500;
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
  backdrop-filter: blur(2px);
}

.modal-box {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 450px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
  background: linear-gradient(135deg, #f8f9fa 0%, #e8eef7 100%);
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
  transform: scale(1.1);
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
.form-group select {
  width: 100%;
  padding: 10px 12px;
  border: 2px solid #e0e6ed;
  border-radius: 6px;
  font-size: 14px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.form-group input:focus,
.form-group select:focus {
  outline: none;
  border-color: #0066cc;
  box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.form-row .form-group {
  margin-bottom: 0;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 20px;
  border-top: 2px solid #e0e6ed;
  background: #f8f9fa;
}

/* BUTTON STYLES */
.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.btn-primary {
  background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
  color: white;
  box-shadow: 0 2px 8px rgba(0, 102, 204, 0.2);
}

.btn-primary:hover {
  box-shadow: 0 4px 16px rgba(0, 102, 204, 0.3);
  transform: translateY(-2px);
}

.btn-secondary {
  background: white;
  color: #333;
  border: 2px solid #e0e6ed;
}

.btn-secondary:hover {
  border-color: #0066cc;
  color: #0066cc;
  background: #f8f9fa;
  transform: translateY(-1px);
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