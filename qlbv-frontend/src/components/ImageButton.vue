<template>
  <a 
    :href="href" 
    :target="target"
    class="image-button"
    :class="[variant, { 'rounded': rounded }]"
  >
    <div class="button-content">
      <div class="button-icon">
        <img 
          :src="imageSrc" 
          :alt="label"
          class="button-image"
        >
      </div>
      <span class="button-label">{{ label }}</span>
    </div>
    <div class="button-shine"></div>
  </a>
</template>

<script>
export default {
  name: 'ImageButton',
  props: {
    href: {
      type: String,
      required: true
    },
    imageSrc: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    variant: {
      type: String,
      default: 'primary',
      validator: (val) => ['primary', 'success', 'warning', 'danger'].includes(val)
    },
    target: {
      type: String,
      default: '_self'
    },
    rounded: {
      type: Boolean,
      default: true
    }
  }
}
</script>

<style scoped>
.image-button {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  padding: 12px 20px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  color: white;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  cursor: pointer;
  border: none;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  animation: slideInUp 0.6s ease-out;
}

.image-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.2);
  transition: left 0.3s ease;
}

.image-button:hover::before {
  left: 100%;
}

.image-button:hover {
  transform: translateY(-4px) scale(1.02);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
}

.image-button:active {
  transform: translateY(-2px) scale(0.98);
}

/* Variants */
.image-button.primary {
  background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
}

.image-button.success {
  background: linear-gradient(135deg, #28a745 0%, #218838 100%);
}

.image-button.warning {
  background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
  color: #333;
}

.image-button.danger {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.image-button.rounded {
  border-radius: 50px;
}

.button-content {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  position: relative;
  z-index: 1;
}

.button-icon {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.15);
  border-radius: 6px;
  transition: all 0.3s ease;
}

.image-button:hover .button-icon {
  background: rgba(255, 255, 255, 0.25);
  transform: scale(1.1) rotate(5deg);
}

.button-image {
  width: 24px;
  height: 24px;
  object-fit: contain;
  filter: brightness(0) invert(1);
}

.button-label {
  transition: all 0.3s ease;
  font-size: 0.95rem;
}

.image-button:hover .button-label {
  letter-spacing: 0.5px;
}

.button-shine {
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: linear-gradient(
    45deg,
    transparent 20%,
    rgba(255, 255, 255, 0.1) 50%,
    transparent 80%
  );
  transform: rotate(45deg);
  animation: shine 3s infinite;
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes shine {
  0% {
    transform: translateX(-100%) translateY(-100%) rotate(45deg);
  }
  100% {
    transform: translateX(100%) translateY(100%) rotate(45deg);
  }
}

@media (max-width: 768px) {
  .image-button {
    padding: 10px 16px;
    font-size: 0.9rem;
  }

  .button-icon {
    width: 28px;
    height: 28px;
  }

  .button-image {
    width: 20px;
    height: 20px;
  }
}
</style>
