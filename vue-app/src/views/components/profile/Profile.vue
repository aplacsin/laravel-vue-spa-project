<template>
  <v-card class="mx-auto" max-width="1000" tile>
    <div style="height: 200px">
      <v-img height="100%" src="https://cdn.vuetifyjs.com/images/cards/server-room.jpg"></v-img>
    </div>
    <v-row
        align="end"
        class="fill-height"
    >
      <v-col align-self="start"
             class="pa-0"
             cols="8"
      >
        <v-avatar size="100" class="profile" style="margin-left: 30px;">
          <v-img src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg"></v-img>
        </v-avatar>
      </v-col>
      <v-list-item color="rgba(0, 0, 0, .4)" v-if="user">
        <v-list-item-content style="margin-left: 10px;">
          <v-list-item-title class="title">{{ user.name }}</v-list-item-title>
          <v-list-item-subtitle>Network Engineer</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-row>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      user: null,
    }
  },
  mounted() {
    this.user = JSON.parse(localStorage.getItem('userData'));
  }
}

</script>
