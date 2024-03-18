<template>
  <div id="body" @click="closeMOdal()">
    <div v-if="isLoading">
      <Loader />
    </div>
    <div v-else class="container">


      <section class="main">

        <form action="" v-on:keyup="searchQuestion()" @submit.prevent="searchQuestion()" class="formSearch"
          v-if="dataLength > 0">
          <div class="inputDiv" ref="topScrollNavDiv">

            <v-responsive  id="searchQuestions" max-width="344" >
              <v-text-field placeholder="Search here" density="compact" variant="solo" @input="searchQuestion()"
                prepend-inner-icon="mdi-magnify" v-model="searchKey"></v-text-field>
            </v-responsive>
          </div>
        </form>
        <div class="main-body">
          <div class="main-content" v-if="flag == 1">
            <div v-if="searchFlag == 1" class="null-result">
              <div class="fof">
                <h1 class="nodataD" id="NoDataShow">No Data</h1>
              </div>
            </div>
            <div v-else>
              <div class="cardbox" :key="item.id" v-for="item in searchResult">
                <div class="card-detail">
                  <h2 @click="viewDetails(item.id)" style="white-space: pre-wrap">
                    {{ item.title }}
                  </h2>
                  <div class="restdata">
                    <div class="user" id="card-co">
                      <div class="user-detail">
                        <h4>
                          <fa icon="eye"></fa>
                          {{ item.views }} views
                        </h4>
                        <h4>
                          <fa icon="comment"></fa>
                          {{ item.answer_count }} Answers
                        </h4>

                        <p class="date">
                          Posted on {{ moment(item.created_at).calendar() }} by
                          {{ item.name }}
                        </p>
                        <div class="Forflex" id="tagsforquestion">
                          <div v-for="tag in item.tags" :key="tag.id" class="tagDIvQ">
                            <p class="TagDisplay">
                              <strong class="pop-color">#</strong>
                              {{ tag.tag_name }}
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="dashboard-footer" id="dashboard-page" v-if="searchFlag != 1 && dataLength > 0">
              <ul class="dashboard-pagination">
                <li style="list-style: none !important;" id="lastPageButton">
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-if="pagination.current_page == 1" disabled
                    class="ipage"><v-tooltip activator="parent" location="top" open-delay="500">First Page</v-tooltip>
                    <fa icon="fa-solid fa-backward-step"></fa>
                  </v-btn>
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else @click.prevent="changePage(1)"
                    class="ipage"><v-tooltip activator="parent" location="top" open-delay="500">First Page</v-tooltip>
                    <fa icon="fa-solid fa-backward-step"></fa>
                  </v-btn>
                </li>
                <li style="list-style: none !important;">
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-if="pagination.current_page > 1"
                    @click.prevent="changePage(pagination.current_page - 1)" class="ipage">
                    <v-tooltip activator="parent" location="top" open-delay="500">Previous Page</v-tooltip>
                    <fa icon="fa-solid fa-caret-left"></fa>
                  </v-btn>
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else class="ipage" disabled>
                    <v-tooltip activator="parent" location="top" open-delay="500">Previous Page</v-tooltip>
                    <fa icon="fa-solid fa-caret-left"></fa>
                  </v-btn>
                </li>
                <div v-for="page in pagination.last_page" :key="page" class="page-info">
                  <label v-if="pagination.current_page == page" color="#2d4b7a" @click.prevent="changePage(page)"
                    class="mpage">Page {{ page }} of {{ pagination.last_page }}</label>
                </div>
                <li style="list-style: none !important;">
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a"
                    v-if="pagination.current_page < pagination.last_page"
                    @click.prevent="changePage(pagination.current_page + 1)" class="ipage">
                    <v-tooltip activator="parent" location="top" open-delay="500">Next Page</v-tooltip>
                    <fa icon="fa-solid fa-caret-right"></fa>
                  </v-btn>
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else class="ipage" disabled>
                    <v-tooltip activator="parent" location="top" open-delay="500">Next Page</v-tooltip>
                    <fa icon="fa-solid fa-caret-right"></fa>
                  </v-btn>
                </li>
                <li style="list-style: none !important;" id="lastPageButton">
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a"
                    v-if="pagination.current_page == pagination.last_page" disabled class="ipage">
                    <v-tooltip activator="parent" location="top" open-delay="500">Last Page</v-tooltip>
                    <fa icon="fa-solid fa-forward-step"></fa>
                  </v-btn>
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else
                    @click.prevent="changePage(pagination.last_page)" class="ipage">
                    <v-tooltip activator="parent" location="top" open-delay="500">Last Page</v-tooltip>
                    <fa icon="fa-solid fa-forward-step"></fa>
                  </v-btn>
                </li>
              </ul>
            </div>
          </div>
          <hr />
          <div class="main-content" v-if="flag == 0">
            <div class="cardbox" :key="item.id" v-for="item in list">
              <div class="card-detail">
                <div>
                  <h2 @click="viewDetails(item.id)" style="white-space: pre-wrap">
                    {{ item.title }}
                  </h2>
                </div>
                <div class="restdata">
                  <div class="user" id="card-co">
                    <div class="user-detail">
                      <h4>
                        <fa icon="eye"></fa>
                        {{ item.views }} views
                      </h4>
                      <h4>
                        <fa icon="comment"></fa>
                        {{ item.answer_count }} Answers
                      </h4>
                      <div class="lastLine">
                        <p class="date">
                          Posted on {{ moment(item.created_at).calendar() }} by
                          {{ item.name }}
                        </p>
                        <div class="Forflex" id="tagsforquestion">
                          <div v-for="tag in item.tags" :key="tag.id" class="tagDIvQ">
                            <p class="TagDisplay">
                              <strong class="pop-color">#</strong>


                              {{ tag.tag_name }}
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="dashboard-footer" id="dashboard-page" v-if="searchFlag != 1 && dataLength > 0">
              <ul class="dashboard-pagination">
                <li style="list-style: none !important;" id="lastPageButton">
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-if="pagination.current_page == 1" disabled
                    class="ipage"><v-tooltip activator="parent" location="top" open-delay="500">First Page</v-tooltip>
                    <fa icon="fa-solid fa-backward-step"></fa>
                  </v-btn>
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else @click.prevent="changePage(1)"
                    class="ipage"><v-tooltip activator="parent" location="top" open-delay="500">First Page</v-tooltip>
                    <fa icon="fa-solid fa-backward-step"></fa>
                  </v-btn>
                </li>
                <li style="list-style: none !important;">
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-if="pagination.current_page > 1"
                    @click.prevent="changePage(pagination.current_page - 1)" class="ipage">
                    <v-tooltip activator="parent" location="top" open-delay="500">Previous Page</v-tooltip>
                    <fa icon="fa-solid fa-caret-left"></fa>
                  </v-btn>
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else class="ipage" disabled>
                    <v-tooltip activator="parent" location="top" open-delay="500">Previous Page</v-tooltip>
                    <fa icon="fa-solid fa-caret-left"></fa>
                  </v-btn>
                </li>
                <div v-for="page in pagination.last_page" :key="page" class="page-info">
                  <label v-if="pagination.current_page == page" color="#2d4b7a" @click.prevent="changePage(page)"
                    class="mpage">Page {{ page }} of {{ pagination.last_page }}</label>
                </div>
                <li style="list-style: none !important;">
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a"
                    v-if="pagination.current_page < pagination.last_page"
                    @click.prevent="changePage(pagination.current_page + 1)" class="ipage">
                    <v-tooltip activator="parent" location="top" open-delay="500">Next Page</v-tooltip>
                    <fa icon="fa-solid fa-caret-right"></fa>
                  </v-btn>
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else class="ipage" disabled>
                    <v-tooltip activator="parent" location="top" open-delay="500">Next Page</v-tooltip>
                    <fa icon="fa-solid fa-caret-right"></fa>
                  </v-btn>
                </li>
                <li style="list-style: none !important;" id="lastPageButton">
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a"
                    v-if="pagination.current_page == pagination.last_page" disabled class="ipage">
                    <v-tooltip activator="parent" location="top" open-delay="500">Last Page</v-tooltip>
                    <fa icon="fa-solid fa-forward-step"></fa>
                  </v-btn>
                  <v-btn variant="none" icon size="x-large" color="#2d4b7a" v-else
                    @click.prevent="changePage(pagination.last_page)" class="ipage">
                    <v-tooltip activator="parent" location="top" open-delay="500">Last Page</v-tooltip>
                    <fa icon="fa-solid fa-forward-step"></fa>
                  </v-btn>
                </li>
              </ul>
            </div>
          </div>
          <!-- pagination content -->

          <!--  -->
          <div class="sidebar">
            <router-link to="/AskQuestion" class="openBtn" @click="togglepopup()">Add Question</router-link>
            <div class="popular_creators">
              <h4>Popular Users</h4>
              <div class="row-userdisplay" v-for="userdetails in userlist" :key="userdetails.id">
                <div class="imguser">
                  <img v-if="userdetails.profile_picture != null" :src="userdetails.profile_picture"
                    id="user-profile-image" referrerpolicy="no-referrer" alt="" />
                  <img v-else v-bind:src="require('@/assets/avatar.png')" alt="" id="user-profile-image" />
                </div>
                <div class="usernamedashboard">
                  {{ userdetails.name }}
                </div>
              </div>
            </div>
            <div class="popular_tags">
              <h4>Popular Tags</h4>
              <div class="tag-1">
                <div class="his" :key="item.id" v-for="item in popularTags" @click="viewTagQuestion(item.id)">
                  <p>{{ item.tag_name }}</p>
                  <strong class="pop-color">#</strong>


                </div>
              </div>
            </div>
          </div>

        </div>
      </section>
    </div>

  </div>
