<template>
  <div id="body" @click="closeMOdal()">
    <div v-if="isLoading">
      <Loader />
    </div>
    <div v-else class="container">


      <section class="main" >
        <div class="search-container-dash" v-if="arrayLength>0" ref="topScrollNavDiv">
          <form action="" v-on:keyup="searchQuestion()" @submit.prevent="searchQuestion()" class="formSearch-qdetails">
            <div class="inputDiv">
              <v-responsive id="searchQuestions" class="mx-auto" max-width="344" >
                <v-text-field placeholder="Search here" density="compact" variant="solo" @input="searchQuestion()"
                  prepend-inner-icon="mdi-magnify" v-model="searchKey"></v-text-field>
              </v-responsive>
            </div>
          </form>
        </div>

        <div class="main-body">
          <div v-if="flag == 0" class="noQuestionsPostedDiv">
            <div v-if="resultLength == 0">
              <h1 class="nodatauq">No Data</h1>
            </div>
          </div>
          <div class="main-content" v-if="flag == 1">
            <div v-if="searchFlag == 1">
              <h1 class="nodatauq1">No Data</h1>
            </div>
            <div v-else>
              <div class="cardbox" :key="item.id" v-for="item in searchResult">
                <div class="card-detail">
                  <h2 @click="viewDetails(item.id)" style="white-space: pre-wrap" v-if="item.status==1">
                    {{ item.title}}
                  </h2>
                  <h2 @click="viewDetails(item.id)" style="white-space: pre-wrap" v-else-if="item.status==0" class="unavailable-question">
                    {{ item.title}}<span class="unavailable-question"> [Deleted]</span>
                  </h2>
                  <h2 @click="viewDetails(item.id)" style="white-space: pre-wrap" v-else class="unavailable-question">
                    {{ item.title}}<span class="unavailable-question"> [Closed!]</span>
                  </h2>
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
                        Posted on {{ moment(item.created_at).calendar() }}
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
            <div class="dashboard-footer" id="dashboard-page" v-if="searchFlag != 1 && arrayLength != 0">
          <ul class="dashboard-pagination">
            <li style="list-style: none !important;"  id="lastPageButton">
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
            <li style="list-style: none !important;"  id="lastPageButton">
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
                <h2 @click="viewDetails(item.id)" style="white-space: pre-wrap" v-if="item.status==1">
                  {{ item.title}}
                </h2>
                <h2 @click="viewDetails(item.id)" style="white-space: pre-wrap" v-else-if="item.status==0" class="unavailable-question">
                  {{ item.title}}<span class="unavailable-question"> [Deleted]</span>
                </h2>
                <h2 @click="viewDetails(item.id)" style="white-space: pre-wrap" v-else class="unavailable-question">
                  {{ item.title}}<span class="unavailable-question"> [Closed]</span>
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
                        Posted on {{ moment(item.created_at).calendar() }}
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
            <div class="dashboard-footer" id="dashboard-page" v-if="searchFlag != 1 && arrayLength != 0">
          <ul class="dashboard-pagination">
            <li style="list-style: none !important;"  id="lastPageButton">
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
            <li style="list-style: none !important;"  id="lastPageButton">
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

          <div class="sidebar">
            <router-link to="/AskQuestion" class="openBtn" @click="togglepopup()">Add Question</router-link>
            <div class="popular_creators">
              <h4>Popular Users</h4>
              <div class="row-userdisplay" v-for="userdetails in popularUsers" :key="userdetails.id">
                <div class="imguser">
                  <img v-if="userdetails.profile_picture != null" :src="userdetails.profile_picture"
                    referrerpolicy="no-referrer" alt="" id="user-profile-image" />
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
import http from "../../../http-common";
import { useToast } from "vue-toastification";
import Loader from '../../Loader.vue';
let moment = require("moment");
window.addEventListener("scroll", () => {
  document.getElementById("to-top").style.display = "block";
});
export default {
  components: {
    Loader
  },
  data() {
    return {
      isLoading: true,
      User: {},
      userType: "",
      searchKey: "",
      flag: undefined,
      userlist: undefined,
      searchFlag: undefined,
      notifications: "",
      searchResult: {},
      search: "",
      TagList: "",
      down: false,
      list: undefined,
      moment: moment,
      Tagid: [],
      popularUsers: "",
      popularTags: "",
      resultLength: undefined,
      sideBarnav: false,
      arrayLength: undefined,
      pagination: {
        current_page: 1
      },
    };
  },
  setup() {
    const toast = useToast();
    return { toast };
  },
  methods: {
    async getUser() {
      http.get("/getuser").then((response) => {
        this.User = response.data.user;
        this.userType = response.data.user.type;
      });
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
    searchQuestion() {
      this.flag = 1;
      let text = this.searchKey.trim();
      if (text == "") {
        this.getQuestions();
        this.flag = 0;
      } else {
        let regex = /\\/g;
        text = text.replace(regex, "\\\\");
        text = encodeURIComponent(text);
        http
          .get("/questions-onUser/search?data=" + text)
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
    async getPopulartags() {
      http
        .get("/popular-tags")
        .then((response) => {
          this.popularTags = response.data;
        })
        .catch(function (error) {
          throw error;
        });
    },
    // functionalities

    viewDetails(id) {
      http.post("/questionviews/" + id, {}).then(() => { });
      this.$router.push("/QView/" + id);
    },
    navToProfile() {
      this.$router.push("/profile");
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
    async getQuestions() {
      await http.get("/questionProfile").then((response) => {
        this.resultLength = response.data.question.data.length;
        this.arrayLength = response.data.question.data.length;
        console.log('PPPPPPPPP',this.arrayLength);
        this.list = response.data.question.data;
        this.pagination = response.data.question
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
    // pagination- change page code
    changePage(cpage) {
      if (this.searchKey.length > 0) {
        this.flag = 1;
        let searchText = this.searchKey.trim();
        if (searchText == "") {
          this.getQuestions();
          this.flag = 0;
        } else {
          let regex = /\\/g;
          searchText = searchText.replace(regex, "\\\\");
          searchText = encodeURIComponent(searchText);
          http
            .get("/questions-onUser/search?data=" + searchText + "&page=" + cpage)
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
        http.get("/questionProfile?page=" + cpage)
          .then(response => {
            this.list = response.data.question.data;
            this.pagination = response.data.question;
            this.scrolltoTop();
          });
      }
    },
    scrolltoTop() {
      this.$refs["topScrollNavDiv"].scrollIntoView({ behavior: "auto", block: 'center' })
    },
  },

  async beforeMount() {
    await this.getUser();
    await this.getQuestions();
    await this.getPopulartags();
    http.get("/user/get-popular").then((response) => {
      this.popularUsers = response.data;
      this.flag = 0;
      http.get("/getAllUsers").then((response) => {
        this.userlist = response.data;
        http
          .get("/user/notification")
          .then((response) => {
            this.notifications = response.data;
            this.isLoading=false;
          })
          .catch(function (error) {
            throw error;
          });
      });
    });


  },

  clear() {
    localStorage.removeItem("TOKEN");
    localStorage.removeItem("User Details");
    window.location.href = "/";
  },
};
</script>
<style scoped>
@import url("Questiondetails.css");

#card-co {
  width: fit-content;
   margin-bottom: 2px;
}
</style>