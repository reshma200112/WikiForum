
<template>
  <div id="body" @click="closeMOdal()">
    <div v-if="isLoading">
      <Loader />
    </div>
    <div v-else class="container">
      <section class="main">
        <div class="search-container-Tag">
          <form action="" v-on:keyup="searchTag()" @submit.prevent="searchTag()" class="formSearch">
            <div class="inputDiv">
              <v-responsive id="searchQuestions" class="mx-auto" max-width="344" >
                <v-text-field density="compact" variant="solo" placeholder="Search here" @input="searchTag()"
                  prepend-inner-icon="mdi-magnify" v-model="searchKey"></v-text-field>
              </v-responsive>
            </div>
          </form>
          <div class="addTagDiv">
          <button class="btn-add" color="primary" @click="openModal()">
            Add new tag&nbsp;<fa icon="plus"></fa>
          </button>
          </div>
        </div>
<div class="tag-list-main-body">
        <div class="taglistmain-body" v-if="flag == 1">
          <div v-if="searchFlag == 1" class="null-result">
            <div class="fof">
              <h1 class="nodataTL">No Data</h1>
            </div>
          </div>
          <div v-else>
            <div class="flex">
              <div class="row-tag">
                <div class="column-tag" :key="item.id" v-for="item in searchResult">
                  <div class="card-tag">
                    <div class="abcd"></div>
                    <h3 @click="viewTagQuestion(item.id)" id="TagnameSize">
                    {{ showTag(item.tag_name)}}  <v-tooltip activator="parent" location="bottom" id="taglist-tooltip" v-if="item.tag_name.length>12"
                >{{ item.tag_name }}</v-tooltip
              >
                    </h3>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="taglistmain-body" v-if="flag == 0">
          <div class="flex">
            <div class="row-tag">
              <div class="column-tag" :key="item.id" v-for="item in searchResult">
                <div class="card-tag">
                  <div class="tagname-div">
                    <h3 @click="viewTagQuestion(item.id)" id="TagnameSize">
                    {{ showTag(item.tag_name)}}  <v-tooltip activator="parent" id="taglist-tooltip" location="bottom" v-if="item.tag_name.length>12"
                >{{ item.tag_name }}</v-tooltip
              >
                    </h3>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </section>
    </div>

    <div class="ft-modal" id="ft-demo-modal">
      <div class="ft-modal-content">
        <div class="ft-modal-body">
          <form action="" @submit.prevent="addTag()" ref="tagForm">
            <label for="tagName">Tag name<small class="manstar-p">*</small></label>

            <v-text-field placeholder="Type tag name here.." v-model.trim="tagName" :counter="20"
              @blur="v$.tagName.$touch()" @keyup="v$.tagName.$touch()" v-bind:class="{
                error: v$.tagName.$error,
                valid: v$.tagName.$dirty && !v$.tagName.$invalid,
              }" />
            <span class="error-show" v-for="error in v$.tagName.$errors" :key="error.$uid">{{
              error.$message
            }}</span>
            <hr />

            <div class="ft-modal-footer">
              <v-btn color="blue-darken-1" variant="text" class="btn-tag-close" @click="modalClose">
                Close </v-btn>&nbsp;&nbsp;
              <div v-if="tagName == ''">
                <v-btn color="blue-darken-1" variant="text" type="submit" class="btn-tag" disabled>
                  Submit
                </v-btn>
              </div>
              <div v-else>
                <v-btn color="blue-darken-1" variant="text" id="btnAddTag" type="submit" class="btn-tag">
                  Submit
                </v-btn>
              </div>
            </div>
          </form>
          <hr />
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { useToast, POSITION } from "vue-toastification";
import Loader from '../Loader.vue';
import { useVuelidate } from "@vuelidate/core";
import { required, maxLength, helpers } from "@vuelidate/validators";

import http from "../../http-common";
export default {
  validations() {
    return {
      tagName: {
        required: helpers.withMessage("Tag name is required", required),
        maxLength: maxLength(20),
      },
    };
  },
  components: {
    Loader
  },
  data() {
    return {
      isLoading: true,
      userType: "",
      v$: useVuelidate(),
      tagRules: [
        (v) => !!v || "Tag is required",
        (v) => v.length <= 20 || "Tag must be less than 20 characters",
      ],
      list: undefined,
      flag: 0, //flag to indicate whether search is active
      searchKey: "",
      searchFlag: undefined,
      searchResult: {},
      tagName: "",
      notifications: "",
      User: {}
    };
  },
  setup() {
    const toast = useToast();
    return { toast };
  },

  async created() {
    await this.getAllTags();
    http
      .get("/user/notification")
      .then((response) => {
        this.notifications = response.data;
      })
      .catch(function (error) {
        throw error;
      });
    await this.getUser();
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
    searchTag() {
      this.flag = 1;
      let searchText = this.searchKey.trim();
      if (searchText == "") {
        this.getAllTags();
        this.flag = 0;
      } else {
        searchText = encodeURIComponent(searchText);
        http
          .get("/tag/searchtext?data=" + searchText)
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
      }
    },
    showTag(tagName)
{
let tag_name=tagName;
if(tag_name.length>12)
{
  tag_name=tag_name.slice(0,12)+'...'
}
  return tag_name;
},
    async getUser() {
      http.get("/getuser").then((response) => {
        this.User = response.data.user;
        this.userType = response.data.user.type;
        this.isLoading = false;
      });
    },
    openModal() {
      this.v$.$reset();
      document.body.classList.add('overlay');
      document.getElementById('ft-demo-modal').style.display = 'block';
      document.body.style.overflow = "hidden";
    },
    modalClose() {
      this.tagName = "";
      this.v$.$reset();
      document.getElementById('ft-demo-modal').style.display = 'none';
      document.body.classList.remove('overlay');
      document.body.style.overflow = "auto";
    },
    // notification
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
    navToProfile() {
      this.$router.push("/profile");
    },
    NotificationDetails(Qid) {
      http.post("/questionviews/" + Qid, {}).then(() => { });
      this.$router.push("/questionView/" + Qid);
    },
    deleteNotification() {
      http.put("user/notification/delete").then(() => {
        window.location.reload();
      });
    },
    async addTag() {
      document.getElementById("btnAddTag").disabled = true;
      this.v$.$validate();
      if (!this.v$.tagName.$error) {
        let tagname = this.tagName.split(" ").filter(Boolean).join(" ");
        const bodyParameters = {
          tag_name: tagname,
        };
        await http
          .post("/newtag", bodyParameters)
          .then(() => {

            document.getElementById("btnAddTag").disabled = false;
            this.modalClose();
            this.toast.success("Tag added successfully", {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
            this.getAllTags();
          })
          .catch((error) => {
            document.getElementById("btnAddTag").disabled = false;
            this.modalClose();
            let msg = "Unable to process !!" + error.response.data.message;
            this.toast.error(msg, {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
          });
      }
    },
    async viewTagQuestion(id) {
      http.get("/questions-onTags/" + id)
        .then(response => {
         localStorage.setItem('chosenTag', response.data.tagName);
          this.$router.push({ path: "/tagquestion/" + id });
        });
    },
    async getAllTags() {
      this.flag = 0;
      http.get("/tags").then((response) => {
        this.searchResult = response.data;
      });
    },
    dashboard() {
      window.scrollTo(0, 0);
      window.location.reload();
    },
  },
};
</script>
<style scoped>
@import url("TagList.css");
</style>
