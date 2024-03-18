<template>
    <v-app-bar app elevate-on-scroll elevation="3" color="white">
      <v-app-bar-nav-icon @click="$emit('handeDrawer')"></v-app-bar-nav-icon>
      <v-spacer />
     
      <v-spacer />
      <v-menu offset-y>
        
      </v-menu>
      <v-chip link style="cursor:default;pointer-events: none;">
              <v-badge dot bottom color="green" offset-y="10" offset-x="10">
                <v-avatar size="40">
                    <img          :src="require('../assets/admin.jpg')"/>
                </v-avatar>
              </v-badge>
              <span class="ml-3">Admin</span>
            </v-chip>
    </v-app-bar>
  </template>
  
  <script>
import Swal from 'sweetalert2'

  export default {
    name: "Head-er",
    data() {
      return {
        menus: [
       
          { title: "Logout", icon: "mdi-logout" },
        ],
      
      };
    },
    methods:{
        logout() {
      Swal.fire({
        title: 'Logout!',
        text: "Are you sure want to logout?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Logout',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          localStorage.clear('TOKEN')
        window.location.reload()
        }
      })
    },
    }
  };
  </script>
  
  <style scoped></style>