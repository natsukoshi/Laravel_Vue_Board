<template>
  <transition name="modal" appear>
    <div class="modal modal-overlay">
      <div class="modal-window">
        <div class="modal-content">
          <slot name="message" />
        </div>
      </div>
    </div>
  </transition>
</template>


