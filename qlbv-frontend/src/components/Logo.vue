<template>
  <div class="logo-wrapper">
    <svg class="logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
      <!-- Outer Circle -->
      <circle cx="50" cy="50" r="48" fill="none" stroke="#16a34a" stroke-width="2"/>
      
      <!-- Cross/Plus in Green -->
      <g>
        <!-- Vertical line -->
        <rect x="45" y="20" width="10" height="60" fill="#16a34a" rx="2"/>
        <!-- Horizontal line -->
        <rect x="20" y="45" width="60" height="10" fill="#16a34a" rx="2"/>
      </g>
      
      <!-- Heart shape accent -->
      <g>
        <path d="M50 35 Q55 30 60 35 Q65 30 65 38 L65 45 Q65 50 50 60 Q35 50 35 45 L35 38 Q35 30 40 35 Q45 30 50 35" 
              fill="#059669" opacity="0.8"/>
      </g>
      
      <!-- Inner circle accent -->
      <circle cx="50" cy="50" r="35" fill="none" stroke="#16a34a" stroke-width="1" opacity="0.5"/>
    </svg>
    
    <div class="logo-text">
      <h1 class="hospital-name">QUẢN LÝ BỆNH VIỆN</h1>
      <p class="hospital-tagline">QUẢN LÝ CHUYÊN NGHIỆP</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Logo'
}
</script>

<style scoped>
.logo-wrapper {
  display: flex;
  align-items: center;
  gap: 1rem;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.logo-wrapper:hover {
  transform: scale(1.02);
}

.logo-svg {
  width: 50px;
  height: 50px;
  flex-shrink: 0;
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
}

.logo-text {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.hospital-name {
  font-size: 1rem;
  font-weight: 800;
  color: #16a34a;
  margin: 0;
  letter-spacing: 0.5px;
  line-height: 1.2;
}

.hospital-tagline {
  font-size: 0.7rem;
  color: #059669;
  margin: 0;
  font-weight: 600;
  letter-spacing: 0.3px;
}

@media (max-width: 768px) {
  .logo-wrapper {
    gap: 0.75rem;
  }

  .logo-svg {
    width: 40px;
    height: 40px;
  }

  .hospital-name {
    font-size: 0.85rem;
  }

  .hospital-tagline {
    font-size: 0.6rem;
  }
}
</style>
