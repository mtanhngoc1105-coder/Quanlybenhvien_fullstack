<template>
  <header class="app-header">
    <!-- Top Header with Logo -->
    <div class="header-top">
      <div class="header-container">
        <div class="logo-section">
          <a href="javascript:" @click="navigate('/')" class="logo-link">
            <div class="">
              <img class="doc-header__logo-img" src="/public/images/logo/logo_1.png" alt="Quasar Logo" width="48" height="48">
            </div>

            <div class="logo-text">
              <h1>QUẢN LÝ BỆNH VIỆN</h1>
              <p>Hệ Thống Quản Lý Chuyên Nghiệp</p>
            </div>
          </a>
        </div>
      </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="nav-bar">
      <div class="header-container">
        <div class="nav-wrapper">
          <ul class="nav-menu">
            <li>
              <a href="javascript:" @click="navigate('/')" class="nav-link">
                Trang Chủ
              </a>
            </li>
            <li>
              <a href="javascript:" @click="navigate('/gallery')" class="nav-link">
                Thư Viện Ảnh
              </a>
            </li>
            <li>
              <a href="javascript:" @click="navigate('/doctors')" class="nav-link">
                Bác Sĩ
              </a>
            </li>
            <li>
              <a href="javascript:" @click="navigate('/departments')" class="nav-link">
                Phòng Ban
              </a>
            </li>
            <li>
              <a href="javascript:" @click="navigate('/appointments')" class="nav-link">
                Lịch Hẹn
              </a>
            </li>
            <li>
              <a href="javascript:" @click="navigate('/medicines')" class="nav-link">
                Thuốc
              </a>
            </li>
            <li>
              <a href="javascript:" @click="navigate('/patients')" class="nav-link">
                Bệnh Nhân
              </a>
            </li>
            <li>
              <a href="javascript:" @click="navigate('/prescriptions')" class="nav-link">
                Đơn Thuốc
              </a>
            </li>
          </ul>

          <!-- Search Box -->
          <div class="search-box">
            <input 
              type="text" 
              placeholder="Tìm kiếm..."
              @keyup.enter="handleSearch"
              v-model="searchQuery"
              class="search-input"
            >
            <button class="search-btn" @click="handleSearch" title="Tìm kiếm">
              <img src="/public/images/icons/search.svg" alt="Tìm kiếm" class="search-icon">
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Breadcrumb Navigation -->
    <div class="breadcrumb-wrapper" v-if="breadcrumbs.length">
      <div class="header-container">
        <div class="breadcrumb">
          <a href="javascript:" @click="navigate('/')" class="breadcrumb-item">Trang Chủ</a>
          <span v-for="(item, index) in breadcrumbs" :key="index" class="breadcrumb-separator">›</span>
          <span class="breadcrumb-current">{{ currentPage }}</span>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'AppHeader',
  data() {
    return {
      searchQuery: '',
      breadcrumbs: [],
      currentPage: ''
    }
  },
  methods: {
    navigate(path) {
      this.$router.push(path);
    },
    handleSearch() {
      if (this.searchQuery.trim()) {
        console.log('Tìm kiếm:', this.searchQuery);
        this.$emit('search', this.searchQuery);
      }
    },
    updateBreadcrumb() {
      const path = this.$route.path;
      const pathMap = {
        '/doctors': 'Quản Lý Bác Sĩ',
        '/departments': 'Quản Lý Phòng Ban',
        '/appointments': 'Quản Lý Lịch Hẹn',
        '/medicines': 'Quản Lý Thuốc',
        '/patients': 'Quản Lý Bệnh Nhân',
        '/prescriptions': 'Quản Lý Đơn Thuốc'
      };

      this.currentPage = pathMap[path] || 'Trang';
      this.breadcrumbs = path !== '/' ? [path] : [];
    }
  },
  watch: {
    '$route'() {
      this.updateBreadcrumb();
    }
  },
  mounted() {
    this.updateBreadcrumb();
  }
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.app-header {
  background: white;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.header-top {
  background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);
  border-bottom: 1px solid #e9ecef;
  padding: 1.5rem 0;
}

.header-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 2rem;
}

.logo-section {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.logo-link {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  text-decoration: none;
  transition: transform 0.2s ease;
}

.logo-link:hover {
  transform: scale(1.01);
}

.logo-icon {
  font-size: 2.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
  border-radius: 12px;
  color: white;
  box-shadow: 0 4px 12px rgba(0, 102, 204, 0.3);
}

.logo-text h1 {
  font-size: 1.4rem;
  color: #1a3a52;
  font-weight: 700;
  margin: 0;
  letter-spacing: 0.5px;
}

.logo-text p {
  font-size: 0.85rem;
  color: #666;
  margin: 0.25rem 0 0 0;
  font-weight: 500;
}

/* Navigation Bar */
.nav-bar {
  background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
  padding: 1rem 0;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.nav-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 2rem;
}

.nav-menu {
  list-style: none;
  display: flex;
  gap: 0;
  flex: 1;
  margin: 0;
  padding: 0;
}

.nav-menu li {
  margin: 0;
}

.nav-link {
  display: inline-block;
  padding: 0.8rem 1.2rem;
  color: white;
  text-decoration: none;
  font-weight: 500;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  border-bottom: 3px solid transparent;
  cursor: pointer;
}

.nav-link:hover {
  background: rgba(255, 255, 255, 0.15);
  border-bottom-color: white;
}

/* Search Box */
.search-box {
  display: flex;
  align-items: center;
  background: white;
  border-radius: 25px;
  padding: 0.4rem 0.8rem;
  gap: 0.5rem;
  flex: 0 1 300px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.search-input {
  flex: 1;
  border: none;
  outline: none;
  font-size: 0.9rem;
  background: transparent;
  padding: 0.5rem 0;
  color: #333;
}

.search-input::placeholder {
  color: #999;
}

.search-btn {
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0.4rem 0.6rem;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
}

.search-btn:hover {
  background: rgba(0, 102, 204, 0.1);
  transform: scale(1.05);
}

.search-icon {
  width: 20px;
  height: 20px;
  object-fit: contain;
  filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));
}

/* Breadcrumb Navigation */
.breadcrumb-wrapper {
  background: #f8f9fa;
  border-bottom: 1px solid #e9ecef;
  padding: 0.75rem 0;
}

.breadcrumb {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
}

.breadcrumb-item {
  color: #0066cc;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s ease;
  cursor: pointer;
}

.breadcrumb-item:hover {
  color: #0052a3;
  text-decoration: underline;
}

.breadcrumb-separator {
  color: #999;
  margin: 0 0.3rem;
}

.breadcrumb-current {
  color: #333;
  font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
  .header-container {
    padding: 0 1rem;
  }

  .logo-text h1 {
    font-size: 1.1rem;
  }

  .nav-wrapper {
    flex-direction: column;
    gap: 1rem;
  }

  .nav-menu {
    width: 100%;
    overflow-x: auto;
    flex-wrap: wrap;
  }

  .nav-link {
    padding: 0.6rem 0.8rem;
    font-size: 0.85rem;
  }

  .search-box {
    width: 100%;
    flex: 1;
  }
}
</style>
