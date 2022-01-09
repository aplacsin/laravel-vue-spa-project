<template>
  <v-main>
    <v-container fluid>
      <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4>
          <v-card class="elevation-12">
            <v-toolbar dark color="primary">
              <v-toolbar-title>Register form</v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text>
              <v-form>
                <v-text-field type="name" label="Name" prepend-icon="mdi-account" v-model="form.name"
                              class="form-control" id="name"></v-text-field>
                <v-alert dense outlined type="error" v-if="errors.name">
                  {{ errors.name[0] }}
                </v-alert>
                <v-text-field type="email" label="Email Address" prepend-icon="mdi-email" v-model="form.email"
                              class="form-control" id="email"></v-text-field>
                <v-alert dense outlined type="error" v-if="errors.email">
                  {{ errors.email[0] }}
                </v-alert>
                <v-text-field type="password" label="Password" prepend-icon="mdi-lock" v-model="form.password"
                              class="form-control" id="password"></v-text-field>
                <v-alert dense outlined type="error" v-if="errors.password">
                  {{ errors.password[0] }}
                </v-alert>
                <v-text-field type="password" label="Confirm Password" prepend-icon="mdi-check"
                              v-model="form.password_confirmation" class="form-control"
                              id="password_confirmation"></v-text-field>
                <v-alert dense outlined type="error" v-if="errors.password_confirmation">
                  {{ errors.password_confirmation[0] }}
                </v-alert>
              </v-form>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="primary" @click.prevent="register">Register</v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-main>
</template>

<script>
import AuthUserService from "@/service/UserService";

export default {
  data() {
    return {
      form: {
        name: "",
        email: "",
        password: "",
        password_confirmation: ""
      },
      errors: []
    };
  },
  methods: {
    register() {
      AuthUserService.register(this.form)
          .then(() => {
            this.$router.push({
              name: "Login"
            });
          })
          .catch(error => {
            if (error.response.status === 422) {
              this.errors = error.response.data.errors;
            }
          });
    }
  }
};
</script>