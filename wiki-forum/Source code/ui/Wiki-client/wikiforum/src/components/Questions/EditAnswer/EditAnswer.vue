<template>
  <div id="body" @click="closeMOdal()">
    <div v-if="isLoading">
      <Loader />
    </div>
    <div v-else class="container">
      
      <div class="main">
       
        <div class="main-body-Add">
          <div class="main-content-Add">
            <div class="contentAddQuestion-Add">
              <form action="" name="addAnswer" @submit.prevent="setEditAnswer()" id="addAnswer-media">
                <label for="answer"><strong>Type your answer</strong></label>
                <hr />

                <div class="ckEditormedia">
                <ckeditor :editor="editor" v-model="edAnswer" :config="editorConfig"></ckeditor>
</div>
                <span class="error-show" v-for="error in v$.edAnswer.$errors" :key="error.$uid">{{
                  error.$message
                }}</span>
                <hr />
                <div class="form_field checkbox">
                  <input type="checkbox" name="acceptCondition" value="accept" v-model="accept" />&nbsp;&nbsp;Make sure
                  that this answer would not hurt
                  anyone&nbsp;&nbsp;&nbsp;
                </div>
                <div v-if="edAnswer == ''">
                <v-btn depressed color="blue darken-1" type="submit" disabled="true"
                  >Submit</v-btn
                >
              </div>

              <div v-else-if="accept != ''">
                <v-btn depressed color="blue darken-1" type="submit">Submit</v-btn>
              </div>

              <div v-else>
                <v-btn
                  v-if="accept == ''"
                  depressed
                  color="blue darken-1"
                  disabled="true"
                  >Submit</v-btn
                >
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import Loader from '../../Loader.vue';
import { useToast, POSITION } from "vue-toastification";
import { useVuelidate } from "@vuelidate/core";
import { required, maxLength, helpers } from "@vuelidate/validators";
import http from "../../../http-common";


export default {
  // components: {
  //   Sidebar,
  // },
  validations() {
    return {
      edAnswer: {
        required: helpers.withMessage("Answer is required", required),
        maxLength: helpers.withMessage(
          "Maximum length allowed is 4096 characters",
          maxLength(4103)
        ),
        $autoDirty: true,
      },
    };
  },
  components: {
    Loader
  },
  data() {
    return {
      isLoading: true,
      userType:'',
      v$: useVuelidate(),
      accept: "",
      flag: undefined,
      userlist: undefined,
      searchFlag: undefined,
      searchResult: {},
      notifications: "",
      search: "",
      User: {},
      down: false,
      list: undefined,
      editor: ClassicEditor,
      edAnswer: "",
      editorConfig: {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo'],
        table: {
          toolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
        },
      },
      edAnswerId: undefined,
      questionId: undefined,
    };
  },
  setup() {
    const toast = useToast();
    return { toast };
  },
  async beforeMount() {
    await this.getUser();
    let id = this.$route.params.answerId;
    http
      .get("/answer/view/" + id)
      .then((response) => {
        this.edAnswer = response.data?.Answer?.answer;
        this.edAnswerId = response.data?.Answer?.id;
        this.setQuestionId(response.data?.Answer?.question_id);
        http
      .get("/user/notification")
      .then((response) => {
        this.notifications = response.data;
        console.log(this.notifications.length);
        this.isLoading=false;
      })
      .catch(function (error) {
        throw error;
      });
      })
      .catch(function (error) {
        throw error;
      });

    
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
    async getUser() {
      http.get("/getuser").then((response) => {
        this.User = response.data.user;
        this.userType = response.data.user.type;
      });
    },
    // notifications
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
    deleteNotification() {
      http.put("user/notification/delete").then(() => {
        window.location.reload();
      });
    },
    dashboard() {
      window.scrollTo(0, 0);
    },

    setEditAnswer() {
      this.v$.$validate();
      const bodyParameters = {
        answer: this.edAnswer,
      };
      if (!this.v$.edAnswer.$error) {
        http
          .put("/answer/" + this.edAnswerId, bodyParameters)
          .then(() => {
            this.dialog_answer = false;

            this.toast.success("Answer edited successfully", {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
        
            history.back();
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
    navToProfile() {
      this.$router.push('/profile');
    },
    setQuestionId(id) {
      console.log("+++++++++++++++", id);
      this.questionId = id;
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
};
</script>
  
<style scoped>
@import url("EditAnswer.css");
</style>