</template>
<script>
import Loader from '../Loader.vue';
import { useToast,POSITION } from "vue-toastification";
import http from "../../http-common";
let moment = require("moment");
export default {
  name: "dash-board",
  components: {
    Loader
  },
  data() {
    return {
      isLoading: true,
      dataLength: 0,
      searchKey: "",
      flag: undefined,
      searchFlag: undefined,
      searchResult: {},
      searchResultTags: {},
      search: "",
      TagList: "",
      notifications: "",
      list: undefined,
      tagName: undefined,
      userlist: undefined,
      moment: moment,
      questions: undefined,
      Tagid: [],
      userName: [],
      page: 1,
      popularTags: "",
      userType: "",
      down: false,
      i: 0,
      pagination: {
        current_page: 1
      },
      sideBarnav: false,
      User: {}
    };
  },
  setup() {
    const toast = useToast();
    return { toast };
  },
  created() {
      
            this.interval = setInterval(() => {
                this.getCurrentUser();
              
            }, 3000)
        
    },
  methods: {
    clear() {
      localStorage.removeItem("TOKEN");
      localStorage.removeItem("User Details");
      localStorage.removeItem("User image");
      localStorage.removeItem("User name");
      localStorage.removeItem("email");
      localStorage.removeItem("TOKENN");
      window.location.href = "/";
    },
    // close notification
    closeMOdal() {
      let body = document.getElementById("body");
      let box = document.getElementById("box");
      body.addEventListener(
        "click",
        function () {
          box.style.height = "0px";
          box.style.opacity = 0;
          this.down = false;
        },
        false
      );
    },


    async searchQuestion() {
      this.flag = 1;
      let searchText = this.searchKey.trim();
      if (searchText == "") {
        await this.getAllQuestions();
        this.flag = 0;
      } else {
        let regex = /\\/g;
        searchText = searchText.replace(regex, "\\\\");
        searchText = encodeURIComponent(searchText);
        await http
          .get("/question/searchtext?s=" + searchText)
          .then((response) => {
            this.searchResult = response.data.data;
            this.pagination = response.data;
            if (this.searchResult.length === 0) {
              this.searchFlag = 1;
            } else {
              this.searchFlag = 0;
            }
          })
          .catch(function (error) {
            throw error;
          });
      }
    },
    
    async getCurrentUser() {
      http.get("/getuser").then((response) => {
        this.users = response.data.user;
        this.User = response.data.user;
        this.currentUserId = response.data.id;
        this.userName = response.data.user.name;
        this.userType = response.data.user.type
      }).catch((error) => {
        if (error.response.data.error_code == 5001) {
        console.log(error);
        let msg = error.response.data.message
        this.toast.error(msg, {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
              onClose: () => {
    localStorage.clear();
    window.location.reload();
  }
            });}
          
      })

    },
    async getPopulartags() {
      http
        .get("/popular-tags")
        .then((response) => {
          this.popularTags = response.data;
        })
        .catch(function (error) {
          if (error.response.status == 401) {
            localStorage.clear();
            window.location.href = '/';
          }
        });
    },

    addToTagArray(Taglistid) {
      const index = this.Tagid.findIndex((item) => {
        return Taglistid === item;
      });
      if (index === -1) {
        if (this.Tagid.length < 3) {
          this.Tagid.push(Taglistid);
        } else {
          document.getElementById("tagcheck" + Taglistid).checked = false;
        }
      } else {
        this.Tagid.splice(index, 1);
      }
    },
    // functionalities

    viewDetails(id) {
      http.post("/questionviews/" + id, {}).then(() => { });
      this.$router.push("/questionView/" + id);
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
            ev.stopPropagation();
          },
          false
        );
      } else {
        box.style.height = "510px";
        box.style.opacity = 1;
        this.down = true;
      }
    },

    navToProfile() {
      this.$router.push("/profile");
    },
    NotificationDetails(Qid) {
      http.post("/questionviews/" + Qid, {}).then(() => { });
      this.$router.push("/questionView/" + Qid);
    },
    outputTag() {
      const input = document.querySelector("#tagInput");
      const output = document.querySelector(".tags");
      const tag = `
        <span class="tag">
          <b>${input.value}</b>
           <i id="icon-close" class="fa fa-window-close"></i>
            </span
        `;
      output.innerHTML += tag;
      input.value = "";
    },

    async getAllQuestions() {
      http.get("/questions").then((response) => {
        this.list = response.data.data;
        this.dataLength = response.data.data.length;
        this.pagination = response.data;
        this.isLoading = false
      });
      this.flag = 0;

    },
    viewTagQuestion(id) {
      http.get("/questions-onTags/" + id)
        .then(response => {
          localStorage.setItem('chosenTag', response.data.tagName);
          this.$router.push({ path: "/tagquestion/" + id });
        });
    },
    deleteNotification() {
      http.put("user/notification/delete").then(() => {
        window.location.reload();
      });
    },
    scrolltoTop() {
      window.scrollTo(0, 0);
    },


    dashboard() {
      window.scrollTo(0, 0);
      window.location.reload();
    },
    reload() {
      window.location.reload();
    }
    ,
    changePage(cpage) {
      if (this.searchKey.length > 0) {
        this.flag = 1;
        let searchText = this.searchKey.trim();
        if (searchText == "") {
          this.getAllQuestions();
          this.flag = 0;
        } else {
          let regex = /\\/g;
          searchText = searchText.replace(regex, "\\\\");
          searchText = encodeURIComponent(searchText);
          http
            .get("/question/searchtext?s=" + searchText + "&page=" + cpage)
            .then((response) => {
              this.searchResult = response.data.data;
              this.pagination = response.data;
              this.scrolltoTop();
              if (this.searchResult.length === 0) {
                this.searchFlag = 1;
              } else {
                this.searchFlag = 0;
              }
            })
            .catch(function (error) {
              throw error;
            });
        }
      }
      else {
        http.get("/questions?page=" + cpage)
          .then(response => {
            this.list = response.data.data;
            this.pagination = response.data;
            this.scrolltoTop();
          });
      }
    },
  },
  async beforeMount() {
    await this.getPopulartags();
    this.flag = 0;
    await http.get("/user/get-popular").then((response) => {
      this.userlist = response.data;
    });
    await http
      .get("/user/notification")
      .then((response) => {
        this.notifications = response.data;
      })
      .catch(function (error) {
        throw error;
      });
    await this.getCurrentUser();
    await this.getAllQuestions();
  },

  clear() {
    localStorage.removeItem("TOKEN");
    localStorage.removeItem("User Details");
    localStorage.removeItem(" TOKENN")
    window.location.href = "/";
  },
};
</script>
<style scoped>
/* user-profile icon on header */
.user-Pro {
  border-radius: 50px;
  position: fixed;
  right: 36px;
  top: 5px;
  margin: 10px 30px;
  color: rgb(110, 109, 109);
  cursor: pointer;
  width: 45px;
}

