<template>
  <div class="home col-10 mx-auto py-5 mt-5">
    <h2>General Statistics</h2><br>
<!--    <v-skeleton-loader v-if="firstLoad" :loading="loading" type="card"></v-skeleton-loader>-->
    <div class="d-flex justify-center">
      <v-card
          class="dashboard-card"
          max-width="344"
          outlined
      >
        <v-list-item three-line>
          <v-list-item-avatar
              tile
              size="80"
              color="#00cae3"
              class="dashboard-avatar"
          ><span class="mdi mdi-post dashboard-icon"></span></v-list-item-avatar>
          <v-list-item-content>
            <div class="text-overline mb-4">
              TOTAL POSTS
            </div>
            <v-list-item-title class="text-h5 mb-1" v-if="stat">
              {{ stat.totalPost }}
            </v-list-item-title>
            <v-list-item-subtitle></v-list-item-subtitle>
          </v-list-item-content>

        </v-list-item>
      </v-card>

      <v-card
          class="dashboard-card"
          max-width="344"
          outlined
      >
        <v-list-item three-line>
          <v-list-item-avatar
              tile
              size="80"
              color="#00cae3"
              class="dashboard-avatar"
          ><span class="mdi mdi-note-plus dashboard-icon"></span></v-list-item-avatar>
          <v-list-item-content>
            <div class="text-overline mb-4">
              WEEK POSTS
            </div>
            <v-list-item-title class="text-h5 mb-1">
              +{{ stat.weekPost }}
            </v-list-item-title>
            <v-list-item-subtitle></v-list-item-subtitle>
          </v-list-item-content>

        </v-list-item>
      </v-card>

      <v-card
          class="dashboard-card"
          max-width="344"
          outlined
      >
        <v-list-item three-line>
          <v-list-item-avatar
              tile
              size="80"
              color="#ff9800"
              class="dashboard-avatar"
          ><span class="mdi mdi-video dashboard-icon"></span></v-list-item-avatar>
          <v-list-item-content>
            <div class="text-overline mb-4">
              TOTAL VIDEOS
            </div>
            <v-list-item-title class="text-h5 mb-1">
              {{ stat.totalVideo }}
            </v-list-item-title>
            <v-list-item-subtitle></v-list-item-subtitle>
          </v-list-item-content>

        </v-list-item>
      </v-card>

      <v-card
          class="dashboard-card"
          max-width="344"
          outlined
      >
        <v-list-item three-line>
          <v-list-item-avatar
              tile
              size="80"
              color="#00cae3"
              class="dashboard-avatar"
          ><span class="mdi mdi-video-switch dashboard-icon"></span></v-list-item-avatar>
          <v-list-item-content>
            <div class="text-overline mb-4">
              WEEK VIDEOS
            </div>
            <v-list-item-title class="text-h5 mb-1">
              +{{ stat.weekVideo }}
            </v-list-item-title>
            <v-list-item-subtitle></v-list-item-subtitle>
          </v-list-item-content>

        </v-list-item>
      </v-card>

    </div>
  </div>
</template>

<script>
import DashboardService from "@/service/DashboardService";

export default {
  data() {
    return {
      user: null,
      loading: true,
      firstLoad: true,
      stat: [],
    };
  },
  created() {
    this.getStat();
  },
  methods: {
    getStat() {
      DashboardService.list().then(response => {
        this.stat = response.data.data;
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors;
          this.$toast.error(this.errors);
        }
      });
    },
  }
};
</script>

<style lang="scss">

.dashboard-icon {
  font-size: 30px;
}

.dashboard-avatar {
  border-radius: 10px;
}

.dashboard-card {
  margin-right: 10px;
}

</style>
