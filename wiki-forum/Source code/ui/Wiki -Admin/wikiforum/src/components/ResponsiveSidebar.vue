<!-- eslint-disable vue/no-mutating-props -->
<template>
  <v-navigation-drawer v-model="drawerr" app > 
    <a href="/dashboard" style="text-decoration: none;">
    <v-img src="" class="pa-4" >
      <div class="text-center">
        <v-avatar class="mb-4" color="" size="54" style="border-radius: 0px;    width: 123px;">
          <v-img aspect-ratio="300">
            <img :src="require('@/assets/wiki.png')" alt="" class="wf-logoo" />

          </v-img>

        </v-avatar>
        <h2 class="white-text" style="color:rgb(93 171 234)">Wiki-Forum</h2>
      </div>
    </v-img>
  </a>
    <v-divider></v-divider>



    <template>
      <div>

          <v-list-item :class="getSidebarItemClass('/dashboard')" @click="getRoute('/dashboard')">
            <v-list-item-icon>
              <v-icon>mdi-microsoft-windows</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              
              <v-list-item-title>Dashboard</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
 
          <v-list-item :class="getSidebarItemClass('/userlist')" @click="getRoute('/userlist')">
            <v-list-item-icon>
  
              <v-icon>mdi-account-group</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>User List</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item :class="getSidebarItemClass('/reputation')" @click="getRoute('/reputation')">
            <v-list-item-icon>
              <v-icon>mdi-clipboard-list-outline</v-icon>
           
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Reputation</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item :class="getSidebarItemClass('')" @click="deleteItem()"  class="logoutBtn">
            <v-list-item-icon>
  
              <v-icon>mdi-logout</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Logout      </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
     

          <v-dialog v-model="dialogDelete" max-width="400px">
          <v-card>
            <v-card-title class="text-h5">Are you sure want to logout?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </div>
    </template>

  </v-navigation-drawer>
</template>

<script>
import Swal from 'sweetalert2'

export default {
  props: ['drawerr'],
  name: "Side-bar",
  data() {
    return {
      dialogDelete: false,

      links: [

      ],
    }
  },
  watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
    },

  methods: {
    deleteItemConfirm(){
      localStorage.clear('TOKEN')
        window.location.reload()
    },
    deleteItem () {
   
        this.dialogDelete = true
     
      },
      closeDelete () {
        this.dialogDelete = false

      },
    clear() {
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
    getRoute(route)
    {
      if(this.$route.path==route){
        this.$router.go();
      }
      else{
        this.$router.push(route);
      }
    },
    getSidebarItemClass(route) {
   
      return {
        'active-sidebar-item': this.$route.path === route,
      };
      
    },
    
  },
}
</script>

<style>
.active-sidebar-item {
  background-color:#ccf0ff;
  color: white;
}

.routelink {
  text-decoration: none;
}
@media screen and (min-height: 350px){
.logoutBtn{
  position:absolute;bottom:2%;width:100%
}
  
}
@media screen and (max-height: 350px){
.logoutBtn{
  gap:2px;

}
}
</style>