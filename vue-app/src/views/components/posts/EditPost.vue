<template>
  <div class="wrapper-edit-content">
    <v-dialog
        :retain-focus="false"
        v-model="dialog"
        max-width="900"
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
                outlined
                required
            ></v-text-field>
          </v-col>
          <tiptap-vuetify
              v-model="post.description"
              :extensions="extensions"
              :toolbar-attributes="{ color: '' }"
          />
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
import {
  TiptapVuetify,
  Heading,
  Bold,
  Italic,
  Strike,
  Underline,
  Code,
  Paragraph,
  BulletList,
  OrderedList,
  ListItem,
  Link,
  Blockquote,
  HardBreak,
  HorizontalRule,
  History,
  Image,
  Table,
  TableCell,
  TableHeader,
  TableRow
} from 'tiptap-vuetify'

export default {
  components: {
    TiptapVuetify
  },
  props: {
    posts: [],
    getPost: {
      type: Function
    }
  },
  data() {
    return {
      dialog: false,
      post: [],
      extensions: [
        History,
        Table,
        TableCell,
        TableHeader,
        TableRow,
        Blockquote,
        Link,
        Underline,
        Strike,
        Italic,
        ListItem,
        BulletList,
        OrderedList,
        Image,
        [
          Heading,
          {
            options: {
              levels: [1, 2, 3]
            }
          }
        ],
        Bold,
        Link,
        Code,
        HorizontalRule,
        Paragraph,
        HardBreak
      ],
    }
  },
  methods: {
    getEditPost(post) {
      this.post = Object.assign({}, post);
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
  outline: none;
}

.wrapper-content {
  margin-left: 20px;
  margin-right: 20px;
}

.wrapper-edit-content {
  display: initial;
}

</style>
