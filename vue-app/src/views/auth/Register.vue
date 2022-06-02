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
                <v-text-field
                    type="name"
                    label="Name"
                    prepend-icon="mdi-account"
                    class="form-control"
                    id="name"
                    v-model="form.name"
                ></v-text-field>
                <Errors :errors="errors.name"/>
                <v-text-field
                    type="email"
                    label="Email Address"
                    prepend-icon="mdi-email"
                    class="form-control"
                    id="email"
                    v-model="form.email"
                ></v-text-field>
                <Errors :errors="errors.email"/>
                <v-text-field
                    type="password"
                    label="Password"
                    prepend-icon="mdi-lock"
                    class="form-control"
                    id="password"
                    v-model="form.password"
                ></v-text-field>
                <Errors :errors="errors.password"/>
                <v-text-field
                    type="password"
                    label="Confirm Password"
                    prepend-icon="mdi-check"
                    class="form-control"
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                ></v-text-field>
                <Errors :errors="errors.password_confirmation"/>
              </v-form>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                  color="primary"
                  @click.prevent="register"
              >Register
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-main>
</template>

<script>


import Errors from "../../components/ui/errors/Errors";
import AuthUserService from "../../service/UserService";

export default {
  components: {
    Errors
  },
  data() {
    return {
      form: {
        name: null,
        email: null,
        password: null,
        password_confirmation: null
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
            this.message = 'Successful registration!';
            this.$toast.success(this.message);
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
