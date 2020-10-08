<template>
  <div>
    <nav class="navbar bg-light sticky-top">
      <ul class="nav nav-pills">
        <router-link
          to="/category"
          tag="li"
          class="nav-item nav-link nav-pointer"
          active-class="active"
          exact
        >
          <div>Category</div>
        </router-link>
        <router-link
          to="/category/add"
          tag="li"
          class="nav-item nav-link nav-pointer"
          active-class="active"
          exact
        >
          <div>Add Category</div>
        </router-link>
      </ul>
    </nav>

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
export default {};
</script>

<style scoped>
li {
  cursor: pointer;
}
</style>