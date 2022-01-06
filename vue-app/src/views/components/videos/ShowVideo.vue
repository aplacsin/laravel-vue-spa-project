<template>
  <div>
    <router-link :to="{ name: 'ListVideos' }">
      <v-btn depressed color="primary" class="link-btn back-btn">
        Back
      </v-btn>
    </router-link>
    <v-card v-if="video">
      <v-responsive>
        <v-card-text>
          <h2><b>{{ video.title }}</b></h2><br><br>
          <div style="display: flex; justify-content: center;">
            <iframe
              :src="`https://player.vimeo.com/video/${video.video_id}?h=791afcfe42&amp;title=0&amp;byline=0&amp;portrait=0&amp;speed=0&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=234456`"
              max-width="100%" width="720" height="480" frameborder="0" allow="autoplay; fullscreen; picture-in-picture"
              :title="`${video.title}`"></iframe>
          </div>
        </v-card-text>
      </v-responsive>
      <v-divider></v-divider>
      <StoreComments :id="video.id" :type="video.type" />
      <FetchComments :comments="video.comments" />
    </v-card>
  </div>
</template>

<script>
  import Video from "@/service/Video";
  import FetchComments from '../comments/FetchComments.vue';
  import StoreComments from '../comments/StoreComments.vue'

  export default {
    components: {
      FetchComments,
      StoreComments
    },
    data() {
      return {
        video: [],
        comment: [],
        message: "",
        errors: []
      }
    },
    mounted() {
      this.getVideo(this.$route.params.id)
    },
    methods: {
      getVideo(id) {
        Video.show(id).then(response => {
          this.video = response.data
        });
      },
    }
  }
</script>

<style scoped lang="scss">

  .link-btn {
    text-decoration: none;
    color: inherit;
  }

  .back-btn {
    margin-bottom: 10px;
  }
</style>