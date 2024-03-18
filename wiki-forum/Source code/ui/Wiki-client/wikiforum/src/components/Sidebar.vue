<template>
  <div class="containerSideBar" v-if="tokenFlag == true && routerPath != '/tagPage' && routerPath != '/tagpage'"
    @click="closeMOdal()">
    <nav id="sideBarNav">
      <ul class="sideBarUl">
        <router-link to="" class="open-close" :active-class="open - close" @click="OpenClose()">
          <li style="list-style: none !important;">
            <fa class="fas" icon="bars" />
            <span class="navitem"> </span>
          </li>
        </router-link>
        <a class="sidebar-list" exact>
          <li style="list-style: none !important;" @click="handleClick('/dashboard')" class="liSidebarList"
            :class="{ activeSide: isActive('/dashboard') }">
            <fa class="fas" icon="house" />
            <span class="navitem">Dashboard</span>
          </li>
        </a>
        <a class="sidebar-list">
          <li style="list-style: none !important;" @click="handleClick('/profile')" class="liSidebarList"
            :class="{ activeSide: isActive('/profile') }">
            <fa class="fas" icon="user" />
            <span class="navitem">Profile</span>
          </li>
        </a>
        <a class="sidebar-list">
          <li style="list-style: none !important;" @click="handleClick('/tagList')" class="liSidebarList"
            :class="{ activeSide: isActive('/tagList') }">
            <fa class="fas" icon="tags" />
            <span class="navitem">Tags</span>
          </li>
        </a>
        <a class="sidebar-list">
          <li style="list-style: none !important;" @click="handleClick('/userlist')" class="liSidebarList"
            :class="{ activeSide: isActive('/userlist') }">
            <fa class="fas" icon="users" />
            <span class="navitem">Users</span>
          </li>
        </a>

        <a class="sidebar-list">
          <li style="list-style: none !important;" @click="handleClick('/AskQuestion')" class="liSidebarList"
            :class="{ activeSide: isActive('/AskQuestion') }">
            <fa class="fas" icon="question" />
            <span class="navitem">Ask Question</span>
          </li>
        </a>

        <a class="changepass" v-if="this.userType == 0">
          <li v-if="this.userType == 0" style="list-style: none !important; " @click="handleClick('/changepass')"
            class="liSidebarList" :class="{ activeSide: isActive('/changepass') }">

            <fa class="fas" icon="cog" />
            <span class="navitem">Change password</span>
          </li>
        </a>
      </ul>
      <ul>
        <li @click="clear()" style="list-style: none !important;">
          <a href="#" class="logout">
            <fa class="fas" icon="sign-out-alt" />
            <span class="navitem">Logout</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
import Swal from 'sweetalert2'
import http from '../http-common'
export default {
  name: "side-1",
  data() {
    return {
      activeRoute: '',
      tokenFlag: false,
      userType: 1,
      sideBarnav: ''
    };
  },
  computed: {
    routerPath() {
      return this.$route.path;
    }
  },

  async created() {
    let token = localStorage.getItem('TOKEN');
    if (token != null) {
      this.tokenFlag = true
    }
    else this.tokenFlag = false;
    await this.getCurrentUser();
  },
  mounted() {
    if (localStorage.getItem('TOKEN') != null) {
      this.sideBarnav = ("true" === localStorage.getItem('sidebarFlag'));
      if (window.innerWidth <= 850) {
        this.sideBarnav = false;
      }
      if (window.innerWidth > 850) {
        if (this.sideBarnav) {
          document.getElementById('gridMain').style.gridTemplateColumns = '280px calc(100vw - 280px)'
          document.getElementById("sideBarNav").style.width = "280px";
        }
        else {
          document.getElementById('gridMain').style.gridTemplateColumns = '80px calc(100vw - 80px)'
          document.getElementById("sideBarNav").style.width = "80px";
        }
      }
    }
  },
  methods: {
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
         localStorage.clear();
          window.location.href = "/";
          window.location.reload();
        }
      })
    },
    async getCurrentUser() {
      http.get("/getuser").then((response) => {

        this.userType = response.data.user.type;
      });
    },
    OpenClose() {
      let screenwidth = window.innerWidth;
      if (this.sideBarnav) {
        if (screenwidth < 357) {
          document.getElementById('gridMain').style.gridTemplateColumns = '45px calc(100vw - 45px)'
          document.getElementById("sideBarNav").style.width = "45px";
        }
        else {
          document.getElementById('gridMain').style.gridTemplateColumns = '80px calc(100vw - 80px)'
          document.getElementById("sideBarNav").style.width = "80px";
        }
        this.sideBarnav = false;
      }
      else {
        document.getElementById('gridMain').style.gridTemplateColumns = '280px calc(100vw - 280px)'
        document.getElementById("sideBarNav").style.width = "280px";
        this.sideBarnav = true;
      }
      localStorage.setItem('sidebarFlag', this.sideBarnav)
    },
    modalCloseDelete() {
      document.getElementById("logout-modal").style.display = "none";
    },

    modalCloseLogout() {
      document.body.classList.remove('overlay');
      document.getElementById("logout-modal").style.display = "none";
    },
    closeMOdal() {
      let body = document.getElementById("body");
      let box = document.getElementById("box");
      body.addEventListener(
        "click",
        function (e) {
          console.log(e);
          box.style.height = "0px";
          box.style.opacity = 0;
          this.down = false;
        },

        false
      );
    },

    ProfieRefresh() {
      window.scrollTo(0, 0);
    },
    handleClick(route) {
      // Get the current route path
      const currentRoute = this.$route.path;

      // Check if the clicked route is the same as the current route
      if (route === currentRoute) {
        // Reload the current page
        this.$router.go();
      } else {
        // Navigate to the clicked route
        this.$router.push(route);
      }

      // Set the active route
      this.activeRoute = route;
    },
    isActive(route) {
      // Get the current route path
      const currentRoute = this.$route.path;

      // Check if the given route is the same as the current route
      return route === currentRoute;
    },
  },
};
</script>

