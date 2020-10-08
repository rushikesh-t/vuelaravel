<template>
  <div>

    <transition
      enter-active-class="animate__animated animate__fadeInLeft animate__faster"
      leave-active-class="animate__animated animate__fadeOutRight animate__faster"
      mode="out-in"
    >
      <router-view />
    </transition>
  </div>
</template>

<script>
export default {
  beforeCreate() {
    if (this.$store.getters.userlog == "") {
      console.log("userlog");
      this.$router.push("/login");
    }
  }
};
</script>

<style scoped>
li{
  cursor: pointer;
}
</style>