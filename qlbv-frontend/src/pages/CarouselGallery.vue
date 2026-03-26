<template>
  <div class="carousel-showcase">
    <div class="showcase-header">
      <h1>Thư Viện Ảnh Động</h1>
      <p>Khám phá hình ảnh bệnh viện qua bộ sưu tập chuyển động mượt mà</p>
    </div>

    <!-- Main Carousel -->
    <div class="carousel-main">
      <image-carousel 
        :images="carouselImages"
        :autoPlay="true"
        :interval="5000"
      />
    </div>

    <!-- Featured Images Grid -->
    <div class="featured-section">
      <h2>Các Ảnh Nổi Bật</h2>
      <div class="featured-grid">
        <div 
          v-for="(image, index) in carouselImages"
          :key="index"
          class="featured-card"
          :style="{ animationDelay: `${index * 0.1}s` }"
          @click="selectImage(image, index)"
        >
          <img :src="image.src" :alt="image.label" class="featured-thumbnail">
          <div class="featured-card-info">
            <p>{{ image.label }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Image Details Modal -->
    <transition name="modal">
      <div v-if="selectedImage" class="image-modal" @click="selectedImage = null">
        <div class="modal-content" @click.stop>
          <button class="modal-close" @click="selectedImage = null">✕</button>
          <img :src="selectedImage.src" :alt="selectedImage.label" class="modal-image">
          <div class="modal-info">
            <h3>{{ selectedImage.label }}</h3>
            <p>{{ selectedImage.description }}</p>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import ImageCarousel from '../components/ImageCarousel.vue'

export default {
  name: 'CarouselGallery',
  components: {
    ImageCarousel
  },
  data() {
    return {
      selectedImage: null,
      carouselImages: [
        {
          src: '/public/images/bac_si/bac_si.jpg',
          label: 'Đội Ngũ Bác Sĩ Chuyên Nghiệp'
        },
        {
          src: '/public/images/benh_nhan/benh_nhan.jpg',
          label: 'Dịch Vụ Chăm Sóc Bệnh Nhân'
        },
        {
          src: '/public/images/phong_ban/phong_ban.jpg',
          label: 'Cơ Sở Vật Chất Hiện Đại'
        },
        {
          src: '/public/images/thuoc/thuoc.jpg',
          label: 'Kho Thuốc Đầy Đủ'
        },
        {
          src: '/public/images/logo/logo_1.png',
          label: 'Biểu Tượng Bệnh Viện'
        }
      ]
    }
  },
  methods: {
    selectImage(image, index) {
      this.selectedImage = image;
    }
  }
}
</script>

<style scoped>
.carousel-showcase {
  min-height: 100vh;
  padding: 2rem;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.showcase-header {
  text-align: center;
  margin-bottom: 3rem;
  animation: slideDown 0.6s ease-out;
}

.showcase-header h1 {
  font-size: 2.5rem;
  color: #1a3a52;
  margin-bottom: 0.75rem;
  font-weight: 700;
}

.showcase-header p {
  font-size: 1.1rem;
  color: #666;
  font-weight: 500;
}

.carousel-main {
  max-width: 1000px;
  margin: 0 auto 3rem;
  animation: slideUp 0.6s ease-out 0.2s backwards;
}

.featured-section {
  max-width: 1200px;
  margin: 0 auto;
  animation: slideUp 0.6s ease-out 0.4s backwards;
}

.featured-section h2 {
  font-size: 1.8rem;
  color: #1a3a52;
  margin-bottom: 1.5rem;
  text-align: center;
  font-weight: 700;
}

.featured-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
}

.featured-card {
  position: relative;
  cursor: pointer;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  animation: fadeInScale 0.6s ease-out forwards;
  opacity: 0;
}

.featured-card:hover {
  transform: scale(1.05) translateY(-8px);
  box-shadow: 0 12px 28px rgba(0, 102, 204, 0.2);
}

.featured-thumbnail {
  width: 100%;
  height: 200px;
  object-fit: cover;
  object-position: center;
  transition: transform 0.3s ease;
}

.featured-card:hover .featured-thumbnail {
  transform: scale(1.1);
}

.featured-card-info {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
  padding: 1rem;
  color: white;
  transform: translateY(100%);
  transition: transform 0.3s ease;
}

.featured-card:hover .featured-card-info {
  transform: translateY(0);
}

.featured-card-info p {
  margin: 0;
  font-weight: 600;
  font-size: 0.95rem;
}

/* Modal Styles */
.image-modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.85);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1001;
  padding: 1rem;
  backdrop-filter: blur(4px);
}

.modal-content {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  max-width: 700px;
  width: 100%;
  display: flex;
  flex-direction: column;
  max-height: 85vh;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.4s ease-out;
  position: relative;
}

.modal-close {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(255, 255, 255, 0.9);
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  font-size: 1.5rem;
  cursor: pointer;
  z-index: 1;
  transition: all 0.2s ease;
}

.modal-close:hover {
  background: white;
  transform: scale(1.1) rotate(90deg);
}

.modal-image {
  width: 100%;
  height: auto;
  max-height: 60vh;
  object-fit: cover;
  object-position: center;
}

.modal-info {
  padding: 2rem;
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.modal-info h3 {
  font-size: 1.6rem;
  color: #1a3a52;
  margin-bottom: 1rem;
  font-weight: 700;
}

.modal-info p {
  font-size: 0.95rem;
  color: #666;
  line-height: 1.6;
}

/* Animations */
@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInScale {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
  .carousel-showcase {
    padding: 1.5rem;
  }

  .showcase-header h1 {
    font-size: 1.8rem;
  }

  .showcase-header p {
    font-size: 0.95rem;
  }

  .featured-grid {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
  }

  .featured-thumbnail {
    height: 150px;
  }

  .modal-content {
    max-width: 95vw;
    border-radius: 12px;
  }

  .modal-info {
    padding: 1.5rem;
  }

  .modal-info h3 {
    font-size: 1.3rem;
  }
}
</style>
