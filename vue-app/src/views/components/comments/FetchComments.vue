<template>
  <div class="reply-comment" v-if=" comments && comments.length > 0">
    <div class="user-comment" v-for="comment in comments" :key="comment.id">
      <v-divider></v-divider>
      <div class="user-name-comment">
        <div><b>{{ comment.user.name }}</b> - {{ new Date(comment.created_at).toLocaleString('ru-ru') }}</div>
      </div>
      <div class="wrapper-content">
        <div> {{ comment.content }} </div>
      </div>
      <!--      <div class="reply">
        <button class="reply-button">
          Reply
        </button>
      </div>-->
    </div>
  </div>
  <div v-else>
    <div class="text-center">
      No comments found.
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      comments: []
    },
  }
</script>

<style scoped lang="scss">

  .user-name-comment {
    margin-left: 15px
  }

  .wrapper-content {
    margin-left: 15px
  }

</style>