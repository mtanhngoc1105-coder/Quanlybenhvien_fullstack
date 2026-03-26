<template>
  <div class="image-gallery-container">
    <div class="gallery-header">
      <h2>Thư Viện Hình Ảnh</h2>
      <p>Các hình ảnh liên quan đến bệnh viện</p>
    </div>

    <div class="gallery-grid">
      <div 
        v-for="(image, index) in galleryImages" 
        :key="index"
        class="gallery-item"
        :style="{ animationDelay: `${index * 0.1}s` }"
        @mouseenter="selectedIndex = index"
        @mouseleave="selectedIndex = null"
      >
        <div class="gallery-image-wrapper">
          <img 
            :src="image.src" 
            :alt="image.label"
            class="gallery-image"
          >
          <div class="gallery-overlay">
            <h3>{{ image.label }}</h3>
            <p>{{ image.description }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Featured Image View -->
    <div v-if="selectedIndex !== null" class="featured-image">
      <button class="close-btn" @click="selectedIndex = null">✕</button>
      <div class="featured-content">
        <img 
          :src="galleryImages[selectedIndex].src" 
          :alt="galleryImages[selectedIndex].label"
          class="featured-img"
        >
        <div class="featured-info">
          <h2>{{ galleryImages[selectedIndex].label }}</h2>
          <p>{{ galleryImages[selectedIndex].description }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ImageGallery',
  data() {
    return {
      selectedIndex: null,
      galleryImages: [
        {
          src: '/public/images/bac_si/bac_si.jpg',
          label: 'Đội Ngũ Bác Sĩ',
          description: 'Những bác sĩ giàu kinh nghiệm, tận tâm với chuyên môn'
        },
        {
          src: '/public/images/benh_nhan/benh_nhan.jpg',
          label: 'Chăm Sóc Bệnh Nhân',
          description: 'Dịch vụ chăm sóc bệnh nhân chuyên nghiệp và tận tình'
        },
        {
          src: '/public/images/phong_ban/phong_ban.jpg',
          label: 'Các Phòng Ban',
          description: 'Cơ sở vật chất hiện đại, trang thiết bị y tế tiên tiến'
        },
        {
          src: '/public/images/thuoc/thuoc.jpg',
          label: 'Kho Thuốc',
          description: 'Kho thuốc đầy đủ, đa dạng từ các hãng uy tín'
        },
        {
          src: '/public/images/logo/logo_1.png',
          label: 'Biểu Tượng Bệnh Viện',
          description: 'Logo và biểu tượng chính của hệ thống'
        }
      ]
    }
  }
}
</script>

<style scoped>
.image-gallery-container {
  padding: 2rem;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  border-radius: 12px;
  margin: 2rem 0;
}

.gallery-header {
  text-align: center;
  margin-bottom: 2.5rem;
  animation: slideDown 0.6s ease-out;
}

.gallery-header h2 {
  font-size: 2rem;
  color: #1a3a52;
  margin-bottom: 0.5rem;
  font-weight: 700;
}

.gallery-header p {
  font-size: 1rem;
  color: #666;
  font-weight: 500;
}

.gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.gallery-item {
  animation: fadeInScale 0.6s ease-out forwards;
  opacity: 0;
  cursor: pointer;
}

.gallery-image-wrapper {
  position: relative;
  overflow: hidden;
  border-radius: 12px;
  aspect-ratio: 1;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  background: white;
}

.gallery-item:hover .gallery-image-wrapper {
  transform: scale(1.05) translateY(-8px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
}

.gallery-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.gallery-item:hover .gallery-image {
  transform: scale(1.1);
}

.gallery-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent);
  padding: 1.5rem;
  color: white;
  transform: translateY(100%);
  transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
  transform: translateY(0);
}

.gallery-overlay h3 {
  font-size: 1.1rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.gallery-overlay p {
  font-size: 0.85rem;
  opacity: 0.9;
  line-height: 1.4;
}

/* Featured Image Modal */
.featured-image {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.85);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
  animation: fadeIn 0.3s ease;
  backdrop-filter: blur(4px);
}

.featured-content {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  max-width: 800px;
  width: 100%;
  display: flex;
  flex-direction: column;
  max-height: 90vh;
  animation: slideUp 0.4s ease-out;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.featured-img {
  width: 100%;
  height: auto;
  max-height: 60vh;
  object-fit: cover;
  object-position: center;
}

.featured-info {
  padding: 2rem;
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.featured-info h2 {
  font-size: 1.8rem;
  color: #1a3a52;
  margin-bottom: 1rem;
  font-weight: 700;
}

.featured-info p {
  font-size: 1rem;
  color: #666;
  line-height: 1.6;
}

.close-btn {
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
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  z-index: 1001;
}

.close-btn:hover {
  background: white;
  transform: scale(1.1) rotate(90deg);
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

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@media (max-width: 768px) {
  .image-gallery-container {
    padding: 1.5rem;
  }

  .gallery-header h2 {
    font-size: 1.5rem;
  }

  .gallery-grid {
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
  }

  .featured-content {
    max-width: 95vw;
  }

  .featured-info {
    padding: 1.5rem;
  }

  .featured-info h2 {
    font-size: 1.3rem;
  }
}
</style>
