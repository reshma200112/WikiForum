<template>
  <div id="body" @click="closeMOdal()">
    <div v-if="isLoading">
      <Loader />
    </div>
    <div v-else class="container">


      <section class="main">

        <div class="main-data">
          <div class="search-container-dash">
            <form action="" v-on:keyup="searchTag()" @submit.prevent="searchTag()">
              <div class="inputDiv">
                <v-responsive id="searchQuestions" class="mx-auto" max-width="344" >
                  <v-text-field density="compact" variant="solo" placeholder="Search here" @input="searchTag()"
                    prepend-inner-icon="mdi-magnify" v-model="searchKey"></v-text-field>
                </v-responsive>
              </div>
            </form>
          </div>
          <div class="sort-icon">

            <button id="ascending" class="btnUserSort" @click="onSortChange(2)" v-if="searchFlag != 1">Reputation <fa
                icon="fa-solid fa-arrow-up"></fa> <v-tooltip activator="parent" location="bottom" :open-delay="500">Sort
                by ascending</v-tooltip></button>
            <button id="descending" class="btnUserSort" @click="onSortChange(1)" v-if="searchFlag != 1">Reputation <fa
                icon="fa-solid fa-arrow-down"></fa> <v-tooltip activator="parent" location="bottom" :open-delay="500">Sort
                by descending</v-tooltip></button>
          </div>
          <div class="main-body-user">
            <div class="main-content-userlist" v-if="flag == 1">
              <div v-if="searchFlag == 1" class="null-result">
                <div class="fof">
                  <h1 class="nodataU">No Data</h1>
                </div>
              </div>
              <div v-else>
                <div class="flex">
                  <div class="row-userlist">
                    <div class="column-user" :key="userdetails.id" v-for="userdetails in searchResult">
                      <div class="card-user">
                        <div class="userImg" id="userListImageDiv">
                          <img  v-if="userdetails.profile_picture == null" :src="require('@/assets/avatar.png')" alt=""
                             id="user-profile-image" class="userlistImg"/>
                          <img v-else v-bind:src="userdetails.profile_picture" referrerpolicy="no-referrer" alt=""
                          id="user-profile-image" class="userlistImg"/>
                        </div>
                        <h1>{{ showUserName(userdetails.name)}}<v-tooltip activator="parent" id="userlist-tooltip" location="bottom" v-if="userdetails.name.length>12"
                >{{ userdetails.name }}</v-tooltip
              ></h1>

                        <h4>Points:{{ userdetails.points }}</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="dashboard-footer" id="user-pages" v-if="searchFlag != 1 && arrayLength != 0">
                <ul class="dashboard-pagination">

                  <li style="list-style: none !important;" id="lastPageButton">
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-if="pagination.current_page == 1" disabled
                      class="ipage"><v-tooltip activator="parent" location="top" open-delay="500">First Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-backward-step"></fa>
                    </v-btn>
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else @click.prevent="changePage(1)"
                      class="ipage"><v-tooltip activator="parent" location="top" open-delay="500">First Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-backward-step"></fa>
                    </v-btn>
                  </li>
                  <li style="list-style: none !important;">
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-if="pagination.current_page > 1"
                      @click.prevent="changePage(pagination.current_page - 1)" class="ipage">
                      <v-tooltip activator="parent" location="top" open-delay="500">Previous Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-caret-left"></fa>
                    </v-btn>
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else class="ipage" disabled>
                      <v-tooltip activator="parent" location="top" open-delay="500">Previous Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-caret-left"></fa>
                    </v-btn>
                  </li>
                  <div v-for="page in pagination.last_page" :key="page" class="page-info" v-show="pagination.current_page == page">
                    <label v-if="pagination.current_page == page" color="#2d4b7a" @click.prevent="changePage(page)"
                      class="mpage">Page {{ page }} of {{ pagination.last_page }}</label>
                  </div>
                  <li style="list-style: none !important;">
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a"
                      v-if="pagination.current_page < pagination.last_page"
                      @click.prevent="changePage(pagination.current_page + 1)" class="ipage">
                      <v-tooltip activator="parent" location="top" open-delay="500">Next Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-caret-right"></fa>
                    </v-btn>
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else class="ipage" disabled>
                      <v-tooltip activator="parent" location="top" open-delay="500">Next Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-caret-right"></fa>
                    </v-btn>
                  </li>
                  <li style="list-style: none !important;" id="lastPageButton">
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a"
                      v-if="pagination.current_page == pagination.last_page" disabled class="ipage">
                      <v-tooltip activator="parent" location="top" open-delay="500">Last Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-forward-step"></fa>
                    </v-btn>
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else
                      @click.prevent="changePage(pagination.last_page)" class="ipage">
                      <v-tooltip activator="parent" location="top" open-delay="500">Last Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-forward-step"></fa>
                    </v-btn>
                  </li>
                </ul>
              </div>
            </div>

            <div class="main-content-userlist" v-if="flag == 0">
              <div class="flex">
                <div class="row-userlist">
                  <div class="column-user" v-for="user in users" :key="user.id">
                    <div class="card-user">
                      <div class="userImg" id="userListImageDiv">
                        <img v-if="user.profile_picture == null" :src="require('@/assets/avatar.png')" alt="" class="userlistImg"
                         id="user-profile-image"/>
                        <img v-else v-bind:src="user.profile_picture" referrerpolicy="no-referrer" alt="" class="userlistImg"
                       id="user-profile-image"/>
                      </div>
                      <h1>{{ showUserName(user.name)}}<v-tooltip activator="parent" id="userlist-tooltip" location="bottom" v-if="user.name.length>12"
                >{{ user.name }}</v-tooltip
              ></h1>

                      <h4>Points:{{ user.points }}</h4>
                    </div>
                  </div>

                </div>

              </div>
              <div class="dashboard-footer" id="user-pages" v-if="searchFlag != 1 && arrayLength != 0">
                <ul class="dashboard-pagination">

                  <li style="list-style: none !important;" id="lastPageButton">
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-if="pagination.current_page == 1" disabled
                      class="ipage"><v-tooltip activator="parent" location="top" open-delay="500">First Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-backward-step"></fa>
                    </v-btn>
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else @click.prevent="changePage(1)"
                      class="ipage"><v-tooltip activator="parent" location="top" open-delay="500">First Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-backward-step"></fa>
                    </v-btn>
                  </li>
                  <li style="list-style: none !important;">
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-if="pagination.current_page > 1"
                      @click.prevent="changePage(pagination.current_page - 1)" class="ipage">
                      <v-tooltip activator="parent" location="top" open-delay="500">Previous Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-caret-left"></fa>
                    </v-btn>
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else class="ipage" disabled>
                      <v-tooltip activator="parent" location="top" open-delay="500">Previous Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-caret-left"></fa>
                    </v-btn>
                  </li>
                  <div v-for="page in pagination.last_page" :key="page" class="page-info" v-show="pagination.current_page == page">
                    <label v-if="pagination.current_page == page" color="#2d4b7a" @click.prevent="changePage(page)"
                      class="mpage">Page {{ page }} of {{ pagination.last_page }}</label>
                  </div>
                  <li style="list-style: none !important;">
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a"
                      v-if="pagination.current_page < pagination.last_page"
                      @click.prevent="changePage(pagination.current_page + 1)" class="ipage">
                      <v-tooltip activator="parent" location="top" open-delay="500">Next Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-caret-right"></fa>
                    </v-btn>
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else class="ipage" disabled>
                      <v-tooltip activator="parent" location="top" open-delay="500">Next Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-caret-right"></fa>
                    </v-btn>
                  </li>
                  <li style="list-style: none !important;" id="lastPageButton">
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a"
                      v-if="pagination.current_page == pagination.last_page" disabled class="ipage">
                      <v-tooltip activator="parent" location="top" open-delay="500">Last Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-forward-step"></fa>
                    </v-btn>
                    <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else
                      @click.prevent="changePage(pagination.last_page)" class="ipage">
                      <v-tooltip activator="parent" location="top" open-delay="500">Last Page</v-tooltip>
                      <fa id="faIcon" icon="fa-solid fa-forward-step"></fa>
                    </v-btn>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!--  -->

          <!--  -->
        </div>
      </section>

    </div>

  </div>
