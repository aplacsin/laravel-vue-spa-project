<template>
  <div id="app">
    <PageLoader/>
    <Navigation/>
  </div>
</template>

<script>
import Navigation from "@/components/Navigation.vue";
import PageLoader from "@/components/ui/pageloader/Pageloader";

export default {
  components: {
    PageLoader,
    Navigation
  },
};
</script>

<style lang="scss">

</style>
