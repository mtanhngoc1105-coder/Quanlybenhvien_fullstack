<template>
  <div class="medicine-page">
    <!-- Hero Section -->
    <div class="hero-section">
      <div class="hero-content">
        <div class="hero-icon">💊</div>
        <h1>Quản Lý Thuốc</h1>
        <p>Quản lý danh sách thuốc và tồn kho một cách hiệu quả</p>
      </div>
      <button class="btn btn-primary btn-lg" @click="openAdd">
        <span>➕</span> Thêm Thuốc Mới
      </button>
    </div>

    <!-- Search Section -->
    <div class="search-section">
      <div class="search-wrapper">
        <input 
          type="text" 
          v-model="searchText" 
          placeholder="Tìm kiếm theo tên, mã hoặc loại thuốc..." 
          class="search-input"
        />
      </div>
      <button class="btn btn-outline" @click="fetchData">
        <span>↻</span> Làm mới
      </button>
    </div>

    <!-- Stats Section -->
    <div class="stats-section" v-if="list.length > 0">
      <div class="stat-card">
        <div class="stat-icon">💊</div>
        <div class="stat-info">
          <h3>{{ list.length }}</h3>
          <p>Tổng số thuốc</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-info">
          <h3>{{ filteredList.length }}</h3>
          <p>Kết quả tìm kiếm</p>
        </div>
      </div>
    </div>

    <!-- Cards Grid -->
    <div v-if="paginated.length > 0" class="medicines-grid">
      <article 
        v-for="(item, index) in paginated" 
        :key="item.id" 
        class="medicine-card"
        @click="goDetail(item.id)"
      >
        <div class="card-number">{{ index + 1 + (currentPage - 1) * pageSize }}</div>
        
        <div class="card-header-section">
          <h3>{{ item.name }}</h3>
          <div class="card-meta">
            <span v-if="item.stock < 10" class="alert-badge">⚠️ Tồn kho thấp</span>
            <span v-else class="stock-badge">✓ Có sẵn</span>
          </div>
        </div>

        <div class="card-body-section">
          <div class="info-item">
            <span class="info-label">Mã:</span>
            <span class="info-value">{{ item.code }}</span>
          </div>
          <div class="info-item">
            <span class="info-label">Loại:</span>
            <span class="info-value">{{ item.category }}</span>
          </div>
          <div class="info-item">
            <span class="info-label">Giá:</span>
            <span class="info-value price">{{ formatPrice(item.price) }}</span>
          </div>
          <div class="info-item">
            <span class="info-label">Tồn kho:</span>
            <span class="info-value" :style="{ color: item.stock < 10 ? '#dc3545' : '#28a745', fontWeight: 'bold' }">
              {{ item.stock }} {{ item.unit }}
            </span>
          </div>
        </div>

        <div class="card-actions" @click.stop>
          <button class="action-btn edit-btn" @click="openEdit(item)" title="Sửa">
             Sửa
          </button>
          <button class="action-btn delete-btn" @click="confirmDelete(item.id)" title="Xóa">
             Xóa
          </button>
        </div>
      </article>
    </div>

    <!-- Empty State -->
    <div v-else class="empty-state">
      <div class="empty-icon">💊</div>
      <h3>Chưa có thuốc nào</h3>
      <p>Hãy thêm thuốc mới để bắt đầu quản lý</p>
      <button class="btn btn-primary" @click="openAdd">Thêm Thuốc</button>
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="pagination-section">
      <button 
        class="pagination-btn" 
        :disabled="currentPage === 1" 
        @click="currentPage--"
      >
        ← Trước
      </button>
      
      <div class="pagination-info">
        Trang <span class="current">{{ currentPage }}</span> / <span class="total">{{ totalPages }}</span>
      </div>

      <button 
        class="pagination-btn" 
        :disabled="currentPage === totalPages" 
        @click="currentPage++"
      >
        Tiếp → 
      </button>
    </div>

    <!-- Modal -->
    <div v-if="showPopup" class="modal-overlay" @click.self="closePopup">
      <div class="modal">
        <div class="modal-header">
          <h2>{{ isEdit ? " Chỉnh Sửa Thuốc" : " Thêm Thuốc Mới" }}</h2>
          <button class="modal-close-btn" @click="closePopup">✕</button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label>Tên Thuốc <span class="required">*</span></label>
            <input 
              v-model="form.name" 
              type="text"
              placeholder="Nhập tên thuốc"
              class="form-control"
            />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label> Mã <span class="required">*</span></label>
              <input 
                v-model="form.code" 
                type="text"
                placeholder="Nhập mã thuốc"
                class="form-control"
              />
            </div>
            <div class="form-group">
              <label> Loại <span class="required">*</span></label>
              <input 
                v-model="form.category" 
                type="text"
                placeholder="Ví dụ: Kháng sinh"
                class="form-control"
              />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label> Đơn Vị</label>
              <input 
                v-model="form.unit" 
                type="text"
                placeholder="Ví dụ: Viên"
                class="form-control"
              />
            </div>
            <div class="form-group">
              <label> Giá (₫)</label>
              <input 
                v-model.number="form.price" 
                type="number"
                placeholder="0"
                class="form-control"
              />
            </div>
          </div>

          <div class="form-group">
            <label> Tồn Kho</label>
            <input 
              v-model.number="form.stock" 
              type="number"
              placeholder="0"
              class="form-control"
            />
          </div>

          <div class="form-group">
            <label>Mô Tả</label>
            <textarea 
              v-model="form.description"
              placeholder="Nhập mô tả thuốc"
              class="form-control form-textarea"
              rows="3"
            ></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" @click="closePopup">Hủy</button>
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
        code: "",
        category: "",
        unit: "",
        price: "",
        stock: "",
        description: "",
      },
    };
  },

  computed: {
    filteredList() {
      const search = this.searchText.toLowerCase();
      return this.list.filter(
        (item) =>
          item.name.toLowerCase().includes(search) ||
          item.code?.toLowerCase().includes(search) ||
          item.category?.toLowerCase().includes(search)
      );
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
        const res = await axios.get("http://127.0.0.1:8000/api/medicines");
        this.list = res.data.data || [];
        this.currentPage = 1;
      } catch (error) {
        console.error(error);
        alert("Lỗi load dữ liệu");
      }
    },

    formatPrice(price) {
      return Number(price).toLocaleString("vi-VN", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }) + " ₫";
    },

    goDetail(id) {
      this.$router.push(`/medicines/${id}`);
    },

    openAdd() {
      this.isEdit = false;
      this.form = {
        name: "",
        code: "",
        category: "",
        unit: "",
        price: "",
        stock: "",
        description: "",
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
      if (!this.form.name || !this.form.code || !this.form.category) {
        alert("⚠️ Vui lòng nhập đầy đủ thông tin bắt buộc");
        return;
      }

      try {
        if (this.isEdit) {
          await axios.put(
            `http://127.0.0.1:8000/api/medicines/${this.editId}`,
            this.form
          );
        } else {
          await axios.post("http://127.0.0.1:8000/api/medicines", this.form);
        }

        alert(" Thành công");
        this.closePopup();
        this.fetchData();
      } catch (error) {
        console.error(error);
        alert("Lỗi khi lưu dữ liệu");
      }
    },

    confirmDelete(id) {
      if (confirm("❓ Bạn có chắc chắn muốn xóa thuốc này không?")) {
        this.deleteItem(id);
      }
    },

    async deleteItem(id) {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/medicines/${id}`);
        alert("Đã xóa thành công");
        this.fetchData();
      } catch (error) {
        console.error(error);
        alert("Lỗi khi xóa");
      }
    },

    closePopup() {
      this.showPopup = false;
    },
  },
};
</script>

<style scoped>
.medicine-page {
  animation: fadeIn 0.3s ease-out;
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

/* Hero Section */
.hero-section {
  background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
  color: white;
  padding: 2.5rem;
  border-radius: 12px;
  margin-bottom: 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 2rem;
  box-shadow: 0 4px 15px rgba(255, 107, 107, 0.2);
}

.hero-content {
  flex: 1;
}

.hero-icon {
  font-size: 3rem;
  margin-bottom: 0.5rem;
}

.hero-section h1 {
  font-size: 2rem;
  margin: 0.5rem 0;
  font-weight: 700;
}

.hero-section p {
  font-size: 0.95rem;
  opacity: 0.9;
  margin: 0;
}

.btn-lg {
  padding: 0.8rem 1.75rem;
  font-size: 1rem;
  white-space: nowrap;
}

/* Search Section */
.search-section {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.search-wrapper {
  flex: 1;
  min-width: 250px;
}

.search-input {
  width: 100%;
  padding: 0.85rem 1.25rem;
  border: 2px solid #dee2e6;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: white;
  font-family: inherit;
}

.search-input:focus {
  outline: none;
  border-color: #ff6b6b;
  box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
}

/* Stats Section */
.stats-section {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  border-left: 4px solid #ff6b6b;
}

.stat-icon {
  font-size: 2.5rem;
}

.stat-info h3 {
  margin: 0;
  font-size: 1.5rem;
  color: #212529;
}

.stat-info p {
  margin: 0.25rem 0 0 0;
  font-size: 0.85rem;
  color: #6c757d;
}

/* Medicines Grid */
.medicines-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.medicine-card {
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  cursor: pointer;
  border: 1px solid #dee2e6;
  display: flex;
  flex-direction: column;
  position: relative;
}

.medicine-card:hover {
  box-shadow: 0 8px 24px rgba(255, 107, 107, 0.15);
  transform: translateY(-4px);
  border-color: #ff6b6b;
}

.card-number {
  position: absolute;
  top: 12px;
  right: 12px;
  background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
  color: white;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 0.9rem;
}

.card-header-section {
  padding: 1.25rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-bottom: 1px solid #dee2e6;
}

.card-header-section h3 {
  margin: 0;
  font-size: 1.1rem;
  color: #212529;
}

.card-meta {
  margin-top: 0.75rem;
  display: flex;
  gap: 0.5rem;
}

.alert-badge,
.stock-badge {
  display: inline-block;
  padding: 0.3rem 0.75rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}

.alert-badge {
  background: #fff3cd;
  color: #ff9800;
}

.stock-badge {
  background: #d4edda;
  color: #28a745;
}

.card-body-section {
  padding: 1.25rem;
  flex: 1;
}

.info-item {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
  font-size: 0.9rem;
}

.info-item:last-child {
  margin-bottom: 0;
}

.info-label {
  font-weight: 600;
  color: #6c757d;
  white-space: nowrap;
}

.info-value {
  color: #212529;
}

.info-value.price {
  color: #ff6b6b;
  font-weight: 600;
}

.card-actions {
  padding: 1rem 1.25rem;
  border-top: 1px solid #dee2e6;
  display: flex;
  gap: 0.5rem;
}

.action-btn {
  flex: 1;
  padding: 0.6rem 0.75rem;
  border: none;
  border-radius: 6px;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
}

.action-btn.edit-btn {
  background: #e3f2fd;
  color: #0056b3;
}

.action-btn.edit-btn:hover {
  background: #0056b3;
  color: white;
}

.action-btn.delete-btn {
  background: #ffebee;
  color: #dc3545;
}

.action-btn.delete-btn:hover {
  background: #dc3545;
  color: white;
}

/* Empty State */
.empty-state {
  background: white;
  border-radius: 10px;
  padding: 4rem 2rem;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  border: 2px dashed #dee2e6;
}

.empty-icon {
  font-size: 3.5rem;
  margin-bottom: 1rem;
}

.empty-state h3 {
  margin: 0 0 0.5rem 0;
  font-size: 1.3rem;
  color: #212529;
}

.empty-state p {
  margin: 0 0 1.5rem 0;
  color: #6c757d;
  font-size: 0.95rem;
}

/* Pagination */
.pagination-section {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1.5rem;
  padding: 1.5rem;
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.pagination-btn {
  padding: 0.6rem 1.25rem;
  border: 2px solid #dee2e6;
  background: white;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  color: #ff6b6b;
  transition: all 0.3s ease;
}

.pagination-btn:hover:not(:disabled) {
  background: #ff6b6b;
  color: white;
  border-color: #ff6b6b;
  transform: translateY(-2px);
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-info {
  font-weight: 600;
  color: #212529;
}

.pagination-info .current,
.pagination-info .total {
  color: #ff6b6b;
  font-size: 1.1rem;
}

/* Modal */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  animation: fadeIn 0.3s ease-out;
}

.modal {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
  animation: slideUp 0.3s ease-out;
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
  padding: 1.5rem;
  border-bottom: 1px solid #dee2e6;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h2 {
  margin: 0;
  font-size: 1.3rem;
  color: #212529;
}

.modal-close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #6c757d;
  transition: color 0.3s ease;
}

.modal-close-btn:hover {
  color: #212529;
}

.modal-body {
  padding: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group:last-child {
  margin-bottom: 0;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #212529;
  font-size: 0.9rem;
}

.required {
  color: #dc3545;
}

.form-control {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid #dee2e6;
  border-radius: 6px;
  font-size: 0.95rem;
  font-family: inherit;
  transition: all 0.3s ease;
  background: white;
}

.form-control:focus {
  outline: none;
  border-color: #ff6b6b;
  box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
}

.modal-footer {
  padding: 1.5rem;
  border-top: 1px solid #dee2e6;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
  .hero-section {
    flex-direction: column;
    text-align: center;
  }

  .hero-section h1 {
    font-size: 1.5rem;
  }

  .search-section {
    flex-direction: column;
  }

  .medicines-grid {
    grid-template-columns: 1fr;
  }

  .stats-section {
    grid-template-columns: 1fr;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .pagination-section {
    flex-direction: column;
    gap: 1rem;
  }
}
</style>