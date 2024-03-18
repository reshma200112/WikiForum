<template>
  <div id="body" @click="closeMOdal()">
    <section class="main">
      <div v-if="isLoading">
        <Loader />
      </div>

      <div v-else div class="main-body">
        <div class="main-content-profile">
          <div class="container">
            <div class="innerwrap">
              <section class="section1 clearfix" id="profileSection">
                <div>
                  <div class="row grid clearfix" id="ProfilePage">
                    <div class="col2 first">
                      <div class="avatar-upload" v-show="users.type == 0">
                        <div class="avatar-edit">
                          <input
                            type="file"
                            id="imageUpload"
                            accept=".jpg,.jpeg,.png,.gif"
                            @change="onProfilePicChange()"
                          />
                          <label for="imageUpload" class="camera-icon"
                            ><em class="fa fa-camera" aria-hidden="true"></em
                          ></label>
                        </div>
                      </div>
                      <div class="avatar-preview">
                        <img
                          :src="imageSrc"
                          referrerpolicy="no-referrer"
                          alt=""
                          id="imagePreview"
                        />
                      </div>
                      <h1 class="profileUserName">{{ users.name }}</h1>
                      <p class="EmailProfile">Email: {{ users.email }}</p>

                      <p
                        v-if="showAlert"
                        :bind="showAlert"
                        type="error"
                        density="compact"
                        dismissible
                        id="profilefilealert"
                      >
                        {{ alertMsgProfile }}
                      </p>
                      <button
                        class="update-profile-pic"
                        v-show="editimageflag"
                        @click="editPicture()"
                      >
                        Update profile
                      </button>
                    </div>
                    <div class="col2 last">
                      <div class="grid clearfix">
                        <div class="col3 first">
                          <h1>{{ count }}</h1>
                          <span> Questions</span>
                        </div>
                        <div class="col3">
                          <h1>{{ users.points }}</h1>
                          <span>Points</span>
                        </div>
                    
                        <div class="col3 last">
                          <img
                            v-if="users.points >= 5000"
                            :src="require('@/assets/gold.png')"
                            alt=""
                            id="profileBadge"
                          />
                          <img
                            v-else-if="users.points >= 1000"
                            :src="require('@/assets/silver.png')"
                            alt=""
                            id="profileBadge"
                          />
                          <img
                            v-else-if="users.points >= 100"
                            :src="require('@/assets/bronze.png')"
                            alt=""
                            id="profileBadge"
                          />
                          <img
                            v-else
                            :src="require('@/assets/nopoint.jpg')"
                            alt=""
                            class="noScoreBadgeProfile"
                            id="profileBadge"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row clearfix" id="listUl">
                    <ul class="row2tab clearfix" id="ulProfileList">
                      <li id="profileList" @click="editUserModalOpen()">
                        <em class="fa fa-list-alt"></em> Edit Profile
                      </li>
                      <li id="profileList" @click="editTag()">
                        <em class="fa fa-heart"></em> Edit Interested Tags
                      </li>
                      <li id="profileList" @click="showPostedQuestion()">
                        <em class="fa fa-check"></em> Show Posted questions
                      </li>
                    </ul>
                  </div>
                </div>
                <span class="smalltri">
                  <i class="fa fa-star"></i>
                </span>
              </section>
              <section class="section-watchtags">
                <div class="interestedtags-div">
                  <div class="watchtags-title">
                 <h5 class="interestedTags">Interested Tags</h5>
                </div>
                  <div class="row-userTag">
                  <div
                    class="column-userTag"
                    :key="item.id"
                    v-for="item in userSelectedTags"
                  >
                    <div class="div-watchtags">
                      <div class="abcd"></div>
                      <span class="TagnameSize">
                       # {{ showTag(item.tag_name)}}  <v-tooltip activator="parent" location="bottom" id="taglist-tooltip" v-if="item.tag_name.length>10"
                >{{ item.tag_name }}</v-tooltip
                >
                      </span>
                    </div>
                  </div>
                 </div>
                </div>
                <div class="ignoredags-div">
                  <div class="watchtags-title">
                 <h5 class="interestedTags">Ignored Tags</h5>
                 </div>
                  <div class="row-userTag">
                  <div
                    class="column-userTag"
                    :key="item.id"
                    v-for="item in userSelectedTags"
                  >
                    <div class="div-watchtags">
                      <div class="abcd"></div>
                      <span class="TagnameSize">
                       # {{ showTag(item.tag_name)}}  <v-tooltip activator="parent" location="bottom" id="taglist-tooltip" v-if="item.tag_name.length>10"
                >{{ item.tag_name }}</v-tooltip
               >
                      </span>
                    </div>
                  </div>
                 </div>
                </div>
              </section>
              <div class="question-answer-div">
                <div class="question5-div">
                  <div class="watchtags-title">
                 <h5 class="interestedTags">Questions</h5>
                 </div>
                <div class="element-question-div" :key="obj.id" v-for="(obj, index) in questionList.slice(0,5)">
                  <span class="question-element5-title">{{ index + 1 }}. {{ obj.title }}</span>
                </div>
              </div>
              <div class="answer5-div">
                <div class="watchtags-title">
                 <h5 class="interestedTags">Answers</h5>
                 </div>
                <div class="element-question-div" :key="obj.id" v-for="(obj, index) in questionList.slice(0,5)">
                  <span class="question-element5-title">{{ index + 1 }}. {{ obj.title }}</span>
                </div>
              </div>
            </div>
              <br>
              <div style="width:100%; background-color: #fff;    border: 0.2px solid #bfbcbc;">
              <div>
                <h3 class="a" style="display: flex;justify-content: center;align-items: center;color:#0b1b5f;font-weight: bolder;">Impact</h3>    
         
              <div>
                
                <div style="width:50%;float:right">
                <h4 style="text-align: center;">Total upvotes received</h4>
                <h3 style="text-align: center;color:#fb4c22">
                    <vue3autocounter ref='counter' :startAmount='0' :endAmount='totalUpvotes' :duration='3'  separator=',' decimalSeparator='.' :decimals='2' :autoinit='true' @finished='alert(`Counting finished!`)'/>

                </h3>
                </div>
                <div style="width:50%">
                <h4 style="text-align: center;">Total views received</h4>
                <h3 style="text-align: center;color:#fb4c22">{{ totalViews }}</h3>

                </div>
              </div>
            </div>
              </div>
              <div>
     <!-- <vue3autocounter ref='counter' :startAmount='0' :endAmount='2021' :duration='3' prefix='$' suffix='USD' separator=',' decimalSeparator='.' :decimals='2' :autoinit='true' @finished='alert(`Counting finished!`)'/> -->
    <div class="barChart" style="width:100%;float: left;margin-left:-0%">
      <div style="display:flex;justify-content: center;align-items: center;margin-top:4%;margin-left:2%">
    <h2 style="color:#0b1b5f"> Bar-Diagram based on user posted question(Weekly basis)</h2>
    </div>
    <graph style="margin-left:%"/>
    
    </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="ft-modal" id="editProfile">
      <div class="ft-modal-content">
        <div class="ft-modal-body">
          <form action="" @submit.prevent="update()">
            <label class="userName"
              >Username<small class="manstar-p">*</small></label
            >
            <v-text-field
              placeholder="User name here"
              :counter="30"
              v-model.trim="userName"
              @blur="v$.userName.$touch()"
              @keyup="v$.userName.$touch()"
              v-bind:class="{
                error: v$.userName.$error,
                valid: v$.userName.$dirty && !v$.userName.$invalid,
              }"
            ></v-text-field>
            <span
              class="error-show"
              v-for="error in v$.userName.$errors"
              :key="error.$uid"
            >
              {{ error.$message }}</span
            >
            <hr />

            <div class="ft-modal-footer">
              <v-btn
                color="blue-darken-1"
                variant="text"
                class="btn-tag-close"
                @click="modalCloseEditProfile"
              >
                Close </v-btn
              >&nbsp;&nbsp;
              <div v-if="userName == ''">
                <v-btn
                  color="blue-darken-1"
                  variant="text"
                  type="submit"
                  class="btn-tag"
                  disabled
                >
                  Submit
                </v-btn>
              </div>
              <div v-else>
                <v-btn
                  color="blue-darken-1"
                  variant="text"
                  type="submit"
                  class="btn-tag"
                >
                  Submit
                </v-btn>
              </div>
            </div>
          </form>
          <hr />
        </div>
      </div>
    </div>

    <!-- user edit profile modal start -->
    <div class="ft-modal-tag" id="editTag">
      <div class="ft-modal-content-tag">
        <div class="ft-modal-body-tag">
          <div class="overlay" id="edit-tag-modal-body">
            <div class="content">
              <v-card-title>
                <span class="text-h5">Edit Tag</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <label
                        >Select Tag<small class="manstar-p">*</small></label
                      >

                      <v-autocomplete
                        v-model="userTags"
                        :items="TagList"
                        chips
                        closable-chips
                        color="blue-grey-lighten-2"
                        item-title="tag_name"
                        item-value="id"
                        multiple
                        variant="filled"
                        :menu-props="{ maxHeight: 200 }"
                        @change="userTags.$touch()"
                        maxlength="5"
                        v-bind:class="{
                          error: v$.userTags.$error,
                          valid: v$.userTags.$dirty && !v$.userTags.$invalid,
                        }"
                      >
                        <template v-slot:chip="{ props, item }">
                          <v-chip
                            v-bind="props"
                            :text="item.raw.tag_name"
                          ></v-chip>
                        </template>
                        <template v-slot:item="{ props, item }">
                          <v-list-item
                            v-bind="props"
                            :title="item?.raw?.tag_name"
                          ></v-list-item>
                        </template>
                      </v-autocomplete>
                      <span
                        class="error-show"
                        v-for="error in v$.userTags.$errors"
                        :key="error.$uid"
                        >{{ error.$message }}</span
                      >
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <div class="updateTagFooter">
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="modalTagClose()">
                    Close </v-btn
                  >&nbsp;&nbsp;
                  <v-btn color="blue darken-1" text @click="UserTagEdit()">
                    Save
                  </v-btn>
                </v-card-actions>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- edit tag modal end -->
  </div>
