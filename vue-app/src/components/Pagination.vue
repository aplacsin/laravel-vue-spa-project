<template>
  <div class="text-center wrapper-paginate">
    <v-pagination v-model="page" :total-visible="8" :length="total"
                  @input="onPageChange"></v-pagination>
  </div>
</template>

<script>
export default {
  props: {
    onPageChange: {
      type: Function
    },
    page: [] ?? JSON.parse(this.$route.query.page || '1'),
    total: [] ?? 0
  },
  data() {
    return {
      current: this.page,
    }
  },
}
</script>

<style lang="scss">

.wrapper-paginate {
  margin-top: 15px;
}

</style>
