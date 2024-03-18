<template>
  <div id="body">
    <div class="container">
      <nav id="sideBarNav">
        <ul class="sidebarUl">
          <router-link to="/dashboard" class="logo">
            <li id="logo-li">
              <img :src="require('@/assets/wiki.png')" alt="" class="wf-logo" />
              <span>Wiki Forum</span>
            </li>
          </router-link>
          <li @click="OpenClose()">
            <router-link to="">
              <fa class="fas" icon="bars" />
              <span class="navitem"> </span>
            </router-link>
          </li>
          <li>
            <router-link to="/dashboard">
              <fa class="fas" icon="house" />
              <span class="navitem">Dashboard</span>
            </router-link>
          </li>
          <li>
            <router-link to="/profile">
              <fa class="fas" icon="user" />
              <span class="navitem">Profile</span>
            </router-link>
          </li>
          <router-link to="/tagList">
            <li>
              <fa class="fas" icon="tags" />
              <span class="navitem">Tags</span>
            </li>
          </router-link>
          <li>
            <router-link to="/userlist">
              <fa class="fas" icon="users" />
              <span class="navitem">Users</span>
            </router-link>
          </li>

          <li>
            <router-link to="/AskQuestion">
              <fa class="fas" icon="question-circle" />
              <span class="navitem">Ask Question</span>
            </router-link>
          </li>
          <li @click="clear()">
            <a href="#" class="logout">
              <fa class="fas" icon="sign-out-alt" />
              <span class="navitem">Logout</span>
            </a>
          </li>
        </ul>
      </nav>
      <section class="main">
        <div class="main-top">
          <h1>Trending</h1>
          <div class="search-container">
            <form
              action=""
              v-on:keyup="searchQuestion()"
              @submit.prevent="searchQuestion()"
            >
              <input
                type="text"
                v-model="searchKey"
                placeholder="Search.."
                name="search"
                class="search"
              />
              <button type="submit"><em class="fa fa-search"></em></button>
            </form>
          </div>
          <fa id="i" icon="user-cog" />
        </div>
        <div class="main-body">
          <div class="main-content" v-if="flag == 1">
            <div v-if="searchFlag == 1" class="null-result">
              <p>Ooops..No result available!!</p>
            </div>
            <div v-else>
              <div class="cardbox" :key="item.id" v-for="item in searchResult">
                <div class="card-detail">
                  <h2 id="card-co" @click="viewDetails(item.id)">
                    {{ item.title }}
                  </h2>
                  <p>{{ moment(item.created_at).calendar() }}</p>
                  <div class="user" id="card-co">
                    <div class="user-detail">
                      <h4>
                        <i class="fa fa-eye" aria-hidden="true"></i
                        >{{ item.views }} views
                      </h4>
                      <h4>
                        <em class="far fa-comment-alt"></em
                        >{{ item.answer_count }} Answers
                      </h4>
                      <p class="date">
                        {{ moment(item.created_at).calendar() }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr />
          <div class="main-content" v-if="flag == 0">
            <div class="cardbox" :key="item.id" v-for="item in list">
              <div class="card-detail">
                <h2 id="card-co" @click="viewDetails(item.id)">
                  {{ item.title }}
                </h2>
                <div class="user" id="card-co">
                  <div class="user-detail">
                    <h4>
                      <i class="fa fa-eye" aria-hidden="true"></i
                      >{{ item.views }} views
                    </h4>
                    <h4>
                      <em class="far fa-comment-alt"></em
                      >{{ item.answer_count }} Answers
                    </h4>
                    <p class="date">
                      Posted on {{ moment(item.created_at).calendar() }} by
                      {{ item.name }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="popup" id="popup-1">
            <div class="overlay">
              <div class="content">
                <div class="close-btn" @click="togglepopup()">&times;</div>
                <h1 id="title">Add your question</h1>

                <form action="" @submit.prevent="QuestionDetails()">
                  <input
                    class="question"
                    id="textExample"
                    type="text"
                    name="Qtitle"
                    v-model="Qtitle"
                    placeholder="Question"
                  />
                  <p>
                    <textarea
                      id="textExample"
                      class="desc"
                      rows="10"
                      placeholder="Description"
                      v-model="Qdesc"
                    ></textarea>
                  </p>
                  <br />
                  <div id="tg">
                    <div class="tag-input">
                      <h2>Tags</h2>
                      <div class="container-drp">
                        <div class="select-btn" @click="getAlltags()">
                          <span class="btn-text">Select Tags</span>
                          <span class="arrow-dwn">
                            <fa class="tag" icon="tag" />
                          </span>
                        </div>

                        <ul class="list-items">
                          <li class="item" :key="tag.id" v-for="tag in TagList">
                            <div @click="addToTagArray(tag.id)">
                              <input type="checkbox" name="" id="" />
                              <span class="item-text">
                                {{ tag.tag_name }}
                              </span>
                            </div>
                          </li>
                        </ul>
                      </div>

                      <p>
                        <button type="submit" id="type">Submit</button>
                      </p>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="sidebar">
            <a class="openBtn" href="#" @click="togglepopup()">Add Questions</a>
            <div class="popular_creators">
              <h4>Popular Users</h4>
              <div class="row">
                <div class="cr"></div>
              </div>
            </div>
            <div class="popular_tags">
              <h4>Popular Tags</h4>
              <div class="tag-1">
                <div class="his">
                  <p>javascript</p>
                  <fa icon="times" id="f" />
                </div>
                <div class="his">
                  <p>Web Development</p>
                  <fa icon="times" id="f" />
                </div>
                <div class="his">
                  <p>IOS Development</p>
                  <fa icon="times" id="f" />
                </div>
                <div class="his">
                  <p>Python Django</p>
                  <fa icon="times" id="f" />
                </div>
                <div class="his">
                  <p>React Framework</p>
                  <fa icon="times" id="f" />
                </div>
                <div class="his">
                  <p>Angular Framework</p>
                  <fa icon="times" id="f" />
                </div>
                <div class="see-more">
                  <button>See More</button>
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
import http from "../../http-common";

import { useToast, POSITION } from "vue-toastification";
let moment = require("moment");

export default {
  data() {
    return {
      flag: undefined,
      searchFlag: undefined,
      searchResult: {},
      Qtitle: "",
      Qdesc: "",
      search: "",
      TagList: "",
      list: undefined,
      moment: moment,
      Tagid: [],
      sideBarnav: false,
    };
  },
  setup() {
    const toast = useToast();
    return { toast };
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
    async QuestionDetails() {
      console.log(this.Tagid);
      const bodyParameters = {
        title: this.Qtitle,
        description: this.Qdesc,
        tag_id: JSON.parse(JSON.stringify(this.Tagid)),
      };
      http
        .post("/question", bodyParameters)
        .then(console.log)
        .catch((error) => {
          let msg = "Unable to process !!" + error.response.data.message;
          this.toast.error(msg, {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
        });
    },
    searchQuestion() {
      this.flag = 1;
      console.log(this.searchKey, "==============>>");

      http
        .get("/question/searchtext?s=" + this.searchKey)
        .then((response) => {
          console.log("Response====>>>>>>>>>>>", response.data);
          this.searchResult = response.data;
          if (this.searchResult.length === 0) {
            this.searchFlag = 1;
          } else {
            this.searchFlag = 0;
          }
          console.log("+++++++++", this.searchResult);
        })
        .catch(function (error) {
          console.log(error);
          throw error;
        });
    },
    getAlltags() {
      http
        .get("/tags")
        .then((response) => {
          console.log("Response====>>>>>>>>>>>", response.data);
          this.TagList = response.data;
          console.log(this.TagList);
        })
        .catch(function (error) {
          console.log(error);
          throw error;
        });
    },
    addToTagArray(Taglistid) {
      this.Tagid.push(Taglistid);
    },
    // functionalities
    togglepopup() {
      document.getElementById("popup-1").classList.toggle("active");
      this.rand();
    },
    viewDetails(id) {
      console.log(id);
      http.post("/questionviews/" + id, {}).then(() => {
        console.log("Helloooooo");
      });
      console.log(id);
      localStorage.setItem("questionId", id);
      this.$router.push({ name: "QuestionView" });
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
    rand() {
      const selectBtn = document.querySelector(".select-btn"),
        items = document.querySelectorAll(".item");
      if (selectBtn !== null) {
        selectBtn.addEventListener("click", () => {
          selectBtn.classList.toggle("open");
        });

        items.forEach((item) => {
          item.addEventListener("click", () => {
            item.classList.toggle("checked");

            let checked = document.querySelectorAll(".checked"),
              btnText = document.querySelector(".btn-text");

            if (checked && checked.length > 0) {
              btnText.innerText = `${checked.length} Selected`;
            } else {
              btnText.innerText = "Select Language";
            }
          });
        });
      }
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
  },
  mounted() {
    http.get("/my-questions").then((response) => {
      console.log("Helloooooo");
      console.log(response.data);
      this.list = response.data;
    });
    this.flag = 0;
  },

  clear() {
    localStorage.removeItem("TOKEN");
    localStorage.removeItem("User Details");
    window.location.href = "/";
  },
};
</script>
