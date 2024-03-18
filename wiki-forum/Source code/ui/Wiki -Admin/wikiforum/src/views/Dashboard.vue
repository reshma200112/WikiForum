<template>
    <div>
   


    <div v-if="screenWidth >= 1268">
        <Header @handeDrawer="drawer =! drawer"/>
      <Sidebar :drawer="drawer"/>
    </div>
    <div v-else>
        <ResponsiveHeaderVue @handeDrawer="drawerr =! drawerr"/>
      <ResponsiveSidebar :drawerr="drawerr"/>

    </div>
  
    <div class="dashboard">
        <v-subheader class="d-flex justify-space-between align-center">
            <h2 style="margin-left:-1.5%">Dashboard</h2>
        </v-subheader>
   <br>
   <div v-if="valuee">
        <p></p>

        <div class="loader"></div>


      </div>
      <div v-else>
        <v-row>
            <v-col lg="17" cols="12">

                <v-row>
                    <v-col lg="3" cols="12" :key="index" v-for="(item,index) in Counts.data">
                        <v-card elevation="2" class="rounded-lg">
                            <v-card-text class="d-flex justify-space-between align-center">
                                <div class="">
                                    <strong>{{index}}</strong><br>
                                </div>
                                <div v-if="index=='Total Users'">
                                <v-avatar color="red" size="60">
                                    <span class="white--text">{{ item }}</span>
                                </v-avatar></div>
                                <div v-if="index=='Google Users'">
                                <v-avatar color="purple" size="60">
                                    <span class="white--text">{{ item }}</span>
                                </v-avatar></div>
                                <div v-if="index=='Normal Users'">
                                <v-avatar color="blue" size="60">
                                    <span class="white--text">{{ item }}</span>
                                </v-avatar></div>
                                <div v-if="index=='High Reputation Users'">
                                <v-avatar color="orange" size="60">
                                    <span class="white--text">{{ item }}</span>
                                </v-avatar></div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
        
<div>
<bar style="margin-top:4%"/>
</div>
<chart />
</div>


    </div></div>
</template>

<script>

import http from '../../src/http-common'
import Header from '../../src/components/Header.vue'
import Sidebar from '../../src/components/Sidebar.vue'
import ResponsiveSidebar from '@/components/ResponsiveSidebar.vue'
import ResponsiveHeaderVue from '@/components/ResponsiveHeader.vue'
import chart from '../views/ChartAdmin.vue'
import bar from '../views/BardiagramAdmin.vue'
export default {
    name: "Dasboard-vue",
    components: {Sidebar,Header,ResponsiveSidebar,ResponsiveHeaderVue,chart,bar},

    data() {
        return {
          valuee:false,
            screenWidth: window.innerWidth,
          Counts:{},
            cards: ["Today", "Yesterday"],
    drawer: true,
    drawerr: false,



          
        }
    },
    mounted() {
  window.addEventListener('resize', this.updateScreenWidth);
},
beforeDestroy() {
  window.removeEventListener('resize', this.updateScreenWidth);
},
created(){
    this.interval = setInterval(() => {
                this.count();

            }, 26000000)
  },

    methods: {
        updateScreenWidth() {
    this.screenWidth = window.innerWidth;
  },
      count(){
        this.valuee = true
        http
      .get("/getCount")
      .then((response) => {
        console.log(response.data)
        this.Counts=response


this.valuee = false
      }).catch(error => {
  if (error.response && error.response.status === 401) {
    console.log(error);
    // Unauthorized error - redirect to login page or show an error message
    console.log('User is not authorized. Please log in.');
    localStorage.clear('TOKEN')
        window.location.reload()
  }});
     
      
      // .catch(function (error) {
      //   throw error;
      // });
      }
    },
    beforeMount(){
      this.count()
    }
    
}
</script>

<style scoped>

.loader {
  --height-of-loader: 4px;
  --loader-color: #0071e2;
  width: 100%;
  height: var(--height-of-loader);
  border-radius: 30px;
  background-color: rgba(0, 0, 0, 0.2);
  position: relative;
}

.loader::before {
  content: "";
  position: absolute;
  background: var(--loader-color);
  top: 0;
  left: 0;
  width: 0%;
  height: 100%;
  border-radius: 30px;
  animation: moving 1s ease-in-out infinite;
  ;
}

@keyframes moving {
  50% {
    width: 100%;
  }

  100% {
    width: 0;
    right: 0;
    left: unset;
  }
}
</style>