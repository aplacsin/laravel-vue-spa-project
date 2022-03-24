<template>
  <div>
    <div>
      <!--      <FilterPost
                :keyword="keyword"
                :startDateFormatted="startDateFormatted"
                :startDate="startDate"
                :endDate="endDate"
                :endDateFormatted="endDateFormatted"
                :clearFilter="clearFilter"
                :inputDisabled="inputDisabled"></FilterPost>-->
      <v-container class="wrapper-btn">
        <v-row>
          <v-col
              cols="12"
              md="12"
              lg="12"
              sm="12"
              xs="12"
              class="d-flex">
            <v-btn class="filter-btn" depressed v-b-toggle.collapse-2>
              <span class="mdi mdi-filter"></span>
            </v-btn>
            <ImportDialog :onFileChange="this.onFileChange" :processImport="this.processImport"></ImportDialog>
            <v-btn class="export-btn" depressed @click="getExport">
              <span class="mdi mdi-export"></span>
            </v-btn>
          </v-col>
        </v-row>
        <v-row>
          <v-col
              cols="12"
              md="2"
              lg="2"
              sm="2"
              xs="2">
            <ProgressBar
                :visible="progressBar.visible"
                :total="progressBar.total"
                :current="progressBar.current"
                :progress="progressBar.progress">
            </ProgressBar>
          </v-col>
        </v-row>
        <v-row>
          <div>
            <b-collapse id="collapse-2" class="collapse-menu">
              <v-col
                  cols="12"
                  md="12"
                  lg="12"
                  sm="12"
                  xs="12">
                <v-text-field
                    v-model="keyword"
                    prepend-icon="mdi-table-search"
                    label="Search...">
                </v-text-field>
              </v-col>
              <v-col
                  cols="12"
                  md="12"
                  lg="12"
                  sm="12"
                  xs="12">
                <v-menu
                    ref="menu1"
                    v-model="menu1"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="auto">
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="startDateFormatted"
                        label="Start Date"
                        persistent-hint
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on">
                    </v-text-field>
                  </template>
                  <v-date-picker
                      v-model="startDate"
                      :max="endDate"
                      no-title
                      @input="menu1 = false">
                  </v-date-picker>
                </v-menu>
              </v-col>
              <v-col
                  cols="12"
                  md="12"
                  lg="12"
                  sm="12"
                  xs="12">
                <v-menu
                    ref="menu2"
                    v-model="menu2"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="auto">
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="endDateFormatted"
                        label="End Date"
                        persistent-hint
                        prepend-icon="mdi-calendar"
                        readonly
                        :disabled="inputDisabled"
                        v-bind="attrs"
                        v-on="on">
                    </v-text-field>
                  </template>
                  <v-date-picker
                      v-model="endDate"
                      :min='startDate'
                      no-title
                      @input="menu2 = false">
                  </v-date-picker>
                </v-menu>
              </v-col>
              <v-col cols="12">
                <v-btn class="comment-btn" depressed @click="clearFilter">
                  Reset
                </v-btn>
              </v-col>
            </b-collapse>
          </div>
        </v-row>
      </v-container>
    </div>
    <v-simple-table>
      <template v-slot:default>
        <thead slot="head">
        <tr>
          <th>
            <v-checkbox v-model="selectAll"/>
          </th>
          <th data-field="title" class="text-center col-9" @click.prevent="sortBy('title')">
            Title
            <span class="arrow" v-if="sort.direction === 'desc' && sort.field === 'title'"><span
                class="mdi mdi-arrow-up"></span></span>
            <span class="arrow" v-if="sort.direction === 'asc' && sort.field === 'title'"><span
                class="mdi mdi-arrow-down"></span></span>
          </th>
          <th data-field="created_at" class="text-center" @click.prevent="sortBy('created_at')">
            Created At
            <span class="arrow" v-if="sort.direction === 'desc' && sort.field === 'created_at'"><span
                class="mdi mdi-arrow-up"></span></span>
            <span class="arrow" v-if="sort.direction === 'asc' && sort.field === 'created_at'"><span
                class="mdi mdi-arrow-down"></span></span>
          </th>
          <th data-field="action" class="text-center">
            Actions
          </th>
        </tr>
        </thead>
        <tbody v-if="posts.data && posts.data.length > 0">
        <tr v-for="post in posts.data" :key="post.id" :class="isChecked(post.id) ? 'table-primary' : ''">
          <td>
            <v-checkbox :value="post.id" v-model="checked"/>
          </td>
          <td>{{ post.title }}</td>
          <td>{{ post.created_at }}</td>
          <td class="td-post-action">
            <router-link :to="{ name: 'ShowPost', params: { id: post.id }}"
                         class="button-action flex-column post-action-btn"><span
                class="mdi mdi-eye"></span></router-link>
            <router-link :to="{ name: 'EditPost', params: { id: post.id }}"
                         class="button-action flex-column post-action-btn"><span
                class="mdi mdi-pencil"></span></router-link>
            <button class="button-action flex-column post-action-delete-btn"
                    @click="deletePost(post.id)"><span class="mdi mdi-delete"></span></button>
          </td>
        </tr>
        </tbody>
        <tbody class="text-center" v-else>No Posts Found</tbody>
      </template>
    </v-simple-table>
    <div class="text-center">
      <v-pagination v-model="pagination.current" :total-visible="8" :length="pagination.total"
                    @input="onPageChange"></v-pagination>
    </div>
  </div>