.user_rep {
  border-radius: 50px;
  position: fixed;
  right: 87px;
  width: 48px !important;
  top: 4px;
  margin: 10px 30px;
  color: rgb(110, 109, 109);
  cursor: pointer;
}

.user_points {
  position: fixed;
  right: 75px;
  width: 160px !important;
  top: 20px;
  margin: 10px 30px;
  color: rgb(110, 109, 109);
  cursor: pointer;
}

.seermore1 {
  background-color: rgba(51, 51, 51, 0);
  color: #799fb4;
  width: 100%;
  align-items: center;
  text-align: center;
}

.seermore1:hover {
  background-color: rgba(255, 255, 255, 0);
}

.container-drp {
  width: 50%;
  display: inline-block;
  align-items: center;
  text-align: center;
  justify-content: center;
  margin-bottom: 4%;
}

.select-btn {
  display: flex;
  height: 50px;
  align-items: center;
  justify-content: space-between;
  padding: 0 16px;
  border-radius: 8px;
  cursor: pointer;
  background-color: #fff;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
}

.select-btn .btn-text {
  font-size: 17px;
  font-weight: 400;
  color: #333;
}

.select-btn .arrow-dwn {
  display: flex;
  height: 21px;
  width: 21px;
  color: #fff;
  font-size: 14px;
  border-radius: 50%;
  background: #6e93f7;
  align-items: center;
  justify-content: center;
  transition: 0.3s;
}

