<template>
  <div class="home">
    <img
      alt="Vue logo"
      id="logo"
      src="../assets/blur-close-up-composition-craft-281962.jpg"
    />
    <HelloWorld msg="Welcome to My Posts App" />
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from "@/components/HelloWorld.vue";

export default {
  name: "Home",
  components: {
    HelloWorld,
  },
};
</script>

<style lang="scss">
#logo {
  width: 240px;
  height: 360px;
}
</style>
