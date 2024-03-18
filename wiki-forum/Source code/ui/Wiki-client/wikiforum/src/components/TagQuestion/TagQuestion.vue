<template>

  <div id="body" @click="closeMOdal()">
    <div v-if="isLoading">
      <Loader />
    </div>
    <div v-else class="container">
      <section class="main">
        <!-- <div class="search-container-tq" v-if="arrayLength != 0"  ref="topScrollNavDiv"> -->
          <form action="" v-on:keyup="searchQuestion()" @submit.prevent="searchQuestion()"  class="formSearch">
          <div class="inputDiv"  ref="topScrollNavDiv"  v-if="arrayLength != 0">
            <v-responsive id="searchQuestions" class="mx-auto" max-width="344" >
              <v-text-field placeholder="Search here" density="compact" variant="solo" @input="searchQuestion()"
                prepend-inner-icon="mdi-magnify" v-model="searchKey"></v-text-field>
            </v-responsive>
            </div>
          </form>
        <!-- </div> -->
        <div class="main-body">
          <div class="main-content" v-if="flag == 1">
            <div v-if="searchFlag == 1" class="null-result">
              <h1 class="nodataTQ">No Data</h1>
            </div>
            <div v-else>
              <div class="cardbox" :key="item.id" v-for="item in searchResult">
                <div class="card-detail">
                  <h2 @click="viewDetails(item.id)" style="white-space: pre-wrap">
                    {{ item.title }}
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
                        Posted on {{ moment(item.created_at).calendar() }} by
                        {{ item.name }}
                      </p>
                      <div class="Forflex" id="tagsforquestion">
                        <div v-for="tag in item.tags" :key="tag.id">
                          <p class="TagDisplay">
                            <strong class="pop-color">#</strong>{{ tag.tag_name }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr />
          <div class="main-content" v-if="flag == 0">
            <div v-if="arrayLength == 0">
              <h1 class="nodataTQE">No Data</h1>
            </div>
            <div v-else class="cardbox" :key="item.id" v-for="item in list">
              <div class="card-detail">
                <h2 @click="viewDetails(item.id)" style="white-space: pre-wrap">
                  {{ item.title }}
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
                      Posted on {{ moment(item.created_at).calendar() }} by
                      {{ item.name }}
                    </p>
                    <div class="Forflex" id="tagsforquestion">
                      <div v-for="tag in item.tags" :key="tag.id">
                        <p class="TagDisplay">
                          <strong class="pop-color">#</strong>{{ tag.tag_name }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="dashboard-footer" v-if="searchFlag != 1 && arrayLength != 0">
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
      </section>
    </div>

  </div>
</template>
    
