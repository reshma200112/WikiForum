
<template>
  <div>

    <div v-if="screenWidth >= 1268">
      <Header @handeDrawer="drawer = !drawer" />

      <Sidebar :drawer="drawer" />

    </div>
    <div v-else>
      <ResponsiveHeader @handeDrawer="drawerr = !drawerr" />
      <ResponsiveSidebar :drawerr="drawerr" />

    </div>

    <v-subheader class="d-flex justify-space-between align-center">
      <h2>User List</h2>
    </v-subheader>
    <div class="sort-icon" style="float:right;">



      <button id="ascending" class="btnUserSort" @click="onSortChangeScore(1)" v-if="this.sorted == 1"
        style="background-color: rgb(220, 239, 247);">
        <span>Points
          <v-icon> mdi-arrow-up</v-icon>
        </span>

      </button>

      <button id="ascending" class="btnUserSort" @click="onSortChangeScore(1)" v-if="this.sorted != 1">

        <span>Points
          <v-icon> mdi-arrow-up</v-icon></span>
      </button>
      <button id="descending" class="btnUserSort" @click="onSortChangeScore(2)">Points
        <v-icon>mdi-arrow-down</v-icon>
      </button>
    </div>

    <v-col lg="12" cols="12">

      <v-form class="userSearch" @submit.prevent="getSearchUser()">
        <v-text-field v-model="search" v-on:keyup="getSearchUser()" @submit.prevent="getSearchUser()" class="p-0 m-0 mt-6"
          dense append-icon="mdi-magnify" outlined rounded placeholder="Search" style="max-width:285px" />
      </v-form>



      <div v-if="valuee">
        <p></p>

        <div class="loader"></div>


      </div>
      <div v-else>

        <v-data-table :headers="headers" :items="userDetails" :search="search" :items-per-page="itemsPerPage"
          :page.sync="currentPage" :total-items="totalUsers" :server-items-length="totalUsers" class="elevation-1"
          :footer-props="{
            'items-per-page-options': [12],
            'disable-items-per-page': true,
          }">
          <template v-slot:[`item.name`]="{ item }">
            <div style="  width:110px; max-width:120px;
          word-wrap:break-word;">
              {{ item.name }}</div>
          </template>
          <template v-slot:[`item.email`]="{ item }">
            <div style=" width:250px;word-wrap:break-word;
    ">
              {{ item.email }}</div>
          </template>

          <template v-slot:[`item.type`]="{ item }">
            <div v-if="item.type == 1">

              Google User
            </div>
            <div v-if="item.type == 0">

              Normal User

            </div>
          </template>
          <template v-slot:[`item.status`]="{ item }">
            <div v-if="item.status == 1" style="width:118px">
              <v-icon style="color:green">mdi-checkbox-blank-circle </v-icon>
              Active
            </div>
            <div v-if="item.status == 2">
              <v-icon style="color:red">mdi-checkbox-blank-circle </v-icon>

              Inactive
            </div>
            <div v-if="item.status == 0">
              <v-icon style="color:orange">mdi-checkbox-blank-circle </v-icon>

              Not-Verified
            </div>
          </template>
          <template v-slot:[`item.action`]="{ item }">
            <div v-if="item.status == 1">
              <v-btn style="    width: 118px;" color="red" class="white--text" v-on:click="Active(item.id)"
                :disabled="pausedFor2Seconds">

                De-Activate
              </v-btn>

            </div>
            <div v-if="item.status == 2">

              <v-btn style="    width: 118px;" color="green" class="white--text" v-on:click="deActive(item.id)"
                :disabled="pausedFor2Seconds">
                Activate</v-btn>

            </div>

            <div v-if="item.status == 0">

              <v-btn style="   cursor:default; width: 118px;" color="orange" class="white--text">
                Pending</v-btn>

            </div>

          </template>
        </v-data-table>
      </div>


    </v-col>
  </div>
</template>


<script>

import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'