.select-btn.open .arrow-dwn {
  transform: rotate(-180deg);
}

.list-items {
  position: relative;
  height: 200px;
  overflow-y: scroll;
  margin-top: 15px;
  border-radius: 8px;
  padding: 16px;
  background-color: #fff;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
  display: none;
}

.select-btn.open~.list-items {
  display: block;
}

.list-items .item1 {
  display: flex;
  align-items: center;
  list-style: none;
  height: 50px;
  cursor: pointer;
  transition: 0.3s;
  padding: 0 15px;
  border-radius: 8px;
}

.list-items .item1:hover {
  background-color: #e7edfe;
}

.item1 .item-text {
  font-size: 16px;
  font-weight: 400;
  color: #333;
  margin-left: 3%;
}

.restdata {
  margin-top: 10px !important;
}

.item1 .checkbox {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 16px;
  width: 16px;
  border-radius: 4px;
  margin-right: 12px;
  border: 1.5px solid #c0c0c0;
  transition: all 0.3s ease-in-out;
}

.item.checked .checkbox {
  background-color: #4070f4;
  border-color: #4070f4;
}

.checkbox .check-icon {
  color: #fff;
  font-size: 11px;
  transform: scale(0);
  transition: all 0.2s ease-in-out;
}

.item.checked .check-icon {
  transform: scale(1);
}

