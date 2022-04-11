<template>
  <v-app>
    <v-app-bar app color="primary" dark elevation="0" class="wrapper-app">
      <v-app-bar-nav-icon @click.stop="sidebarMenu = !sidebarMenu"></v-app-bar-nav-icon>
      <v-spacer></v-spacer>
      <v-btn @click="toggleTheme" color="primary" class="mr-2">{{ buttonText }}</v-btn>
      <v-menu
          bottom
          min-width="200px"
          rounded
          offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn
              icon
              x-large
              v-on="on"
          >
            <v-avatar size="35">
              <v-icon v-bind="attrs" v-on="on">mdi-account</v-icon>
            </v-avatar>
          </v-btn>
        </template>
        <v-card v-if="isLoggedIn">
          <v-list-item-content class="justify-center" v-if="user">
            <div class="mx-auto text-center">
              <v-avatar color="blue">
                <span class="white--text text-h5">A</span>
              </v-avatar>
              <h3>{{ user.name }}</h3>
              <p class="text-caption mt-1">
                {{ user.email }}
              </p>
              <v-divider class="my-3"></v-divider>
              <v-btn
                  depressed
                  rounded
                  text
                  to="/profile"
              >
                My profile
              </v-btn>
              <v-divider class="my-3"></v-divider>
              <v-btn
                  depressed
                  rounded
                  text
                  @click.prevent="logout"
              >
                Logout
              </v-btn>
            </div>
          </v-list-item-content>
        </v-card>
        <v-list v-if="!isLoggedIn">
          <v-list-item link>
            <v-list-item-title>
              <router-link class="navbar-link" tag="button" to="/login">
                Login
              </router-link>
            </v-list-item-title>
          </v-list-item>
          <v-list-item link>
            <v-list-item-title>
              <router-link class="navbar-link" tag="button" to="/register">
                Register
              </router-link>
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>
    <v-navigation-drawer
        app
        floating
        v-model="sidebarMenu"
        :permanent="sidebarMenu"
        :mini-variant.sync="mini"
        v-if="isLoggedIn"
    >
      <v-list dense color="primary" dark>
        <v-list-item>
          <v-list-item-action>
            <v-icon @click.stop="sidebarMenu = !sidebarMenu">mdi-chevron-left</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>
              <h3 class="nav-logo">Admin Panel</h3>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-list-item class="px-2" @click="toggleMini = !toggleMini">
        <v-list-item-avatar>
          <v-icon>mdi-account-outline</v-icon>
        </v-list-item-avatar>
        <v-list-item-content class="text-truncate" v-if="user">
          {{ user.name }}
        </v-list-item-content>
        <v-btn icon small>
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
      </v-list-item>
      <v-divider></v-divider>
      <v-list>
        <v-list-item v-for="item in items" :key="item.title" link :to="item.href">
          <v-list-item-icon>
            <v-icon class="text-truncate">{{ item.icon }}</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title class="text-truncate">{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-main>
      <v-container class="px-4 py-0 fill-height" fluid>
        <v-row class="fill-height">
          <v-col>
            <transition name="fade">
              <router-view></router-view>
            </transition>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
    <footer id="footer" class="footer"></footer>
  </v-app>
</template>

<script>
import User from '@/service/UserService';

export default {
  data() {
    return {
      user: null,
      isLoggedIn: false,
      sidebarMenu: true,
      toggleMini: false,
      items: [{
        title: 'Home',
        href: '/',
        icon: 'mdi-home-outline'
      },
        {
          title: 'Dashboard',
          href: '/dashboard',
          icon: 'mdi-shield-account'
        },
        {
          title: 'Posts',
          href: '/posts',
          icon: 'mdi-note'
        },
        {
          title: 'Videos',
          href: '/videos',
          icon: 'mdi-video'
        },
        {
          title: 'Settings',
          href: '/settings',
          icon: 'mdi-settings'
        },
      ],
    };
  },
  computed: {
    mini: {
      get() {
        return (this.$vuetify.breakpoint.smAndDown) || this.toggleMini;
      },
      set(value) {
        this.toggleMini = value;
      },
    },
    buttonText() {
      return !this.$vuetify.theme.dark ? 'Dark' : 'Light';
    }
  },
  mounted() {
    this.$root.$on('login', () => {
      this.isLoggedIn = true;
    });
    this.isLoggedIn = !!localStorage.getItem('token');
    this.user = JSON.parse(localStorage.getItem('userData'));
    const theme = localStorage.getItem('dark');
    if (theme) {
      this.$vuetify.theme.dark = theme === 'true';
    }
  },
  methods: {
    logout() {
      User.logout().then(() => {
        localStorage.removeItem('token');
        localStorage.removeItem('userData');
        this.isLoggedIn = false;
        this.$router.push({
          name: 'Login'
        });
      });
    },
    toggleTheme() {
      this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
      localStorage.setItem('dark', this.$vuetify.theme.dark.toString());
    }
  }
};
</script>

<style scoped lang="scss">

.footer {
  margin: 15px;
}

.navbar-link {
  text-decoration: none;
  color: inherit;
}

.nav-logo {
  font-size: 20px;
}

.wrapper-app {
  height: 64px !important;
}

</style>