<style>

#sideBarNav{
  display: flex;
  overflow-y: auto;
    justify-content: space-between;
    flex-direction: column;gap: 30px;
}
.overlay {
  height: 100vh;
  overflow-y: hidden;
}


.list-style {
  display: none !important;
}

.container {
  display: flex;
}

nav {
  position: fixed !important;
  top: 90px;
  bottom: 0;
  height: calc(100vh - 90px);
  left: 0;
  width: 80px;
  background: #fff;
  overflow: hidden;

  /* transition: 1s; */
}

@media screen and (max-width: 1700px) {
  nav {
    z-index: 0;
  }
}

/* 
nav:hover {
  width: 280px;
  transition: 1s;
} */

.logo {
  text-align: center;
  display: flex;
  margin: 10px 0 0 10px;
  padding-bottom: 3rem;
}

.logo span {
  font-weight: bold;
  padding-left: 15px;
  font-size: 18px;
  margin-top: 16px;
  margin-left: 0px;
  width: max-content;
  text-transform: uppercase;
}



.main-top {
  display: flex;
  width: 100%;
}

.main-top .search {
  background: #fff !important;
}

.main-top #i {
  position: fixed;
  right: 10px;
  margin: 10px 30px;
  color: rgb(110, 109, 109);
  cursor: pointer;
}

.main-body {
  display: flex;
}

.main-content-profile {
  position: relative;
  width: 100%;
}

ul li {
  list-style: none;
  margin-left: -5px;
}

a {
  position: relative;
  width: 280px;
  font-size: 14px;
  color: rgb(85, 83, 83);
  display: table;
  padding: 10px;
}

.logo .wf-logo {
  width: 50px;
  height: 50px;
}

#logo-li {
  display: flex;
  margin-left: 0;
}

.navitem {
  position: relative;
  bottom: 3px;
  margin-left: 8px;
  font-size: larger;
}

nav .fas {
  position: relative;
  width: 70px;
  height: 25px;

  font-size: 15px;
  text-align: center;
}

.logo:hover {
  background: transparent;
}

a:hover {
  background: #eee;
}

nav li:hover {
  font-weight: bold;
}

.column {
  float: left;
  width: 25%;
  padding: 0 10px;
}

/* Remove extra left and right margins, due to padding */
.row {
  margin: 0 -5px;
  display: block;
  padding: 20px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

.trophy-logo {
  padding: 10px 10px 10px 10px;
  width: 30%;
  height: 30%;
}



/* Responsive columns */
@media screen and (max-width: 600px) {
  .column {
    width: 100%;
    display: block;
    margin-bottom: 20px;
  }
}

/* Style the counter cards */
.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  padding: 16px;
  text-align: center;
  background-color: #f1f1f1;
  margin-bottom: 20px;
}

img {
  display: block;
}

.btn {
  display: inline-block;
  font: inherit;
  background: none;
  border: none;
  color: inherit;
  padding: 0;
  cursor: pointer;
}

.btn:focus {
  outline: 0.5rem auto #4d90fe;
}

.visually-hidden {
  position: absolute !important;
  height: 1px;
  width: 1px;
  overflow: hidden;
  clip: rect(1px, 1px, 1px, 1px);
}

/* Profile Section */

/* .profile {
  padding: 5rem 0;
} */

.profile::after {
  content: "";
  display: block;
  clear: both;
}

.profile-image {
  float: left;
  width: calc(33.333% - 1rem);
  display: flex;
  justify-content: center;
  align-items: center;
  margin-right: 3rem;
}

.profile-image img {
  border-radius: 50%;
}

.profile-user-settings,
.profile-stats,
.profile-bio {
  float: left;
  width: calc(66.666% - 2rem);
}

.profile-user-settings {
  margin-top: 1.1rem;
}

.profile-user-name {
  font-size: 1.2em !important;

  display: inline-block;
  font-size: 3.2rem;
  font-weight: 300;
}

.profile-edit-btn {
  font-size: 1.4rem;
  line-height: 1.8;
  border: 0.1rem solid #dbdbdb;
  border-radius: 0.3rem;
  padding: 0 2.4rem;
  margin-left: 2rem;
}

.profile-settings-btn {
  font-size: 2rem;
  margin-left: 1rem;
}

.profile-stats {
  margin-top: 2.3rem;
}

.profile-stats li {
  display: inline-block;
  font-size: 1.6rem;
  line-height: 1.5;
  margin-right: 4rem;
  cursor: pointer;
}

.profile-stats li:last-of-type {
  margin-right: 0;
}

.profile-bio {
  font-size: 1.6rem;
  font-weight: 400;
  line-height: 1.5;
  margin-top: 2.3rem;
}

.profile-real-name,
.profile-stat-count,
.profile-edit-btn {
  font-weight: 600;
}


.router-link-exact-active {
  background-color: #d6edff;
  font-weight: 700;
}

.open-close {
  background-color: transparent;
}

.activeSide {
  font-weight: bolder;
  color: #14709c;
  background-color: #b4bdd166;
  padding-top: 7px;
  padding-bottom: 7px;
}

.liSidebarList {
  cursor: pointer;
}</style>