</template>

<script>
import { useToast, POSITION } from "vue-toastification";
import Vue3autocounter from 'vue3-autocounter';
import Loader from "../Loader.vue";
import http from "../../http-common";
import { useVuelidate } from "@vuelidate/core";
import { required, maxLength, helpers } from "@vuelidate/validators";
import graph from '../Graph/BarGraphUser.vue'
const mustBeCool = (value) => {
  return value.match(/^\s*/);
};
export default {
  components: {
    Loader,graph,Vue3autocounter
  },
  name: "Profile-user",
  // async created() {
  //  await this.update();
  // },

  validations() {
    return {
      userName: {
        required: helpers.withMessage("Username is required .", required),
        maxLength: helpers.withMessage(
          "Maximum length allowed is 30",
          maxLength(30)
        ),
        mustBeCool: helpers.withMessage(
          "Initial spaces are not allowed",
          mustBeCool
        ),
      },
      userTags: {
        required: helpers.withMessage("Tag is required", required),
        maxLength: helpers.withMessage(
          "You can only select upto 5 tags",
          maxLength(5)
        ),
        $autoDirty: true,
      },
    };
  },
  data() {
    return {
      questionList:{},
      countImpact:'',
      totalViews:'',
      totalUpvotes:'',
      showAlert: false,
      isLoading: true,
      imageSrc: undefined,
      v$: useVuelidate(),
      alertMsgProfile: "",
      imagefile: null,
      dialog: false,
      editimageflag: false,
      User: {},
      currentUserId: undefined,
      dialog_tagedit: false,
      TagList: "",
      dialog_delete: undefined,
      tagListForEdit: undefined,
      editSelectedTagList: [],
      selectDropdownFlag: false,
      editedTag: "",
      notifications: "",
      points: undefined,
      count: "",
      oldSelectedTagId: "",
      userType: "",
      userTags: [],
      userName: "",
      userSelectedTags: [],
      users: {
        name: "",
        email: "",
        profile_picture: "",
        tempname: "",
        icons: ["mdi-facebook", "mdi-twitter", "mdi-linkedin", "mdi-instagram"],
      },
      nameRules: [
        (v) => !!v || "Name is required",
        // (v) => v.length <= 10 || "Name must be less than 10 characters",
      ],
    };
  },
  setup() {
    const toast = useToast();
    return { toast };
  },

  methods: {
    onProfilePicChange() {
      let flagState = this.editimageflag;
      const selectedFile = event.target.files[0];
      const fileSize = selectedFile.size / 1024 / 1024; // convert to MB
      const allowedExtensions = ["jpg", "jpeg", "png", "gif"];
      const fileExtension = selectedFile.name.split(".").pop().toLowerCase();
      if (fileSize > 2 || !allowedExtensions.includes(fileExtension)) {
        if (!allowedExtensions.includes(fileExtension)) {
          this.alertMsgProfile = "File type not allowed";
        } else if (fileSize > 2) {
          this.alertMsgProfile = "File must be less than 2MB";
        }

        this.editimageflag = false;
        this.showAlert = true;
        // clear the file input field
        event.target.value = "";
        // hide the alert after 2 seconds
        setTimeout(() => {
          this.showAlert = false;
          this.editimageflag = flagState;
        }, 3000);
      } else {
        const reader = new FileReader();
        reader.onload = (event) => {
          this.imageSrc = event.target.result;
        };
        this.editimageflag = true;
        reader.readAsDataURL(selectedFile);
        const files = event.target.files || event.dataTransfer.files;
        if (!files.length) return;
        this.imagefile = files[0];
      }
    },
    editPicture() {
      this.editimageflag = false;
      const formData = new FormData();
      formData.append("image", this.imagefile);
      http
        .post("user/upload/image", formData)
        .then(() => {
          this.getCurrentUser().then(() => {
            this.toast.success("Profile picture updated succesfully", {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
          });
        })
        .catch((error) => {
          let msg = "Unable to process !!" + error.response.data.message;
          this.toast.error(msg, {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
          this.editimageflag = true;
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
    async update() {
      this.v$.$validate();
      if (!this.v$.userName.$error) {
        let username = this.userName.replace(/  +/g, " ");
        await http
          .put("/users/edit", { name: username })
          .then(() => {
            this.getQuestionCount();
            this.getCurrentUser();
            this.dialog = false;
            this.toast.success("Profile updated succesfully", {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
          })
          .catch((error) => {
            let msg = "Unable to process !!" + error.response.data.message;
            this.toast.error(msg, {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
          });

        this.modalCloseEditProfile();
      }
    },
    navToProfile() {
      window.location.reload();
    },
    async UserTagEdit() {
      this.v$.$validate();
      if (!this.v$.userTags.$error) {
        const bodyParameters = {
          user_tags: this.userTags,
        };

        await http
          .put("/edit-tag", bodyParameters)
          .then(() => {
            this.modalTagClose();
            this.getQuestionCount();
            this.getCurrentUser();
            this.getUserTags();

            this.toast.success("Tags updated succesfully", {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
          })
          .catch((error) => {
            let msg = "Unable to process !!" + error.response.data.message;
            this.toast.error(msg, {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
          });
      }
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
    async getAlltags() {
      http
        .get("/tags")
        .then((response) => {
          this.TagList = response.data;
          http
            .get("/tag/get-tagid")
            .then((response) => {
              this.userTags = response.data;
            })
            .catch((error) => {
              throw error;
            });
        })
        .catch(function (error) {
          throw error;
        });
    },
    async getImpact(){
      await http.get("/user/impact").then((res)=>{
   
        const dataArray = Object.values(res.data)
          console.log("11",res.data);
     
          this.countImpact = dataArray
       
          this.totalUpvotes=this.countImpact[0];
          this.totalViews = this.countImpact[1];

    
    })
    },
    async getQuestionCount() {
      http.get("/users/question/count").then((response) => {
        this.count = response.data.Count;
      });
    },
    async getCurrentUser() {
      http.get("/getuser").then((response) => {
        this.users = response.data.user;
        this.User = response.data.user;
        this.currentUserId = response.data.id;
        this.userName = response.data.user.name;
        this.userType = response.data.user.type;
        if (this.userType === 0) {
          if (this.users.profile_picture != null) {
            this.imageSrc = this.users.profile_picture;
          } else {
            this.imageSrc = require("@/assets/avatar.png");
          }
        } else {
          this.imageSrc = this.users.profile_picture;
        }
      });
    },

    async getUserTags() {
      http.get("/getUserTags").then((response) => {
        this.userSelectedTags = response.data;
      });
    },
    async editTag() {
      await this.getUserTags();
      await this.getAlltags();
      document.body.classList.add("overlay");
      document.getElementById("editTag").style.display = "block";
      document.body.style.overflow = "hidden";
    },
    modalTagClose() {
      this.v$.$reset();
      document.body.classList.remove("overlay");
      document.getElementById("editTag").style.display = "none";
      document.body.style.overflow = "auto";
    },
    closeUpdateModal() {
      this.dialog = false;
    },

    closeTagUpdateModal() {
      this.selectDropdownFlag = false;
      this.dialog_tagedit = false;
    },
    editUserModalOpen() {
      this.getCurrentUser();
      document.body.classList.add("overlay");
      document.getElementById("editProfile").style.display = "block";
      document.body.style.overflow = "hidden";
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
    NotificationDetails(Qid) {
      http.post("/questionviews/" + Qid, {}).then(() => {});
      this.$router.push("/questionView/" + Qid);
    },
    deleteNotification() {
      http.put("user/notification/delete").then(() => {
        window.location.reload();
      });
    },
    modalCloseEditProfile() {
      this.v$.$reset();
      document.body.classList.remove("overlay");
      document.getElementById("editProfile").style.display = "none";
      document.body.style.overflow = "auto";
    },
    dashboard() {
      window.scrollTo(0, 0);
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
    showPostedQuestion() {
      this.$router.push("/question");
    },
    showTag(tagName)
{
let tag_name=tagName;
if(tag_name.length>10)
{
  tag_name=tag_name.slice(0,10)+'...'
}
  return tag_name;
},
async getAllQuestions() {
      http.get("/questions").then((response) => {
        this.questionList = response.data.data;
      });

    },
  },

  async beforeMount() {
    await this.getImpact();
    await this.getQuestionCount();
    await this.getCurrentUser();
    await this.getUserTags();
    await this.getAllQuestions();
    await http
      .get("/user/notification")
      .then((response) => {
        this.notifications = response.data;
        console.log(this.notifications.length);
      })
      .catch(function (error) {
        throw error;
      });
    this.isLoading = false;
  },
  watch: {
    dialog: function (val) {
      if (val) {
        this.userName = "";
        this.v$.$reset();
      }
    },
    // for tag edit
    dialog_tagedit: function (val) {
      if (val) {
        this.userTags = "";
        this.v$.$reset();
      }
    },
  },
};
</script>
<style scoped>
.row-userTag {
  display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    width: fit-content;
    padding: 20px 5px 20px 5px;
}

.column-userTag {
  max-width: 100%;
  padding: 0 10px;
  display: inline-block;
}

.interestedTags {
  color: #0b1b5f;
    font-size: 21px;
    font-weight: 500;
}
.watchtags-title
{
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: row;
}
.div-watchtags {
   box-shadow: 0 3px 4px 0 rgb(0 0 0 / 20%);
    padding: 10px;
    text-align: center;
    border-radius: 5px;
    color: aliceblue;
    background-color: #51a2d8;
    margin-bottom: 20px;
    width: 150px;
    white-space: nowrap;
}
.question-element5-title{
  cursor: pointer;
}
.question-element5-title:hover{
  color: rgb(255, 111, 0)
}
.question5-div ,.answer5-div{
  background-color: white;
    border: 0.2px solid #bfbcbc;
    padding: 10px;
    word-break: break-word;
    width: calc(50% - 10px);

}
.question-answer-div{
  display: flex;
    gap: 10px;
    justify-content: space-between;
    /* flex-wrap: wrap; */
    margin-top: 50px;
    flex-direction: row;
}
.ignoredags-div ,.interestedtags-div{

  width: calc(50% - 10px);
  background-color: white;
  border: solid 0.2px rgb(196 196 196);
}
#profilefilealert {
  display: flex;
  flex-wrap: wrap;
  word-break: break-word;
  margin: 0 !important;
  background-color: transparent;
  color: red;
  max-height: 50px;
  max-width: 450px;
}

.error-show {
  position: absolute;
  left: 35px;
  margin-top: -15px;
}

/* @import url("Profile.css"); */
.noScoreBadgeProfile {
  width: 63px !important;
  height: 60px !important;
  border-radius: 36px;
  border: solid 2px red;
}

.ft-modal-content {
  width: 28%;
  padding: 25px;
  background: #fff;
  max-width: 600px;
  margin: 70px auto;
  position: relative;
  border-radius: 8px;
  box-shadow: 1px 1px 37px 5px rgb(0 0 0 / 20%);
}

* {
  margin: 0px;
  padding: 0px;
}

*,
*:after,
*:before {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  -ms-box-sizing: border-box;
  -o-box-sizing: border-box;
  box-sizing: border-box;
}

.clearfix:before,
.clearfix:after {
  display: table;
  content: "";
}

.clearfix:after {
  clear: both;
}

body {
  background: #e2e2e2;
  color: #333;
  font-weight: normal;
  font-size: 1em;
  font-family: "Roboto", Arial, sans-serif;
}

input:focus,
textarea:focus,
keygen:focus,
select:focus {
  outline: none;
}

::-moz-placeholder {
  color: #666;
  font-weight: 300;
  opacity: 1;
}

::-webkit-input-placeholder {
  color: #666;
  font-weight: 300;
}

.grid .col2 {
  width: 50%;
  padding: 0 10px 0 0;
}

.grid .col2.first {
  float: left;
}

.grid .col2.last {
  float: right;
}

.grid .col3 {
  width: 32%;
  float: left;
  margin-right: 11px;
}

.grid .col3.first {
  margin-left: 0;
  float: left;
}

.grid .col3.last {
  margin-right: 0;
  float: right;
}

/* profile page */
.container {
  padding: 30px 50px 70px;
}

.innerwrap {
  width: 100%;
  margin: 0 auto;
}

.section1 {
  background: #fff;
  position: relative;
  border-radius: 2px;
}

.section1 div .row:first-child {
  padding: 25px;
}

.section1 .col2.first img {
  border-radius: 50%;
  margin: 0 20px;
  width: 120px;
  height: 120px;
  border: 2px solid #f1f1f1;
  padding: 2px;
  float: left;
}

.section1 .col2.first {
  line-height: 25px;
  position: relative;
  border-right: 1px solid #a2a2a2;
}

.section1 .col2.first h1 {
  font-weight: normal;
  margin-bottom: 10px;
  margin-top: 15px;
}

.section1 .col2.first p {
  font-size: 14px;
}

.section1 .col2.first span {
  position: absolute;
  right: 15px;
  top: 16px;
  background: #6aafea;
  padding: 5px 11px;
  font-size: 12px;
  line-height: 1;
  border-radius: 2px;
  color: #fff;
  cursor: pointer;
}

.section1 .col2.last {
  padding: 8px 0;
}

.section1 .col2.last .col3 span {
  color: #a2a2a2;
  font-size: 14px;
}

.section1 .col2.last .col3 h1 {
  color: #fb4c22;
}

.section1 .col2.last .col3 {
  text-align: center;
  line-height: 30px;
  border-right: 1px solid #ccc;
}

.section1 .col2.last .col3.last {
  border-right: 0;
}

.row2tab li {
  list-style: none;
  float: left;
  width: 25%;
  padding: 15px;
  font-size: 14px;
  text-align: center;
  cursor: pointer;
  background: #f1f1f1;
  color: #555;
  border-bottom: 2px solid #f1f1f1;
}

.row2tab li:first-child {
  color: #6aafea;
  border-bottom: 2px solid #6aafea;
  border-radius: 0 0 2px 2px;
}

/* .row2tab li:first-child {
  
} */

.row2tab li i {
  margin-right: 3px;
  font-size: 14px;
}

.smalltri {
  border-right: 40px solid #6aafea;
  height: 0;
  width: 0;
  border-left: 20px solid transparent;
  border-top: 10px solid #6aafea;
  border-bottom: 20px solid transparent;
  padding: 0px;
  top: 0;
  right: 0;
  position: absolute;
}

.smalltri i {
  position: absolute;
  top: -5px;
  right: -33px;
  color: #fff;
  border: 0px;
  font-size: 12px;
}

.section-watchtags{
   display: flex;
    justify-content: space-between;
    margin-top: 50px;
    flex-direction: row;
    gap: 10px;
}

.section2 .col3 {
  width: 30%;
  margin-right: 60px;
  background: #fff;
  border-radius: 2px;
}

.section2 .postcont img {
  width: 100%;
}

.section2 .profileinfo {
  text-align: center;
  padding: 0 10px 30px;
  color: #555;
  font-size: 14px;
  line-height: 25px;
}

.section2 .profileinfo img {
  border-radius: 50%;
  width: 80px;
  padding: 2px;
  border: 3px solid #6aafea;
  margin-top: -48px;
  margin-bottom: 18px;
}

.section2 .col3.center .profileinfo img {
  border: 3px solid #fb4c22;
}

.section2 .profileinfo p {
  text-align: justify;
}

.section2 .profileinfo span {
  margin-top: 15px;
  display: block;
  text-align: left;
  color: #6aafea;
  cursor: pointer;
}

.section2 .profileinfo span i {
  margin-left: 10px;
}

#profileBadge {
  width: 60px;
  height: 60px;
  position: absolute;
}

#profileList {
  width: 280px;
  list-style: none !important;
  color: black !important;
  border: none !important;
}

#profileList:hover {
  color: rgb(255, 111, 0) !important;
}

.profileUserName {
  word-wrap: break-word;
  font-size: 20px;
}

.row-userTag {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  padding: 20px;
  width: fit-content;
  padding-top: 20px;
  padding-left: 10px;
}

.column-userTag {
  max-width: 100%;
  padding: 0 10px;
}

.interestedTags {
  /* padding-left: 30px; */
  color: #0b1b5f;
  font-weight: bolder;
}

.card-userTag {
  box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%);
  padding: 16px;
  text-align: center;
  background-color: #ffffff;
  margin-bottom: 20px;
  width: 250px;
  /* cursor: pointer; */
}

.v-field__input {
  height: auto !important;
}

.ft-modal-tag {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 2000;
  position: fixed;
  padding-top: 180px;
  display: none;
  transition: all 0.3s ease-in-out;
  -moz-transition: all 0.3s ease-in-out;
  -webkit-transition: all 0.3s ease-in-out;
}

.ft-modal-content-tag {
  width: 100%;
  padding: 25px;
  background: #fff;
  max-width: 600px;
  margin: 70px auto;
  position: relative;
  border-radius: 8px;
  box-shadow: 0 0 6px rgba(0, 0, 0, 0.2);
}

.EmailProfile {
  word-break: break-all;
}

#edit-tag-modal-body {
  height: auto !important;
}
@media only screen and (max-width: 1097px) {
.Impact{
  width:100% !important;
}
.Impact .card{
  width:auto
}
.barChart{
  width:100% !important;


}
}

/* @media only screen and (min-width: 1700px) {
  .Impact{
  width:40% !important;
}
.Impact .card{
  width:auto
}

  .barChart{
  width:60% !important;


}
} */
@media only screen and (max-width: 1700px) {
  .innerwrap {
    width: 80%;
  }
}

@media only screen and (max-width: 1300px) {
  .section2 .col3 {
    margin-right: 5%;
  }

  .section1 .grid .col3 {
    margin-right: 2%;
  }

  .section1 .col2.last .col3.last {
    margin-right: 0;
  }
}

@media only screen and (max-width: 1060px) {
  .section1 .col2 {
    width: 100%;
    border-right: 0 !important;
    padding: 0;
  }
}
@media only screen and (max-width: 345px) {

#profileList{
  max-width: 190px !important;
}
}
@media only screen and (max-width: 660px) {
  .section2 .col3 {
    width: 100%;
    float: none !important;
    margin-bottom: 10px;
  }

  .innerwrap {
    margin: 10px;
  }

  #section1 {
    width: fit-content;
  }

  .row2tab li {
    width: 33.333333333%;
    text-align: left;
  }

  .section1 .col2.first {
    text-align: center;
  }

  .section1 .col2.first img {
    display: inline-block;
    float: none;
  }

  .section1 .col2.first span {
    position: relative;
    right: 0;
  }

  .section1 .col2.last {
    margin-top: 25px;
  }
}

@media only screen and (max-width: 450px) {
  .row2tab li {
    width: 100%;
    text-align: left;
  }

  .section1 .col2.last .col3 span {
    font-size: 10px;
  }

  .section1 .col2.last .col3 h1 {
    font-size: 18px;
  }
}

@media only screen and (max-width: 1081px) {
  #ulProfileList {
    display: flex;
    flex-direction: column;
  }
}

@media only screen and (max-width: 1301px) {
  #ulProfileList {
    display: flex;
    flex-direction: column;
  }
}

@media screen and (max-width: 1700px) and (min-width: 1300px) {
  #profileBadge {
    /* background-color: #6AAFEA; */
    margin-top: -60px;
  }
}

@media only screen and (max-width: 285px) {
  #profileList {
    width: 190px;
  }

  .column-userTag {
    padding: 0;
  }
}

.avatar-upload {
  position: relative;
  max-width: 205px;
}

.avatar-upload .avatar-edit {
  position: absolute;
  left: 112px;
  z-index: 1;
  top: 81px;
}

.avatar-upload .avatar-edit input {
  display: none;
}

.avatar-upload .avatar-edit input + label {
  display: inline-block;
  width: 30px;
  height: 30px;
  margin-bottom: 0;
  border-radius: 100%;
  background: #ffffff;
  border: 1px solid transparent;
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
  cursor: pointer;
  font-weight: normal;
  transition: all 0.2s ease-in-out;
}

.avatar-upload .avatar-edit input + label:hover {
  background: #f1f1f1;
  border-color: #d6d6d6;
  color: rgb(255, 111, 0);
}

.avatar-upload .avatar-edit input + label:after {
  font-family: "FontAwesome";
  color: #757575;
  position: absolute;
  top: 10px;
  left: 0;
  right: 0;
  text-align: center;
  margin: auto;
}

.avatar-upload .avatar-preview {
  width: 192px;
  height: 192px;
  position: relative;
  border-radius: 100%;
  border: 6px solid #f8f8f8;
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
}

.avatar-upload .avatar-preview > div {
  width: 100%;
  height: 100%;
  border-radius: 100%;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}

.camera-icon {
  justify-content: center;
  display: flex !important;
  padding-top: 6px;
  background-color: #fdfdfd;
  color: #090a0cfc;
  font-size: 15px;
}

.update-profile-pic {
  background-color: #66b3ff;
  color: white;
  border-radius: 5px;
  padding: 5px;
  width: 91px;
  font-size: small;
  margin-top: 10px;
  word-break: break-word;
}
.card {
max-width:114%;
 width: 190px;
 word-break: break-word;
 height:auto;
 /* height: 254px; */
 margin: 0 auto;
 background-color: #F8FBFE;
 border-radius: 8px;
 z-index: 1;
}

.tools {
 display: flex;
 align-items: center;
 padding: 9px;
}

.circle {
 padding: 0 4px;
}

.box {
 display: inline-block;
 align-items: center;
 width: 10px;
 height: 10px;
 padding: 1px;
 border-radius: 50%;
}

.red {
 background-color: #ff605c;
}

.yellow {
 background-color: #ffbd44;
}

.green {
 background-color: #00ca4e;
}

</style>
