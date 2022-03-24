<template>
  <v-container>
    <v-row>
      <div>
        <v-col
            cols="12"
            md="12"
            lg="12"
            sm="12"
            xs="12">
          <v-btn class="filter-btn" depressed v-b-toggle.collapse-2><span class="mdi mdi-filter"></span>Filter
          </v-btn>
        </v-col>
        <b-collapse id="collapse-2">
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
</template>

<script>

import {debounce} from "lodash";

export default {
  props: {
    method: { type: Function },
    current: []
  },
  data() {
    return {
      menu1: false,
      menu2: false,
      startDate: this.$route.query.startDate ?? null,
      endDate: this.$route.query.endDate ?? null,
      keyword: this.$route.query.search ?? null,
    }
  },
  watch: {
    keyword: debounce(function () {
      this.current = 1;
      this.method(this.keyword);
    }, 300),
    startDate: debounce(function () {
      this.method(this.startDate);
    }, 300),
    endDate: debounce(function () {
      this.current = 1;
      this.method(this.endDate);
    }, 300),
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
  methods: {
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
