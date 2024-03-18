<template>
  <div id="body" @click="closeMOdal()">
    <div class="container">
      <div class="main">
        <div class="main-body-Add">
          <div class="main-content-Add">
            <div class="contentAddQuestion-Add">
              <form
                action=""
                @submit.prevent="QuestionDetails()"
                id="QuestionAddFrom"
              >
                <div class="qtitle">
                  <label for=""
                    >Question title<span class="mandotory">*</span></label
                  >
                  <v-text-field
                    class="question"
                    id="textExample"
                    type="text"
                    name="Qtitle"
                    v-model.trim="Qtitle"
                    placeholder="Question"
                    style="border: none"
                    :counter="100"
                    label="Enter Question title"
                    @blur="v$.Qtitle.$touch()"
                    @keydown="v$.Qtitle.$touch()"
                    @keyup="searchTitle()"
                    v-bind:class="{
                      error: v$.Qtitle.$error,
                      valid: v$.Qtitle.$dirty && !v$.Qtitle.$invalid,
                    }"
                    autocomplete="off"
                  />
                  <span
                    class="error-show-questionaddd"
                    style="color: red; padding-top: 1%"
                    v-for="error in v$.Qtitle.$errors"
                    :key="error.$uid"
                    >{{ error.$message }}</span
                  >
                 
                  <div class="spacing-div"></div>
                  <div class="titleSuggestionDiv" v-if="titleshow">
                    <div class="showSuggestion">
                      <p class="suggestion">
                        Do any of these posts matches your question ?
                      </p>
                    </div>
                    <div class="suggestionContainer">
                      <v-table>
                        <tbody>
                          <tr
                            v-for="question in searchResult"
                            :key="question.id"
                          >
                            <td
                              @click="viewDetails(question.id)"
                              style="cursor: pointer; color: rgb(85, 86, 116)"
                            >
                              {{ question.title }}
                            </td>
                          </tr>
                        </tbody>
                      </v-table>
                    </div>
                  </div>
                </div>
                <div id="tg">
                  <label for=""
                    >Question Description<span class="mandotory">*</span></label
                  >
                  <div class="ckEditormedia">
                    <ckeditor
                      :editor="editor"
                      v-model="Qdesc"
                      :config="editorConfig"
                    ></ckeditor>
                  </div>
                  <span
                    class="error-show-questionaddd"
                    style="color: red; position: absolute; padding-top: 1%;    margin-top: 13px;"
                    v-for="error in v$.Qdesc.$errors"
                    :key="error.$uid"
                    >{{ error.$message }}</span
                  ><br>

                  <br /><br />
                  <div class="autocflex">
                    <div class="autoc">
                      <label for=""
                        >Select Tag<span class="mandotory">*</span></label
                      >
                      <v-autocomplete
                        v-model="Tagid"
                        :items="TagList"
                        chips
                        closable-chips
                        color="blue-grey-lighten-2"
                        item-title="tag_name"
                        item-value="id"
                        multiple
                        variant="filled"
                        :menu-props="{ maxHeight: 400 }"
                        @change="Tagid.$touch()"
                        v-bind:class="{
                          error: v$.Tagid.$error,
                          valid: v$.Tagid.$dirty && !v$.Tagid.$invalid,
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
                        v-for="error in v$.Tagid.$errors"
                        :key="error.$uid"
                        >{{ error.$message }}</span
                      >
                    </div>

                    <div class="sub-btn">
                      <button
                        type="submit"
                        id="submitBtn"
                        color="blue-darken-1"
                        variant="text"
                      >
                        Submit
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!--  -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import Swal from "sweetalert2";
import { useToast, POSITION } from "vue-toastification";
import { useVuelidate } from "@vuelidate/core";
import { required, maxLength, helpers } from "@vuelidate/validators";
import http from "../../../http-common";