import http from '../../src/http-common'
import Header from '../../src/components/Header.vue'
import Sidebar from '../../src/components/Sidebar.vue'
import ResponsiveSidebar from '@/components/ResponsiveSidebar.vue'
import ResponsiveHeader from '@/components/ResponsiveHeader.vue'
export default {
  components: { Sidebar, Header, ResponsiveSidebar, ResponsiveHeader },
  name: "User-list",
  data() {
    return {
      valuee: false,
      pausedFor2Seconds: false,
      totalItems: 0,
      sorted: 1,
      currentPage: 1,
      page: 1,
      itemsPerPage: 12,
      perPage: '',
      totalUsers: 0,
      search: '',
      cards: ["Today", "Yesterday"],
      screenWidth: window.innerWidth,

      drawer: true,
      drawerr: false,
      headers: [

        { text: "NAME", sortable: false, value: "name", width: "15%", },
        { text: "EMAIL", value: "email", sortable: false, width: "25%" },
        {
          text: "USER TYPE",
          value: "type",
          sortable: false,
          width: "15%",

        },
        { text: "POINTS", value: "points", width: "15%", sortable: false },


        {
          text: "STATUS",

          value: "status",

          sortable: false,
          width: "15%",
        },
        {
          text: "JOINED DATE  ",

          value: "join_date",

          sortable: false,
          width: "15%",
        },

        {
          text: "ACTION", value: "action", sortable: false,

          width: "15%"
        },
      ],

      userDetails: [
        {
          id: '',
          name: '',
          action: '',
          email: '',
          type: '',
          status: '',
          points: '',
          join_date: ''
        }

      ],

    }
  },
  mounted() {
    window.addEventListener('resize', this.updateScreenWidth);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.updateScreenWidth);
  },

  methods: {

    updateScreenWidth() {
      this.screenWidth = window.innerWidth;
    },
    async getUser() {
      this.valuee = true;

      let text = this.search.trim();
      let regex2 = /\\/g;
      text = text.replace(regex2, "\\\\");
      text = text.replace(/_/g, "\\_");
      text = encodeURIComponent(text);
      await http.get(`/loggedInUser/search?data=${text}&page=${this.currentPage}&limit=${this.itemsPerPage}?&sort=${this.sorted}`).then((response) => {
        console.log(response)

        this.userDetails = response.data.users.data
        this.totalUsers = response.data.users.total
        this.perPage = response.data.users.per_page

        this.userDetailss = this.userDetails.map((item) => item.status);
      }).catch(error => {
  if (error.response && error.response.status === 401) {
    localStorage.clear('TOKEN')
          window.location.reload()
    console.log('User is not authorized. Please log in.');
  }
});

      this.valuee = false;


    },
    async getSearchUser() {
      this.currentPage = 1;

      let text = this.search.trim();
      let regex2 = /\\/g;
      text = text.replace(regex2, "\\\\");
      text = text.replace(/_/g, "\\_");
      text = encodeURIComponent(text);
      http.get(`/loggedInUser/search?data=${text}&page=${this.page}&limit=${this.itemsPerPage}?&sort=${this.sorted}`).then((response) => {
        console.log(response)
        console.log("ee", response.data)
        this.userDetails = response.data.users.data
        this.totalUsers = response.data.users.total
        this.perPage = response.data.users.per_page
        console.log("qq", this.totalUsers)

        this.userDetailss = this.userDetails.map((item) => item.status);
        console.log("1122222", this.userDetailss);

        console.log("ww", this.userDetails);

      });
    },

    async Active(id) {

      this.valuee = true;
      const bodyParameters = {
        status: 2
      }
      http.put("/manageUser/" + id, bodyParameters).then((response) => {
        console.log(response)
        console.log("ee", response.data)


        this.getUser().then(() => {
          this.valuee = false;
        });


      });



      this.$toast.error("Account De-Activated", { $toastId: 12 })



    },
    async deActive(id) {
      this.valuee = true;



      this.valuee = true
      const bodyParameters = {
        status: 1
      }
      http.put("/manageUser/" + id, bodyParameters).then((response) => {
        console.log(response)
        console.log("ee", response.data)


        this.getUser().then(() => {
          this.valuee = false;
        })

      });




      this.$toast.success("Account Activated")


    },
    onSortChangeScore(sort) {
      this.sorted = sort;
      if (this.sorted == 2) {
        document.getElementById('descending').style.backgroundColor = "#dceff7";
        document.getElementById('ascending').style.backgroundColor = "white";
      }
      if (this.sorted == 1) {
        document.getElementById('ascending').style.backgroundColor = "#dceff7";
        document.getElementById('descending').style.backgroundColor = "white";

      }



      http.get(`loggedInUser/search?data=${this.search}&sort=${this.sorted}`).then((response) => {

        this.currentPage = 1;
        console.log(response)
        console.log("ee", response.data)
        this.userDetails = response.data.users.data
        this.totalUsers = response.data.users.total
        this.perPage = response.data.users.per_page
        console.log("qq", this.totalUsers)
        this.userDetailss = this.userDetails.map((item) => item.status);
        console.log("1122222", this.userDetailss);

        console.log("ww", this.userDetails);

      })

    },

  },




  watch: {
    currentPage: {
      handler() {

        this.getUser();
      },
      immediate: false,
    },
  },

  beforeMount() {
    this.getUser();

  }

}
</script>

<style scoped>
.v-application--is-ltr .v-data-footer__pagination {
  margin-left: auto;

}


.btnUserSort {
  display: inline-block;
  font: inherit;
  color: #516083;
  border: 2px solid #e7e7e7 !important;
  background: white;
  padding: 0;
  cursor: pointer;
  width: -moz-fit-content;
  width: fit-content;
  width: 70px;
  padding-top: 10px;
  padding-bottom: 10px;
  font-size: x-small;
}

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

.my-swal {
  max-width: 200px;
}
</style>