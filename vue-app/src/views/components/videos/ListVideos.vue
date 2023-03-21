<template>
  <div>
    <div class="row row-cols-1 row-cols-md-3 g-4 wrapper-video" v-if="videos.data && videos.data.length > 0">

        <v-card class="d-flex flex-column card-video" max-width="250" v-for="video in videos.data" :key="video.id"
        >
          <iframe
              :src="`https://player.vimeo.com/video/${video.video_id}?h=791afcfe42&amp;title=0&amp;byline=0&amp;portrait=0&amp;speed=0&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=234456`"
              max-width="100%" frameborder="0" allow="autoplay; fullscreen; picture-in-picture"
              :title="`${video.title}`"></iframe>
          <v-card-title>
            {{ video.title }}
          </v-card-title>
          <v-spacer></v-spacer>
          <v-card-actions>
            <router-link :to="{ name: 'ShowVideo', params: { id: video.id }}"
                         class="button-action flex-column video-show-btn">
              <v-btn color="orange lighten-2" text>
                Show Video
              </v-btn>
            </router-link>
          </v-card-actions>
        </v-card>

    </div>
    <div class="text-center found-text-video" v-else>No Videos Found</div>
    <div class="text-center wrapper-paginate">
      <v-pagination
          v-model="pagination.current"
          :length="pagination.total"
          :total-visible="8"
          @input="onPageChange"
          prev-icon="mdi-menu-left"
          next-icon="mdi-menu-right"
      ></v-pagination>
    </div>
  </div>
</template>

<script>
import VideoService from '@/service/VideoService';

export default ({
  data() {
    return {
      videos: [],
      loading: true,
      firstLoad: true,
      pagination: {
        current: JSON.parse(this.$route.query.page || '1'),
        total: 0,
      },
    }
  },
  created() {
    this.getVideos();
  },
  methods: {
    getVideos() {
      const page = this.pagination.current ?? 1;
      let params = `?page=${page}`;

      VideoService.video(params).then(response => {
        this.videos = response.data;
        this.pagination.current = response.data.meta.current_page;
        this.pagination.total = response.data.meta.last_page;
        this.$router.push(params);
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors;
          this.$toast.error(this.errors);
        }
      });

      setTimeout(() => {
        if (this.firstLoad)
          this.firstLoad = false
        this.loading = false;
      }, 1000);
    },
    onPageChange() {
      this.getVideos();
    },
  }
})
</script>

<style scoped lang="scss">

.card-video {
  margin-bottom: 10px;
  margin-right: 10px;
}

.video-show-btn {
  text-decoration: none;
  color: inherit;
  font-size: 20px;
}

.wrapper-video {
  display: flex;
  justify-content: center;
  justify-items: center;
  margin-top: 10px
}

.wrapper-paginate {
  margin-top: 15px;
}

.found-text-video {
  font-size: 20px;
}

</style>