.his {
  cursor: pointer;
}

.his p {
  cursor: pointer;
}

.check {
  color: white;
}

#tagcheck {
  cursor: pointer;
}

/*user image*/
.imguser img {
  border-radius: 50%;
  height: 50px;
  width: 50px;
}

.row-userdisplay {
  display: flex;
    /* flex-wrap: wrap; */
  justify-content: left;
  padding-top: 25px;
}

.usernamedashboard {
  margin-left: 5%;
  margin-top: 4%;
  font-weight: bold;
}

/*cardbox*/
#card-co {
  width: fit-content;
  margin-bottom: 5px;
}

.fa-search:before {
  content: "\f002";
  margin-left: 47%;
}

/*notification*/
.notifi-box {
  overflow-y: scroll;
  transition: all 0.6s;
}

/* pagination */
#pagination_width {
  height: auto;
  width: 95%;
  background-color: rgba(255, 255, 255, 0);
}

.search-container-dash {
  display: flex;
  flex-direction: row;
  width: 100%;
  margin-left: 43px;
}

.ds {
  margin-top: -30.4px;
  margin-left: 218%;
}

.text-center {
  margin-top: 10px;
}

.see-more {
  margin-top: 5px;
}

.card-detail {
  word-break: break-word !important;
}

.popular_creators {
  padding: 20px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 20px 30px rgb(0 0 0 / 10%);
  height: 365px;
  overflow: hidden;
}

html {
  height: 100%;
}

body {
  font-family: "Lato", sans-serif;
  color: #888;
  margin: 0;
}

#main {
  display: table;
  width: 100%;
  height: 100vh;
  text-align: center;
}

.fof {
  display: table-cell;
  vertical-align: middle;
}

.nodataD {
  color: black;
  margin-left: 73%;
  width: 100%;
  /* margin-top: 11%; */
}

.clearAllnotifications {
  margin-left: 40%;
  color: blue;
  cursor: pointer;
}

#notificationModalDash {
  height: 100%;
  /* width: 100%; */
  margin-left: 37%;
  z-index: 999;
}

#to-top {
  background: white;
  position: fixed;
  bottom: 16px;
  right: 32px;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: none;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  color: #1f1f1f;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.4s;
  padding-left: 9px;
}

.colorNotification0 {
  color: rgb(116, 116, 116) !important;
}

.colorNotification1 {
  color: rgb(0, 0, 0) !important;
}

.Forflex {
  display: flex;
}
div.tagDIvQ+div.tagDIvQ{
    padding-left: 10px;
}
.TagDisplay {
  /* color: #4aa4ff; */
}

b {
  font-weight: bolder;
}

.formSearch {
  width: 100%;
}

.main-top .search {
  padding: 6px;
  font-size: 17px;
  border: none;
  width: 69.2%;
}

@keyframes type {
  from {
    box-shadow: inset -3px 0px 0px #888;
  }

  to {
    box-shadow: inset -3px 0px 0px transparent;
  }
}


.main-body {
  margin-top: 0px
}
</style>