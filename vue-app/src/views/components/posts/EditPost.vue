<template>
  <div class="wrapper-edit-content">
    <v-dialog
        :retain-focus="false"
        v-model="dialog"
        max-width="700"
    >
      <template v-slot:activator="{ on, attrs }">
        <button
            v-bind="attrs" v-on="on"
            class="button-action flex-column post-action-btn"
            @click="getEditPost(posts)"
            @click.stop="dialog = true"
        >
          <v-icon color="green" class="mdi mdi-pencil"></v-icon>
        </button>
      </template>
      <v-card>
        <v-card-title class="text-h5 justify-center">
          Edit Post
        </v-card-title>
        <div class="wrapper-content">
          <v-col>
            <v-text-field
                v-model="post.title"
                :counter="150"
                label="Title"
                required
            ></v-text-field>
          </v-col>
          <editor
              v-model="post.description"
              api-key="k3hpsqyq7bdu9tzvo6bsl0c8zig9qhpzxwntl6lllolbl1is"
              :init="options"
          ></editor>
        </div>
        <v-card-actions class="btn-wrapper">
          <v-spacer></v-spacer>
          <v-btn
              color="red darken-1"
              text
              @click.stop="dialog = false"
          >Cancel
          </v-btn>
          <v-btn
              text
              @click="updatePost(post.id)"
              @click.stop="dialog = false"
          >Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import PostService from "@/service/PostService";
import Editor from "@tinymce/tinymce-vue";

export default {
  components: {
    editor: Editor,
  },
  props: {
    posts: [],
    getPost: {
      type: Function
    },
    readonly: {
      type: Boolean,
      required: false,
      default: false
    },
  },
  data() {
    return {
      dialog: false,
      editor: false,
      post: [],
      options: {
        height: 500,
        menubar: true,
        plugins: 'autolink charmap code codesample directionality emoticons',
        toolbar1: 'formatselect fontsizeselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
        content_css: [
          '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        ],
      }
    }
  },
  methods: {
    getEditPost(id) {
      PostService.edit(id).then(response => {
        this.post = response.data.data;
      });
    },
    updatePost(id) {
      const data = {
        title: this.post.title,
        description: this.post.description
      };
      PostService.update(id, data).then(response => {
        response.data;
        this.message = 'The post was updated success!';
        this.$toast.success(this.message);
        this.getPost();
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors;
          this.$toast.error(this.errors.title[0] ?? this.errors.description[0]);
        }
      });
    }
  }
}
</script>

<style scoped lang="scss">

.post-action-btn {
  text-decoration: none;
  color: inherit;
  font-size: 20px;
}

.wrapper-content {
  margin-left: 20px;
  margin-right: 20px;
}

.wrapper-edit-content {
  display: initial;
}

</style>
