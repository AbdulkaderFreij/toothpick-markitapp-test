<template>
  <div id="app">
    <v-app>
      <page-header />
      <main>
        <v-container fluid>
          <router-view></router-view>
        </v-container>
      </main>
    </v-app>
  </div>
</template>

<script>
import PageHeader from "./components/Header.vue";
export default {
  name: "app",
  components: {
    PageHeader,
  },
};
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

.v-application--wrap {
  min-height: unset !important;
}

.navlink {
  padding: 0px 30px 0px 30px;
}

.v-toolbar__title {
  overflow: unset;
}

#nav {
  padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-view-exact-active {
      color: #42b983;
    }
  }
}
</style>
