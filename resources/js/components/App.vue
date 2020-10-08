<template>
  <div class="contanier">
    <app-header></app-header>
    <transition
      enter-active-class="animate__animated animate__fadeIn"
      leave-active-class="animate__animated animate__fadeOut"
      mode="out-in"
    >
      <router-view />
    </transition>
  </div>
</template>

<script>
import Header from "./header.vue";
import axios from "axios";


export default {
  data() {
    return {
      page: "add-user"
    };
  },
  components: {
    AppHeader: Header
  }
};
</script>

<style scoped>
.animate__animated.animate__fadeIn {
  --animate-duration: 0.1s;
}
.animate__animated.animate__fadeOut {
  --animate-duration: 0.1s;
}
</style>