</template> 
<script>
import http from "../../http-common";
import Loader from '../Loader.vue';
export default {
  components: {
    Loader
  },
  name: "knk-7",
  data() {
    return {
      isLoading: true,
      User: {},
      userType: "",
      userlist: undefined,
      flag: 0,
      searchResult: [],
      searchFlag: 0,
      searchKey: "",
      notifications: "",
      sideBarnav: false,
      // 
      arrayLength: undefined,
      users: [],
      pagination: {
        current_page: 1
      },
      // sortKey: [{ key: "Ascending", sort: 2 }, { key: "Descending", sort: 1 }],
      sorted: 1,
      // 
    };
  },
  methods: {
    showUserName(userName)
{
let user_name=userName;
if(user_name.length>15)
{
  user_name=user_name.slice(0,15)+'...'
}
  return user_name;
},
    clear() {
      localStorage.removeItem("TOKEN");
      localStorage.removeItem("User Details");
      localStorage.removeItem("User image");
      localStorage.removeItem("User name");
      localStorage.removeItem("email");
      localStorage.removeItem("questionId");
      window.location.href = "/";
    },
    async getUser() {
      http.get("/getuser").then((response) => {
        this.User = response.data.user;
        this.userType = response.data.user.type;

      });
    },
    searchTag() {
      this.flag = 1;
      if (this.searchKey == "") {
        this.searchFlag = 0;
      }
      let text = this.searchKey.trim();
      let regex2 = /\\/g;
      text = text.replace(regex2, "\\\\");
      text = text.replace(/_/g, "\\_");
      if (text == "") {
        this.fetchUsers();
        this.flag = 0;
      } else {
        text = encodeURIComponent(text);
        http
          .get("/users/search?data=" + text + "&sort=" + this.sorted)
          .then((response) => {
            console.log(response.data);
            console.log("Sfdsf", response.data);
            this.searchResult = response.data.users.data;
            this.pagination = response.data.users;
            // 
            if (this.searchResult.length === 0) {
              this.searchFlag = 1;
            } else {
              this.searchFlag = 0;
            }
            console.log("+++++++++", this.searchResult);
            if (this.sorted == 1) {
              document.getElementById('descending').style.backgroundColor = "#dceff7";
              document.getElementById('ascending').style.backgroundColor = "white";
            }
            if (this.sorted == 2) {
              document.getElementById('ascending').style.backgroundColor = "#dceff7";
              document.getElementById('descending').style.backgroundColor = "white";

            }
          })
          .catch(function (error) {
            console.log(error);
            throw error;
          });
      }
    },
    // notifictation
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
    navToProfile() {
      console.log('oooooooooooooooooooo');
      this.$router.push("/profile");
    },
    toggleNotifi() {
      let box = document.getElementById("box");

      if (this.down) {
        let except = document.getElementById("i");
        except.addEventListener(
          "click",
          function (ev) {
            if (this.down) {
              box.style.height = "0px";
              box.style.opacity = 0;
              this.down = false;
            } else {
              box.style.height = "510px";
              box.style.opacity = 1;
              this.down = true;
            }
            ev.stopPropagation(); //this is important! If removed, you'll get both alerts
          },
          false
        );
      } else {
        box.style.height = "510px";
        box.style.opacity = 1;
        this.down = true;
      }
    },
    NotificationDetails(Qid) {
      console.log(Qid);
      http.post("/questionviews/" + Qid, {}).then(() => { });
      this.$router.push("/questionView/" + Qid);
    },
    deleteNotification() {
      http.put("user/notification/delete").then(() => {

        window.location.reload();
      });
    },
    // sidebar
    OpenClose() {
      if (this.sideBarnav) {
        document.getElementById("sideBarNav").style.width = "80px";
        this.sideBarnav = false;
      } else {
        document.getElementById("sideBarNav").style.width = "280px";
        this.sideBarnav = true;
      }
    },
    dashboard() {
      window.scrollTo(0, 0);
      window.location.reload();
    },
    async fetchUsers() {
      http.get("/getAllUsers" + "?sort=" + this.sorted)
        .then(response => {
          if (this.sorted == 1) {
            document.getElementById('descending').style.backgroundColor = "#dceff7";
            document.getElementById('ascending').style.backgroundColor = "white";
          }
          if (this.sorted == 2) {
            document.getElementById('ascending').style.backgroundColor = "#dceff7";
            document.getElementById('descending').style.backgroundColor = "white";

          }
          this.users = response.data.users.data;
          this.pagination = response.data.users;
          this.isLoading = false;
        });
    },
    changePage(cpage) {
      if (this.searchKey.length > 0) {
        this.flag = 1;
        let text = this.searchKey.trim();
        if (text == "") {
          this.fetchUsers();
          this.flag = 0;
        } else {
          text = encodeURIComponent(text);
          http
            .get("/users/search?data=" + text + "&page=" + cpage + "&sort=" + this.sorted)
            .then((response) => {
              this.saveData(response);
            })
            .catch(function (error) {
              console.log(error);
              throw error;
            });
        }
      }
      else {
        http.get("/getAllUsers?page=" + cpage + "&sort=" + this.sorted)
          .then(response => {
            this.users = response.data.users.data;
            this.pagination = response.data.users;
            window.scrollTo(0, 0);
          });
      }
    },
    saveData(response) {
      this.searchResult = response.data.users.data;
      this.pagination = response.data.users;
      window.scrollTo(0, 0);
      if (this.searchResult.length === 0) {
        this.searchFlag = 1;
      } else {
        this.searchFlag = 0;
      }
    },
    onSortChange(sort) {
      this.sorted = sort;
      if (this.sorted == 1) {
        document.getElementById('descending').style.backgroundColor = "#dceff7";
        document.getElementById('ascending').style.backgroundColor = "white";
      }
      if (this.sorted == 2) {
        document.getElementById('ascending').style.backgroundColor = "#dceff7";
        document.getElementById('descending').style.backgroundColor = "white";

      }
      if (this.searchKey != '') {
        this.flag = 1;
        let text = this.searchKey.trim();
        if (text == "") {
          this.fetchUsers();
          this.flag = 0;
        } else {
          text = encodeURIComponent(text);
          http
            .get("/users/search?data=" + text + "&sort=" + this.sorted)
            .then((response) => {
              this.saveData(response);
            })
            .catch(function (error) {
              console.log(error);
              throw error;
            });
        }
      }
      else {
        http.get("/getAllUsers?sort=" + this.sorted)
          .then(response => {
            this.users = response.data.users.data;
            this.pagination = response.data.users;
          });
      }
    }
  },
 async  beforeMount() {
     http
       .get("/user/notification")
       .then((response) => {
         this.notifications = response.data;
          this.getUser().then(()=>{
          

          http.get("/getAllUsers" + "?sort=" + this.sorted)
            .then(response => {
              this.users = response.data.users.data;
              this.pagination = response.data.users;
              this.isLoading = false;
            });
          // 
        });

      })
      .catch(function (error) {
        throw error;
      });




  },
};
</script>
<style scoped>
#user-profile-image{
  border-radius: 50px; width:100px;height:100px

}
@import url("Userlist.css");
</style>