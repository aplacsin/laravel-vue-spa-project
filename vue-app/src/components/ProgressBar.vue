<template>
  <div v-show="visible">
    <v-progress-linear :value="progress"></v-progress-linear>
    <div class="text-right mt-1">{{ this.current }} / {{ this.total }}</div>
  </div>
</template>

<script>

export default {
  props: {
    visible: [],
    current: [],
    total: [],
    progress: [],
  }
}

</script>