export default {
  validations() {
    return {
      Qtitle: {
        required: helpers.withMessage("Question Title is required", required),
        maxLength: helpers.withMessage(
          "Maximum length allowed is 100 characters",
          maxLength(100)
        ),
      },
      Qdesc: {
        required: helpers.withMessage(
          "Question Description is required",
          required
        ),
        maxLength: helpers.withMessage(
          "Maximum length allowed is 4096 characters",
          maxLength(4103)
        ),
        $autoDirty: true,
      },
      Tagid: {
        required: helpers.withMessage("Tag is required", required),
        maxLength: helpers.withMessage(
          "You can select atmost 5 tags",
          maxLength(5)
        ),
        $autoDirty: true,
      },
    };
  },
  data() {
    return {
      tagFlag: false,
      v$: useVuelidate(),
      flag: undefined,
      userlist: undefined,
      searchFlag: undefined,
      searchResult: {},
      Qtitle: "",
      userType: "",
      User: {},
      notifications: "",
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
      down: false,
      list: undefined,
      titleshow: undefined,
      Tagid: [],
      editor: ClassicEditor,
      showConfirm: false,
      editorData: "<p>Content of the editor.</p>",
      editorConfig: {
        toolbar: [
          "heading",
          "|",
          "bold",
          "italic",
          "link",
          "bulletedList",
          "numberedList",
          "|",
          "undo",
          "redo",
        ],
        table: {
          toolbar: ["tableColumn", "tableRow", "mergeTableCells"],
        },
        mobile: {
          toolbar: [
            "heading",
            "bold",
            "italic",
            "link",
            "bulletedList",
            "numberedList",
            "|",
            "undo",
            "redo",
          ],
        },
      },
    };
  },
  setup() {
    const toast = useToast();
    return { toast };
  },
  created() {
    this.getUser();
  },
  mounted() {
    this.getAlltags();
    http
      .get("/user/notification")
      .then((response) => {
        this.notifications = response.data;
        console.log(this.notifications.length);
      })
      .catch(function (error) {
        throw error;
      });
  },
  methods: {
    async QuestionDetails() {
      this.v$.$validate();
      this.tagFlag = false;
      if (
        this.v$.Qtitle.$error ||
        this.v$.Qdesc.$error ||
        this.v$.Tagid.$error
      ) {
        console.log();
      } else {
        if (this.searchResult.length != 0) {
          Swal.fire({
  title: 'Similar Question Exist!',
  text: "Are you sure want to proceed?",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Submit',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {
    this.postQuestion();
  }});
        } else {
          document.getElementById("submitBtn").disabled = true;
          let title1 = this.Qtitle.split(" ").filter(Boolean).join(" ");
          let description1 = this.Qdesc.split(" ").filter(Boolean).join(" ");
          const bodyParameters = {
            title: title1,
            description: description1,
            tag_id: JSON.parse(JSON.stringify(this.Tagid)),
          };
          http
            .post("/question", bodyParameters)
            .then(() => {
              document.getElementById("submitBtn").disabled = false;
              this.toast.success("Question added successfully", {
                position: POSITION.TOP_CENTER,
                timeout: 1700,
              });
              this.$router.push("question");
            })
            .catch((error) => {
              let msg = "Unable to process !!" + error.response.data.message;
              this.toast.error(msg, {
                position: POSITION.TOP_CENTER,
                timeout: 1700,
              });
            });

          document.getElementById("submitBtn").disabled = false;
        }
      }
    },
    getUser() {
      http.get("/getuser").then((response) => {
        this.User = response.data.user;
        this.userType = response.data.user.type;
      });
    },
    postQuestion() {
      document.getElementById("submitBtn").disabled = true;
      console.log("===============>>");
      let title1 = this.Qtitle.split(" ").filter(Boolean).join(" ");
      let description1 = this.Qdesc.split(" ").filter(Boolean).join(" ");
      const bodyParameters = {
        title: title1,
        description: description1,
        tag_id: JSON.parse(JSON.stringify(this.Tagid)),
      };
      http
        .post("/question", bodyParameters)
        .then(() => {
          document.getElementById("submitBtn").disabled = false;
          this.toast.success("Question added successfully", {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });

          document.getElementById("submitBtn").disabled = false;
          this.$router.push("question");
        })
        .catch((error) => {
          let msg = "Unable to process !!" + error.response.data.message;
          this.toast.error(msg, {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
        });
    },
    navToProfile() {
      this.$router.push("/profile");
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
    // Search tags
    search_tag() {
      if (document.getElementById("searchbar") !== null) {
        let input = document.getElementById("searchbar").value;

        input = input.toLowerCase();
        let x = document.getElementsByClassName("item1");

        for (let i = 0; i < x.length; i++) {
          if (!x[i].innerHTML.toLowerCase().includes(input)) {
            x[i].style.display = "none";
          } else {
            x[i].style.display = "list-item";
          }
        }
      }
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
      http.post("/questionviews/" + Qid, {}).then(() => {});
      this.$router.push("/questionView/" + Qid);
    },
    deleteNotification() {
      http.put("user/notification/delete").then(() => {
        window.location.reload();
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
      console.log("hiiiiiiiiiiii", this.TagList);
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
    searchTitle() {
      let searchText = this.Qtitle;
      if (searchText == "") {
        this.titleshow = false;
      } else {
        let regex = /\\/g;
        searchText = searchText.replace(regex, "\\\\");
        searchText = encodeURIComponent(searchText);
        http
          .get("suggestQuestion?data=" + searchText)
          .then((response) => {
            this.searchResult = response.data;
            if (this.searchResult.length != 0) {
              this.titleshow = true;
            } else {
              this.titleshow = false;
            }
          })
          .catch(function (error) {
            throw error;
          });
      }
    },
    viewDetails(id) {
      http.post("/questionviews/" + id, {}).then(() => {});
      let route = this.$router.resolve({ path: "/questionView/" + id });
      window.open(route.href, "_blank");
    },
  },
};
</script>

<style>
@import url("Questionadd.css");
</style>