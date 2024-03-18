<template>
  <div id="body">
    <div class="container">
      <nav>
        <ul class="sidebarUl">
          <router-link to="/dashboard" class="logo">
            <li id="logo-li">
              <img :src="require('@/assets/wiki.png')" alt="" class="wf-logo" />
              <span>Dashboard</span>
            </li>
          </router-link>
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
            <a href="#">
              <fa class="fas" icon="question-circle" />
              <span class="navitem">Help</span>
            </a>
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
      <Sidebar />
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
          <div class="icon" @click="toggleNotifi()">
            <a href="#" class="notification" id="i">
              <fa id="i" icon="bell"></fa>
              <span class="badge">3</span>
            </a>
          </div>
          <div class="notifi-box" id="box">
            <h2>Notifications <span>17</span></h2>
            <div class="notifi-item">
              <img src="img/avatar1.png" alt="img" />
              <div class="text">
                <h4>Elias Abdurrahman</h4>
                <p>@lorem ipsum dolor sit amet</p>
              </div>
            </div>

            <div class="notifi-item">
              <img src="img/avatar2.png" alt="img" />
              <div class="text">
                <h4>John Doe</h4>
                <p>@lorem ipsum dolor sit amet</p>
              </div>
            </div>

            <div class="notifi-item">
              <img src="img/avatar3.png" alt="img" />
              <div class="text">
                <h4>Emad Ali</h4>
                <p>@lorem ipsum dolor sit amet</p>
              </div>
            </div>

            <div class="notifi-item">
              <img src="img/avatar4.png" alt="img" />
              <div class="text">
                <h4>Ekram Abu</h4>
                <p>@lorem ipsum dolor sit amet</p>
              </div>
            </div>

            <div class="notifi-item">
              <img src="img/avatar5.png" alt="img" />
              <div class="text">
                <h4>Jane Doe</h4>
                <p>@lorem ipsum dolor sit amet</p>
              </div>
            </div>
          </div>
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
                        <em class="fa fa-eye" aria-hidden="true"></em
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
                      <em class="fa fa-eye" aria-hidden="true"></em
                      >{{ item.views }} views
                    </h4>
                    <h4>
                      <em class="far fa-comment-alt"></em
                      >{{ item.answer_count }} Answers
                    </h4>
                    <p class="date">
                      Posted on {{ moment(item.created_at).calendar() }}
                    </p>
                  </div>
                </div>
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
                <div class="his" :key="item.id" v-for="item in TagList">
                  <p>{{ item.tag_name }}</p>
                  <strong class="pop-color">#</strong>


                </div>

                <div class="see-more">
                  <router-link to="/tagList" class="seermore1">
                    <button>See More</button>
                  </router-link>
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

let moment = require("moment");

export default {
  name: "dash-board",

  data() {
    return {
      flag: undefined,
      searchFlag: undefined,
      searchResult: {},
      searchResultTags: {},
      Qtitle: "",

      search: "",
      TagList: "",
      list: undefined,
      tagName: undefined,
      moment: moment,
      questions: undefined,
      Tagid: [],
      userName: [],

      i: 0,
    };
  },
  mounted() {
    this.getAlltags();
  },
  methods: {
    async QuestionDetails() {
      const bodyParameters = {
        title: this.Qtitle,
        description: this.Qdesc,
        tag_id: JSON.parse(JSON.stringify(this.Tagid)),
      };
      http
        .post("/question", bodyParameters)
        .then(console.log)
        .catch(console.log);
    },

    // Search tags
    search_tag() {
      if (document.getElementById("searchbar") !== null) {
        let input = document.getElementById("searchbar").value;

        input = input.toLowerCase();
        let x = document.getElementsByClassName("item");

        for (const element of x) {
          if (!element.innerHTML.toLowerCase().includes(input)) {
            element.style.display = "none";
          } else {
            element.style.display = "list-item";
          }
        }
      }
    },
    searchQuestion() {
      this.flag = 1;
      if (this.searchKey === "") {
        this.getAllQuestions();
      }
      http
        .get("/question/searchtext?s=" + this.searchKey)
        .then((response) => {
          this.searchResult = response.data;
          if (this.searchResult.length === 0) {
            this.searchFlag = 1;
          } else {
            this.searchFlag = 0;
          }
        })
        .catch(function (error) {
          throw error;
        });
    },
    getAlltags() {
      http
        .get("/tags")
        .then((response) => {
          this.TagList = response.data;
        })
        .catch(function (error) {
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
      http.post("/questionviews/" + id, {}).then(() => {});

      localStorage.setItem("questionId", id);
      this.$router.push({ name: "QuestionView" });
    },
    toggleNotifi() {
      let box = document.getElementById("box");
      if (this.down) {
        box.style.height = "0px";
        box.style.opacity = 0;
        this.down = false;
      } else {
        box.style.height = "510px";
        box.style.opacity = 1;
        this.down = true;
      }
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
    // reshma
    getAllQuestions() {
      http.get("/questionProfile").then((response) => {
        console.log(response.data);
        this.list = response.data.question;
      });
      this.flag = 0;
    },
  },
  beforeMount() {
    this.flag = 0;
    http.get("/tags").then((response) => {
      console.log(response.data);
      this.searchResult = response.data;
    });
    this.getAllQuestions();
  },

  clear() {
    localStorage.removeItem("TOKEN");
    localStorage.removeItem("User Details");
    window.location.href = "/";
  },
};
</script>
  <style scoped>
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

.select-btn.open ~ .list-items {
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
</style>