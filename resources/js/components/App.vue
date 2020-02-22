<template>
  <div class="w-100 h-100">
    <vue-progress-bar></vue-progress-bar>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: "app",
  mounted() {
    this.$Progress.finish();
  },
  created() {
    this.$Progress.start();
    this.$router.beforeEach((to, from, next) => {
      if (to.meta.progress !== undefined) {
        let meta = to.meta.progress;
        this.$Progress.parseMeta(meta);
      }
      this.$Progress.start();
      next();
    });
    this.$router.afterEach((to, from) => {
      this.$Progress.finish();
    });
  }
};
</script>
