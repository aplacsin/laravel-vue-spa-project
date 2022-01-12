<template>
<div>
  <v-alert dense outlined type="error" v-if="errors">
    {{ errors[0] }}
  </v-alert>
</div>
</template>

<script>
export default {
  props: {
    errors: []
  }
}
</script>