<template>
  <div class="carousel-container">
    <div class="carousel-wrapper">
      <div class="carousel-slides">
        <div 
          v-for="(image, index) in images" 
          :key="index"
          class="carousel-slide"
          :class="{ 'active': currentIndex === index }"
        >
          <img :src="image.src" :alt="image.alt" class="slide-image">
          <div v-if="image.label" class="slide-label">{{ image.label }}</div>
        </div>
      </div>

      <!-- Navigation Dots -->
      <div class="carousel-dots">
        <button 
          v-for="(image, index) in images" 
          :key="'dot' + index"
          class="dot"
          :class="{ 'active': currentIndex === index }"
          @click="goToSlide(index)"
          :aria-label="`Go to slide ${index + 1}`"
        />
      </div>

      <!-- Arrow Controls -->
      <button class="carousel-arrow prev" @click="previousSlide" aria-label="Previous slide">
        &#10094;
      </button>
      <button class="carousel-arrow next" @click="nextSlide" aria-label="Next slide">
        &#10095;
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ImageCarousel',
  props: {
    images: {
      type: Array,
      required: true,
      validator: (arr) => arr.length > 0
    },
    autoPlay: {
      type: Boolean,
      default: true
    },
    interval: {
      type: Number,
      default: 4000
    }
  },
  data() {
    return {
      currentIndex: 0,
      autoPlayTimer: null
    }
  },
  methods: {
    nextSlide() {
      this.currentIndex = (this.currentIndex + 1) % this.images.length;
      this.resetAutoPlay();
    },
    previousSlide() {
      this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
      this.resetAutoPlay();
    },
    goToSlide(index) {
      this.currentIndex = index;
      this.resetAutoPlay();
    },
    startAutoPlay() {
      if (this.autoPlay) {
        this.autoPlayTimer = setInterval(() => {
          this.nextSlide();
        }, this.interval);
      }
    },
    stopAutoPlay() {
      if (this.autoPlayTimer) {
        clearInterval(this.autoPlayTimer);
        this.autoPlayTimer = null;
      }
    },
    resetAutoPlay() {
      this.stopAutoPlay();
      this.startAutoPlay();
    }
  },
  mounted() {
    this.startAutoPlay();
  },
  beforeUnmount() {
    this.stopAutoPlay();
  }
}
</script>

<style scoped>
.carousel-container {
  width: 100%;
  max-width: 100%;
  margin: 0 auto;
}

.carousel-wrapper {
  position: relative;
  overflow: hidden;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.carousel-slides {
  position: relative;
  width: 100%;
  padding-bottom: 66.67%; /* 3:2 aspect ratio */
}

.carousel-slide {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  transform: translateX(100%);
  transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

.carousel-slide.active {
  opacity: 1;
  transform: translateX(0);
  z-index: 10;
}

.slide-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

.slide-label {
  position: absolute;
  bottom: 20px;
  left: 20px;
  right: 20px;
  background: rgba(0, 0, 0, 0.6);
  color: white;
  padding: 12px 16px;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  text-align: center;
  backdrop-filter: blur(4px);
  animation: slideUp 0.6s ease-out;
}

.carousel-dots {
  position: absolute;
  bottom: 12px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 8px;
  z-index: 20;
  background: rgba(0, 0, 0, 0.3);
  padding: 8px 12px;
  border-radius: 20px;
  backdrop-filter: blur(4px);
}

.dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  border: 2px solid white;
  background: rgba(255, 255, 255, 0.4);
  cursor: pointer;
  transition: all 0.3s ease;
  padding: 0;
}

.dot.active {
  background: white;
  transform: scale(1.3);
}

.dot:hover {
  background: rgba(255, 255, 255, 0.8);
}

.carousel-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0, 0, 0, 0.5);
  border: none;
  color: white;
  font-size: 24px;
  padding: 12px 16px;
  cursor: pointer;
  border-radius: 4px;
  transition: all 0.3s ease;
  z-index: 20;
  backdrop-filter: blur(4px);
}

.carousel-arrow:hover {
  background: rgba(0, 0, 0, 0.8);
  transform: translateY(-50%) scale(1.1);
}

.carousel-arrow.prev {
  left: 12px;
}

.carousel-arrow.next {
  right: 12px;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .carousel-arrow {
    padding: 8px 12px;
    font-size: 18px;
  }

  .carousel-dots {
    bottom: 8px;
    padding: 6px 10px;
  }

  .dot {
    width: 8px;
    height: 8px;
  }
}
</style>
