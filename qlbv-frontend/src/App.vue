<template>
  <div class="app-container">
    <!-- HEADER -->
    <app-header @navigate="go" @search="handleSearch" />

    <!-- PAGE CONTENT -->
    <main class="main-content">
      <router-view />
    </main>

    <!-- FOOTER -->
    <app-footer @navigate="go" />

    <!-- Quick contact buttons bottom-right with animated images -->
    <div class="quick-contact-wrapper">
      <image-button 
        href="tel:+84901234567"
        imageSrc="/public/images/icons/phone.svg"
        label="Gọi"
        variant="primary"
        title="Gọi ngay"
      />
      <image-button 
        href="sms:+84901234567"
        imageSrc="/public/images/icons/message.svg"
        label="SMS"
        variant="success"
        title="Nhắn tin"
      />
    </div>
  </div>
</template>

<script>
import AppHeader from './components/AppHeader.vue'
import AppFooter from './components/AppFooter.vue'
import ImageButton from './components/ImageButton.vue'

export default {
  name: "App",
  components: {
    AppHeader,
    AppFooter,
    ImageButton
  },
  methods: {
    go(path) {
      this.$router.push(path);
    },
    handleSearch(query) {
      console.log('Tìm kiếm: ', query);
      // Xử lý tìm kiếm ở đây
    }
  },
};
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body {
  height: 100%;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  background: #f5f7fa;
}

#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.app-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  max-width: 1400px;
  margin: 0 auto;
  width: 100%;
  padding: 2rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .main-content {
    padding: 1.5rem;
  }
}

@media (max-width: 768px) {
  .main-content {
    padding: 1rem;
  }
}

@media (max-width: 480px) {
  .main-content {
    padding: 0.75rem;
  }
}

.quick-contact-wrapper {
  position: fixed;
  right: 1.5rem;
  bottom: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  z-index: 1000;
  animation: slideInRight 0.6s ease-out;
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(100px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Responsive for mobile */
@media (max-width: 480px) {
  .quick-contact-wrapper {
    right: 0.75rem;
    bottom: 0.75rem;
    gap: 0.5rem;
  }
}
</style>