</template>

<script>
import {debounce} from 'lodash';
import PostService from '../../../service/PostService';
import ProgressBar from "@/components/ProgressBar";
import ImportDialog from "@/views/components/posts/import/ImportDialog";
/*import FilterPost from "@/views/components/posts/filters/FilterPost";*/

export default {
  components: {
    ImportDialog,
    /*FilterPost,*/
    ProgressBar
  },
  data() {
    this.processBar = debounce(this.processBar, 2000);

    return {
      posts: [],
      errors: [],
      keyword: this.$route.query.search ?? null,
      startDate: this.$route.query.startDate ?? null,
      endDate: this.$route.query.endDate ?? null,
      menu1: false,
      menu2: false,
      sort: {
        direction: 'asc',
        field: null,
      },
      checked: [],
      selectAll: false,
      importFile: null,
      progressBar: {
        progress: 0,
        total: 0,
        current: 0,
        visible: false
      },
      pagination: {
        current: JSON.parse(this.$route.query.page || '1'),
        total: 0,
      },
    }
  },
  created() {
    this.getPosts();
  },
  computed: {
    startDateFormatted() {
      return this.formatDate(this.startDate);
    },
    endDateFormatted() {
      return this.formatDate(this.endDate);
    },
    inputDisabled() {
      return this.startDate === null;
    }
  },
  watch: {
    keyword: debounce(function () {
      this.pagination.current = 1;
      this.getPosts(this.keyword);
    }, 300),
    startDate: debounce(function () {
      this.getPosts(this.startDate);
    }, 300),
    endDate: debounce(function () {
      this.pagination.current = 1;
      this.getPosts(this.endDate);
    }, 300),
    selectAll: function (value) {
      this.checked = [];
      if (value) {
        this.posts.data.forEach(post => {
          this.checked.push(post.id);
        });
      } else {
        this.selectAll = false;
      }
    },
  },
  methods: {
    getPosts() {
      let page = this.pagination.current ?? 1;
      let params = `?page=${page}`;

      if (this.keyword !== null) {
        params = params.concat(`&search=${this.keyword}`);
      }

      if (this.startDate !== null) {
        if (this.endDate !== null) {
          params = params.concat(`&startDate=${this.startDate}&endDate=${this.endDate}`);
        }
      }

      if (this.sort.field && this.sort.direction !== null) {
        params = params.concat(`&sortField=${this.sort.field}&sortDirection=${this.sort.direction}`);
      }

      PostService.list(params).then(response => {
        this.posts = response.data;
        this.pagination.current = response.data.meta.current_page;
        this.pagination.total = response.data.meta.last_page;
        this.$router.push(params);
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors;
          this.$toast.error(this.errors.endDate[0], {
            position: "top-right",
            timeout: 5000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: false,
            closeButton: "button",
            icon: true,
            rtl: false
          });
        }
      })
    },
    getExport() {
      let checkedPost = this.checked;
      let params = `?id=${checkedPost}`;

      PostService.export(params).then(response => {
        const fileUrl = window.URL.createObjectURL(response.data);
        const fileLink = document.createElement('a');

        fileLink.href = fileUrl;
        fileLink.setAttribute('download', 'export.csv');
        document.body.appendChild(fileLink);
        fileLink.click();
        document.body.removeChild(fileLink);
      }).catch(error => {
        this.errors = error.response.data.errors;
        setTimeout(function (scope) {
          scope.errors = '';
        }, 5000, this)
      })
    },
    onFileChange(file) {
      this.importFile = file;
    },
    processImport() {
      let formData = new FormData();
      formData.append('importFile', this.importFile);

      PostService.import(formData).then(response => {
        if (response.status === 200) {
          this.processBar();
        }
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.data.errors;
          this.$toast.error(this.errors.importFile[0], {
            position: "top-right",
            timeout: 5000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: false,
            closeButton: "button",
            icon: true,
            rtl: false
          });
        }
      })
    },
    isChecked(post_id) {
      return this.checked.includes(post_id);
    },
    processBar() {
      PostService.status(1).then(response => {

        if (response.data.data.processed) {
          this.progressBar.current = this.progressBar.total;
          this.progressBar.progress = 100;
          this.progressBar.visible = false;
          return;
        }

        this.progressBar.visible = true;
        this.progressBar.total = response.data.data.total;
        this.progressBar.current = response.data.data.current;
        this.progressBar.progress = Math.ceil(this.progressBar.current / this.progressBar.total * 100);

        if (this.progressBar.total === this.progressBar.current) {
          PostService.complete(true);
        }

        this.processBar();
      })
    },
    onPageChange() {
      this.getPosts();
    },
    deletePost(id) {
      if (confirm('Deleted post?')) {
        PostService.delete(id).then(() => {
          this.message = 'Success deleted!';
          this.$toast.success(this.message, {
            position: "top-right",
            timeout: 5000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: false,
            closeButton: "button",
            icon: true,
            rtl: false
          });
          this.getPosts();
        });
      }
    },
    sortBy(field) {
      if (this.sort.field === field) {
        this.sort.direction = this.sort.direction === "asc" ? "desc" : "asc";
      } else {
        this.sort.field = field;
      }
      this.getPosts();
    },
    formatDate(date) {
      if (!date) return null;

      const [year, month, day] = date.split('-');
      return `${month}/${day}/${year}`;
    },
    clearFilter() {
      this.keyword = null;
      this.endDate = null;
      this.startDate = null;
    }
  }
}
</script>

<style scoped lang="scss">

.td-post-action {
  display: revert;
  justify-content: center;
  align-items: center;
}

.post-action-btn {
  text-decoration: none;
  color: inherit;
  font-size: 20px;
}

.post-action-delete-btn {
  color: red;
  font-size: 20px;
}

.arrow {
  font-size: 20px;
  display: revert;
}

.comment-btn {
  margin-top: 5px;
}

.export-btn {
  height: 36px !important;
  min-width: 0 !important;
  padding: 0 16px !important;
  width: 36px !important;

  .mdi-export {
    font-size: 20px;
    transform: rotate(-90deg);
  }
}

.filter-btn {
  margin-right: 5px !important;
  height: 36px !important;
  min-width: 0 !important;
  padding: 0 16px !important;
  width: 36px !important;

  .mdi-filter {
    font-size: 20px;
  }
}

.mdi-arrow-up {
  font-size: 13px;
}

.mdi-arrow-down {
  font-size: 13px;
}

.wrapper-btn {
  margin-right: 0;
  margin-left: 0;
}

.collapse-menu {
  overflow: hidden;
  transition: width 600ms ease-out, height 600ms ease-out;
}

</style>