<script>
import http from "../../http-common";
import Loader from '../Loader.vue';
let moment = require("moment");
export default {
  // components: { Header,Sidebar },
  name: "TagQuestion",
  components: {
    Loader
  },
  data() {
    return {
      isLoading: true,
      flag: undefined,
      searchKey: "",
      searchFlag: undefined,
      userlist: undefined,
      searchResult: {},
      searchResultTags: {},
      popularTags: {},
      search: "",
      TagList: "",
      list: undefined,
      tagName: undefined,
      moment: moment,
      questions: undefined,
      Tagid: [],
      userName: [],
      arrayLength: undefined,
      i: 0,
      tag: "",
      tooltipTag: '',
      popularUsers: {},
      sideBarnav: false,
      pagination: {
        current_page: 1
      },
      // User: {}
    };
  },
  methods: {
    clear() {
      localStorage.removeItem("TOKEN");
      localStorage.removeItem("User Details");
      localStorage.removeItem("User image");
      localStorage.removeItem("User name");
      localStorage.removeItem("email");
      localStorage.removeItem("questionId");
      window.location.href = "/";
    },

    searchQuestion() {
      this.flag = 1;

      let text = this.searchKey.trim();
      let regex = /\\/g;
      text = text.replace(regex, "\\\\");
      if (text == "") {
        this.getAllQuestions();
        this.flag = 0;
      } else {
        text = encodeURIComponent(text);
        let id = this.getTagId();
        http
          .get("/questions-onTags/search/" + id + "?data=" + text)
          .then((response) => {
            console.log(response);
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
    scrolltoTop() {
      this.$refs["topScrollNavDiv"].scrollIntoView({ behavior: "auto", block: 'center' })
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
    viewDetails(id) {
      http.post("/questionviews/" + id, {}).then(() => { });
      this.$router.push("/questionView/" + id);
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
    navToProfile() {
      this.$router.push('/profile')
    },
    // reshma
    async getAllQuestions() {
      let tagQuestionId = this.getTagId();
      await http.get("/questions-onTags/" + tagQuestionId).then((response) => {
        if (response.data.question.data) {
          this.list = response.data.question.data;
          this.pagination = response.data.question;
          this.arrayLength = response.data.question.data.length;
          this.tag = response.data.tagName;
        }
        else{
          this.arrayLength=0;
        }
        this.flag = 0;
      });
    },
    getTagId() {
      return this.$route.params.tagId;
    },
    viewTagQuestion(id) {
      console.log("hiiii", id);
      http.get("/questions-onTags/" + id).then((response) => {
        this.list = response.data;
        this.arrayLength = response.data.length;
      });
      this.flag = 0;
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
    changePage(cpage) {
      if (this.searchKey.length > 0) {
        this.flag = 1;
        let text = this.searchKey.trim();
        let regex = /\\/g;
        text = text.replace(regex, "\\\\");
        if (text == "") {
          this.getAllQuestions();
          this.flag = 0;
        } else {
          text = encodeURIComponent(text);
          let id = this.getTagId();
          http
            .get("/questions-onTags/search/" + id + "?data=" + text + "&page=" + cpage)
            .then((response) => {
              console.log(response);
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
        let tagQuestionId = this.getTagId();
        http.get("/questions-onTags/" + tagQuestionId + "?page=" + cpage)
          .then(response => {
            this.list = response.data.question.data;
            this.pagination = response.data.question;
            this.scrolltoTop();
          });
      }
    },
  },
  async beforeMount() {
    this.flag = 0;
    this.searchKey = "";
    http.get("/user/get-popular").then((response) => {
      console.log('-----------------', response.data.users);
      this.popularUsers = response.data;
      http.get("/tags").then((response) => {
        this.searchResult = response.data;
        http.get("/getAllUsers").then((response) => {
          this.userlist = response.data.users;
          http
            .get("/popular-tags")
            .then((response) => {
              this.popularTags = response.data;
            })
            .catch(function (error) {
              throw error;
            });
        });
      });
    });
    await this.getAllQuestions();
    this.isLoading = false;
  },

  clear() {
    localStorage.removeItem("TOKEN");
    localStorage.removeItem("User Details");
    window.location.href = "/";
  },
};
</script>
<style scoped>
.card-detail{
  word-break: break-word !important;
}
.non-result {
  margin-left: 58%;
  margin-top: 10%;
  font-size: 500%;
}

.non-result2 {
  color: #4070f4;
  font-size: 300%;
  margin-left: 54%;
  font-weight: bolder;
}

.non-re {
  margin-left: 38%;
  margin-top: 10%;
  font-size: 300%;
}

.non-re2 {
  color: #4070f4;
  font-size: 200%;
  margin-left: 34%;
  font-weight: bolder;
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

.fa-search:before {
  content: "\f002";
  margin-left: 47%;
}

.nodataTQE {
  color: #000;
margin-top: 80px;
  font-size: 50px;

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

.list-items .item {
  display: flex;
  align-items: center;
  list-style: none;
  height: 50px;
  cursor: pointer;
  transition: 0.3s;
  padding: 0 15px;
  border-radius: 8px;
}

.list-items .item:hover {
  background-color: #e7edfe;
}

.item .item-text {
  font-size: 16px;
  font-weight: 400;
  color: #333;
  margin-left: 3%;
}

.item .checkbox {
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

.his {
  cursor: pointer;
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

.check {
  color: white;
}

#tagcheck {
  cursor: pointer;
}

/* .pageHaedings {
  width: 21%;
} */

.nodataTQ {
  color: #000;
  font-size: 50px ;
  margin-top: 18px;
    margin-left: 0px;
  
}

.clearAllnotifications {
  margin-left: 40%;
  color: blue !important;
  cursor: pointer;
}

.TagnameLogo {
  color: #507082;
}

.colorNotification0 {
  color: rgb(116, 116, 116) !important;
}

.colorNotification1 {
  color: rgb(0, 0, 0) !important;
}

.search-container-tq {
  display: flex;
  flex-direction: row;
  width: 100%;
  /* margin-left: 5.9%; */
}

.tq-icon {
  margin-top: -30px;
  margin-left: 40.5%;
}

#forwidthTagQuestion {
  width: max-content;
}

.Forflex {
  display: flex;
}

.formDiv {
  width: 88%;
}
.fontColor{
  color:#005176;
}
</style>