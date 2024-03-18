<template>
  <v-app id="inspire">

    <v-main>
      <v-container class="py-8 px-6" fluid>
       <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>

export default {
  
  data: () => ({
    cards: ["Today", "Yesterday"],
    drawer: true,
    drawerr:false
  }),
};
</script>
<style>
  toast-icon, .toast-icon img {
    width: 30px;
    height: 30px;
    margin-top:-18px
    
  }
</style>