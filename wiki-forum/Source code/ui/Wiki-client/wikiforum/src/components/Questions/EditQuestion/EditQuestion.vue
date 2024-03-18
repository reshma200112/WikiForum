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
              <form action="" @submit.prevent="editQuestionSubmit()" id="QuestionAddFrom">
                <div class="qtitle">
                  <label for="">Question title<span class="mandotory">*</span></label>
                  <v-text-field :counter="100" class="question" id="textExample" v-model.trim="edTitle"
                    autocomplete="off" @blur="v$.edTitle.$touch()" @keyup="v$.edTitle.$touch()" v-bind:class="{
                      error: v$.edTitle.$error,
                      valid: v$.edTitle.$dirty && !v$.edTitle.$invalid,
                    }" />
                  <span class="error-show" v-for="error in v$.edTitle.$errors" :key="error.$uid">{{
                    error.$message
                  }}</span>
                   <div class="spacing-div">
                
              </div>
                </div>
                <div id="tg">
               
                    <label>Question Description<small class="manstar-p">*</small></label>
                    <div class="ckEditormedia">
                    <ckeditor :editor="editor" v-model="edDescription" :config="editorConfig">
                    </ckeditor>
</div>
                    <span class="error-show" v-for="error in v$.edDescription.$errors" :key="error.$uid">{{
                      error.$message
                    }}</span>
              
                  <br />
                  <div class="autocflex">
                  <div class="autoc">
                    <label>Select Tag<small class="manstar-p">*</small></label>

                    <v-autocomplete v-model="editSelectedTagList" :items="tagListForEdit" chips closable-chips
                      color="blue-grey-lighten-2" item-title="tag_name" item-value="id" multiple variant="filled"
                      :menu-props="{ maxHeight: 200 }" @change="editSelectedTagList.$touch()" v-bind:class="{
                        error: v$.editSelectedTagList.$error,
                        valid:
                          v$.editSelectedTagList.$dirty &&
                          !v$.editSelectedTagList.$invalid,
                      }">
                      <template v-slot:chip="{ props, item }">
                        <v-chip v-bind="props" :text="item.raw.tag_name"></v-chip>
                      </template>
                      <template v-slot:item="{ props, item }">
                        <v-list-item v-bind="props" :title="item?.raw?.tag_name"></v-list-item>
                      </template>
                    </v-autocomplete>

                    <span class="error-show" v-for="error in v$.editSelectedTagList.$errors" :key="error.$uid">{{
                      error.$message
                    }}</span>
                  </div>

                  <div class="sub-btn" id="editquestion_submitbtn">
                    <button type="submit" id="type" color="blue-darken-1" variant="text">
                      Submit
                    </button>
                  </div>
                  </div>
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

  validations() {
    return {
      edTitle: {
        required: helpers.withMessage("Question title is required", required),
        maxLength: helpers.withMessage(
          "Maximum length allowed is 100 characters",
          maxLength(100)
        ),
      },
      edDescription: {
        required: helpers.withMessage(
          "Question description is required",
          required
        ),
        maxLength: helpers.withMessage(
          "Maximum length allowed is 4096 characters",
          maxLength(4103)
        ),
        $autoDirty: true,
      },
      editSelectedTagList: {
        required: helpers.withMessage("Tag is required", required),
        maxLength: helpers.withMessage(
          "You can select atmost 5 tags",
          maxLength(5)
        ),
        $autoDirty: true,
      },
    };
  },
  name: "edit-question",
  components: {
    Loader
  },
  data() {
    return {
      isLoading: true,
      initialTitle: '',//var in which initial title being stored to check whether the title has been changed to show the error message showing similar  question exist
      edTitle: "",
      edDescription: "",
      editSelectedTagList: [],
      tagFlag: false,
      v$: useVuelidate(),
      flag: undefined,
      userlist: undefined,
      searchFlag: undefined,
      searchResult: {},
      Qtitle: "",
      notifications: "",
      userType: '',
      nameRules: [
        (v) => !!v || "Question is required",
        (v) =>
          (v && v.length <= 200) || "Title must be less than 200 characters",
      ],
      QdescRules: [
        (v) => !!v || "Question is required",
        (v) =>
          (v && v.length >= 10) ||
          "Description must be greater than 10 characters",
      ],
      Qdesc: "",
      search: "",
      TagList: "",
      tagListForEdit: [],
      down: false,
      list: undefined,
      User: {},
      Tagid: [],
      editor: ClassicEditor,
      editorConfig: {
        toolbar: [
          "heading",
          "|",
          "bold",
          "italic",
          "bulletedList",
          "numberedList",
          "|",
          "undo",
          "redo",
        ],
      },
    };

  },
  async beforeMount() {
   await this.getUser();
    await this.editQuestion();
    http
      .get("/user/notification")
      .then((response) => {
        this.notifications = response.data;
        this.isLoading=false;
      })
      .catch(function (error) {
        throw error;
      });
  },
  setup() {
    const toast = useToast();
    return { toast };
  },
  methods: {
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
    async getUser() {
      http.get("/getuser").then((response) => {
        this.User = response.data.user;
        this.userType = response.data.user.type;
      });
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
    async getAlltags() {
      await http
        .get("/tags")
        .then((response) => {
          this.TagList = response.data;
        })
        .catch(function (error) {
          throw error;
        });
      console.log("hiiiiiiiiiiii", this.TagList);
    },
    navToProfile() {
      this.$router.push('/profile');
    },
    async editQuestion() {
      let questionId = this.getQuestionId();
      await this.getTagForEdit();
      http
        .get("/question/view/" + questionId)
        .then((response) => {
          this.edTitle = response.data.question.title;
          this.initialTitle = this.edTitle;
          this.edDescription = response.data.question.description;
          this.edTagList = response.data.question_tags;
          this.editSelectedTagList = response.data.question_tags;
        })

        .catch(function (error) {
          throw error;
        });
    },
    async editQuestionSubmit() {
      this.v$.$validate();
      let edQuestionId = this.getQuestionId();
      let edtitle = this.edTitle.replace(/  +/g, ' ');
      let eddescription = this.edDescription.replace(/  +/g, ' ');
      const bodyParameters = {
        title: edtitle,
        description: eddescription,
        tag_id: JSON.parse(JSON.stringify(this.editSelectedTagList)),
      };

      this.tagFlag = false;
      if (
        this.v$.edTitle.$error ||
        this.v$.edDescription.$error ||
        this.v$.editSelectedTagList.$error
      ) {
        console.log();
      } else {
        http
          .put("/question/" + edQuestionId, bodyParameters)
          .then(() => {
            this.dialog_qstn = false;
            this.dialog_delete = false;
            this.toast.success("Question edited successfully", {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
            this.v$.$reset();
            history.back();
          })
          .catch((error) => {
            let msg = "Unable to process !!" + error.response.data.message;
            this.toast.error(msg, {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
            this.v$.$reset();
          });
      }
    },
    getQuestionId() {
      return this.$route.params.questionId;
    },
    // get taglist for edit
    async getTagForEdit() {


      let id = this.getQuestionId();
      let response = await http
        .get("/tag/view/" + id)
      this.tagListForEdit = response.data;
    },
    remove(item) {
      const index = this.editSelectedTagList.indexOf(item.tag_name);
      if (index >= 0) this.editSelectedTagList.splice(index, 1);

    },
  },
};
</script>

<style>
@import url("EditQuestion.css");
</style>