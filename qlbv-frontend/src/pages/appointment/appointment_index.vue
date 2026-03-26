<template>
  <div class="page-container">
    <div class="page-header">
      <div class="header-left">
        <h1>Quản Lý Lịch Hẹn</h1>
        <p>Danh sách lịch hẹn khám chữa bệnh</p>
      </div>
      <button class="btn btn-primary" @click="openAdd">+ Thêm Lịch Hẹn</button>
    </div>

    <div class="toolbar">
      <input type="text" v-model="searchText" placeholder="Tìm kiếm..." class="search-input" />
      <button class="btn btn-secondary" @click="fetchData">Làm mới</button>
    </div>

    <div v-if="paginated.length > 0" class="cards-grid">
      <div
        v-for="(item, index) in paginated"
        :key="item.id"
        class="card"
        @click="goDetail(item.id)"
      >
        <div class="card-header">
          <div class="avatar" style="background: linear-gradient(135deg, #16a085 0%, #138d75 100%)">
            {{ index + 1 + (currentPage - 1) * pageSize }}
          </div>
          <div class="card-actions" @click.stop>
            <button class="icon-btn" @click="openEdit(item)">✎</button>
            <button class="icon-btn danger" @click="confirmDelete(item.id)">✕</button>
          </div>
        </div>
        <div class="card-body">
          <div class="info">
            <div><span class="label">Bệnh nhân:</span><span>{{ item.patient?.name || item.patient_id }}</span></div>
            <div><span class="label">Bác sĩ:</span> <span>{{ item.doctor?.name || item.doctor_id }}</span></div>
            <div><span class="label">Ngày:</span> <span>{{ item.date }}</span></div>
            <div><span class="label">Giờ:</span> <span>{{ item.time }}</span></div>
            <div><span class="label">Trạng thái:</span> <span :class="'status ' + item.status">{{ item.status === 'pending' ? '⏳ Chờ xử lý' : '✓ Hoàn tất' }}</span></div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="empty-state">
      <p style="font-size: 48px; margin-bottom:10px">📅</p>
      <h3>Không có lịch hẹn nào</h3>
    </div>

    <div v-if="totalPages > 1" class="pagination">
      <button :disabled="currentPage === 1" @click="currentPage--">⬅</button>
      <span>{{ currentPage }} / {{ totalPages }}</span>
      <button :disabled="currentPage === totalPages" @click="currentPage++">➡</button>
    </div>

    <div v-if="showPopup" class="modal-overlay" @click.self="closePopup">
      <div class="modal-box">
        <div class="modal-header">
          <h2>{{ isEdit ? "Sửa Lịch Hẹn" : "Thêm Lịch Hẹn" }}</h2>
          <button class="close-btn" @click="closePopup">✕</button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group"><label>Bệnh nhân ID</label><input v-model="form.patient_id" /></div>
            <div class="form-group"><label>Bác sĩ ID</label><input v-model="form.doctor_id" /></div>
          </div>
          <div class="form-row">
            <div class="form-group"><label>Ngày</label><input type="date" v-model="form.date" /></div>
            <div class="form-group"><label>Giờ</label><input type="time" v-model="form.time" /></div>
          </div>
          <div class="form-group">
            <label>Trạng thái</label>
            <select v-model="form.status">
              <option value="pending">Chờ xử lý</option>
              <option value="done">Hoàn tất</option>
            </select>
          </div>
          <div class="form-group"><label>Ghi chú</label><textarea v-model="form.note"></textarea></div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" @click="closePopup">Huỷ</button>
          <button class="btn btn-primary" @click="save">{{ isEdit ? "Cập Nhật" : "Thêm Mới" }}</button>
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
        patient_id: "",
        doctor_id: "",
        date: "",
        time: "",
        status: "pending",
        note: "",
      },
    };
  },

  computed: {
    filteredList() {
      return this.list.filter((item) => {
        const search = this.searchText.toLowerCase();
        return (
          item.patient?.name?.toLowerCase().includes(search) ||
          item.doctor?.name?.toLowerCase().includes(search) ||
          item.date?.includes(search)
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
        const res = await axios.get("http://127.0.0.1:8000/api/appointments");
        this.list = res.data.data || [];
        this.currentPage = 1;
      } catch (error) {
        console.error(error);
        alert("Lỗi load dữ liệu");
      }
    },

    goDetail(id) {
      this.$router.push(`/appointments/${id}`);
    },

    openAdd() {
      this.isEdit = false;
      this.form = {
        patient_id: "",
        doctor_id: "",
        date: "",
        time: "",
        status: "pending",
        note: "",
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
      if (!this.form.patient_id || !this.form.doctor_id || !this.form.date) {
        alert("Vui lòng nhập đầy đủ thông tin");
        return;
      }
      try {
        if (this.isEdit) {
          await axios.put(
            `http://127.0.0.1:8000/api/appointments/${this.editId}`,
            this.form
          );
        } else {
          await axios.post("http://127.0.0.1:8000/api/appointments", this.form);
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
      if (confirm("Bạn có chắc chắn muốn xoá lịch hẹn này không?"))
        this.deleteItem(id);
    },

    async deleteItem(id) {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/appointments/${id}`);
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

.toolbar {
  display: flex;
  gap: 15px;
  margin-bottom: 25px;
}

.search-input {
  flex: 1;
  min-width: 250px;
  padding: 10px 15px;
  border: 2px solid #e0e6ed;
  border-radius: 8px;
  font-size: 14px;
}

.search-input:focus {
  outline: none;
  border-color: #16a085;
}

.cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.card { background: white; border: 2px solid #e0e6ed; border-radius: 12px; overflow: hidden; cursor: pointer; transition: all 0.3s ease; display: flex; flex-direction: column; }
.card:hover { border-color: #16a085; transform: translateY(-4px); box-shadow: 0 8px 24px rgba(22, 160, 133, 0.12); }

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  background: linear-gradient(135deg, #f8f9fa 0%, #e8f6f3 100%);
  border-bottom: 2px solid #e0e6ed;
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
}

.card-actions { display: flex; gap: 5px; }

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

.icon-btn:hover { background: #f5f6fa; border-color: #16a085; color: #16a085; }
.icon-btn.danger:hover { background: #ffe5e5; border-color: #ff4444; color: #ff4444; }

.card-body { padding: 15px; flex: 1; }

.info {
  font-size: 12px;
}

.info div {
  margin-bottom: 6px;
  display: flex;
  justify-content: space-between;
}

.label {
  color: #999;
  font-weight: 500;
}

.status {
  padding: 3px 8px;
  border-radius: 4px;
  font-weight: 600;
  font-size: 11px;
}

.status.pending { background: #fff3cd; color: #ff9800; }
.status.done { background: #d4edda; color: #28a745; }

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #999;
}

.pagination {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 30px;
}

.pagination button {
  padding: 8px 16px;
  border: 2px solid #e0e6ed;
  background: white;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
}

.pagination button:hover:not(:disabled) { border-color: #16a085; color: #16a085; }
.pagination button:disabled { opacity: 0.5; cursor: not-allowed; }

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-box {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 2px solid #e0e6ed;
}

.modal-header h2 { margin: 0; font-size: 20px; color: #333; }

.close-btn {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #999;
}

.modal-body { padding: 20px; }

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
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #16a085;
}

.form-group textarea { resize: vertical; min-height: 80px; }

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.form-row .form-group { margin-bottom: 0; }

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 20px;
  border-top: 2px solid #e0e6ed;
}

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
  background: linear-gradient(135deg, #16a085 0%, #138d75 100%);
  color: white;
}

.btn-primary:hover { box-shadow: 0 4px 12px rgba(22, 160, 133, 0.3); transform: translateY(-2px); }

.btn-secondary { background: white; border: 2px solid #e0e6ed; color: #333; }
.btn-secondary:hover { border-color: #16a085; color: #16a085; }

@media (max-width: 768px) {
  .page-container { padding: 20px; }
  .page-header { flex-direction: column; gap: 15px; align-items: flex-start; }
  .toolbar { flex-direction: column; }
  .search-input { min-width: 100%; }
  .cards-grid { grid-template-columns: 1fr; }
  .form-row { grid-template-columns: 1fr; }
}
</style>