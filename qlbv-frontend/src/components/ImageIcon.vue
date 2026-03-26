<template>
  <div class="image-icon-wrapper">
    <div class="image-icon" :class="{ 'animated': animated }">
      <img 
        :src="imageSrc" 
        :alt="label"
        :style="{ width: size, height: size }"
        class="icon-image"
      >
      <div v-if="label" class="icon-label">{{ label }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ImageIcon',
  props: {
    imageSrc: {
      type: String,
      required: true
    },
    label: {
      type: String,
      default: ''
    },
    size: {
      type: String,
      default: '64px'
    },
    animated: {
      type: Boolean,
      default: true
    }
  }
}
</script>

<style scoped>
.image-icon-wrapper {
  display: inline-block;
}

.image-icon {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
  
  &.animated {
    animation: slideIn 0.6s ease-out;
  }
  
  &:hover {
    transform: scale(1.1) translateY(-5px);
    filter: drop-shadow(0 8px 16px rgba(0, 102, 204, 0.3));
  }
}

.icon-image {
  object-fit: contain;
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
  transition: all 0.3s ease;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.05);
  padding: 8px;
}

.image-icon:hover .icon-image {
  filter: drop-shadow(0 6px 12px rgba(0, 102, 204, 0.4)) brightness(1.1);
}

.icon-label {
  font-size: 0.85rem;
  font-weight: 600;
  color: #333;
  text-align: center;
  white-space: nowrap;
  animation: fadeIn 0.8s ease-out;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(20px);
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
</style>
