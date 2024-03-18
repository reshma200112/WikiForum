<template>
  <div id="body" @click="closeMOdal()">
    <div v-if="isLoading">
      <Loader />
    </div>
    <div v-else class="container">
      <section class="main">
        <div class="backBtn">
          <v-btn router-link to="/question" class="backBtnToQuestionList" color="" dark>
            <v-icon dark left> mdi-arrow-left </v-icon>Back
          </v-btn>
        </div>
        <div class="main-body" id="questionDetailViewDiv">
          <div class="main-content">
            <h1 class="question-title" id="questionDetailViewDiv">
              {{ questionTitle }} <span class="nonstatus" v-if="questionStatus==0">[Deleted]</span><span class="nonstatus" v-if="questionStatus==2">[Closed]</span>
            </h1>

            <div v-html="questionDescription" id="QuestionDiv"></div>
                <!-- tag display  -->
            
                <div id="tagDisplayQuestionPage">
              <div v-for="tag in questionTags" :key="tag.id" class="tagDIvQ">
                <p class="TagDisplay" id="tagdisplayinqViewPage">
                  <strong class="pop-color">#</strong>
                  
                  
                  {{ tag.tag_name }}
                </p>
              </div>
            </div>
            <!--  -->
            <div class="question_historydiv" @click="showHistory()">
<em class="fa fa-history"></em><span>Show activity history</span>
</div>
            <br />
            <div class="postedUserDetails">
              <img
                v-if="Profilepic != null"
              class="userImage"
                :src="Profilepic"
                referrerpolicy="no-referrer"
                alt=""
                id="user-profile-image"/>
              <img
                v-else
                v-bind:src="require('@/assets/avatar.png')"
                alt=""
                class="userImage"
                id="user-profile-image"/>
              <div
                class="user">
              <p>Posted on
                      {{ moment(QuestionpostedDate).calendar() }} by
                      {{ questionPostedUser }}
                    </p>
                <p id="Questioncomments"  class="userPnts">Points:{{ Userpoint }}</p>
             
          
              </div>
            </div>

            <div class="question-crud-div">
              <button @click="editQuestion()" v-if="questionUser === currentUser.id" >
                <em class="fas fa-edit">Edit</em>
              </button>
              <button @click="deleteQuestion()" v-if="questionUser === currentUser.id&&questionStatus!=0" >
                <em class="fas fa-trash">Delete</em>
              </button>
            </div>
            <!-- Edit question modal end -->
            <v-snackbar
              v-model="snackbar"
              color="warning"
              :timeout="1000"
              multi-line
              style="margin-top: -20%"
            >
              No comments posted yet..!!
            </v-snackbar>
            <!-- add comment to question -->

            <!-- add comment to question modal start -->
            <div class="ft-modal-comment" id="addQ-modal">
              <div class="ft-modal-content-comment">
                <div class="ft-modal-body-comment">
                  <div class="comment-avatar">
                    <img
                      v-if="currentuserpic != null"
                    class="userImage"
                      :src="currentuserpic"
                      referrerpolicy="no-referrer"
                      alt=""
                      id="user-profile-image"/>
                    <img
                      v-else
                      v-bind:src="require('@/assets/avatar.png')"
                      alt=""
                      class="userImage"
                      id="user-profile-image"/>
                  </div>

                  <form class="form" name="form" @submit.prevent="AddComment()">
                    <textarea
                      id="commentText"
                      class="commentInput"
                      placeholder="Add comment..."
                      v-model="addcomment"
                    ></textarea>
                    <v-card-actions class="commentfooter">
                      <v-btn
                        color="blue darken-1"
                        text
                        @click="modalCloseAddCommentQuestion()"
                      >
                        Close
                      </v-btn>
                      <div>
                        <v-btn id="buttonaddquestioncomment"
                          type="submit"
                          value="Add Comment"
                          color="blue darken-1"
                          text
                        >
                          Add Comment
                        </v-btn>
                      </div>
                    </v-card-actions>
                  </form>
                  <hr />
                </div>
              </div>
            </div>
           

            <!-- Edit comment new modal -->
            <div class="ft-modal-comment" id="edit-modal">
              <div class="ft-modal-content-comment">
                <div class="ft-modal-body-comment">
                  <div class="comment-avatar" >
                    <img
                      v-if="currentuserpic != null"
                      class="userImage"
                      :src="currentuserpic"
                      referrerpolicy="no-referrer"
                      alt=""
                      id="user-profile-image" />
                    <img
                      v-else
                      v-bind:src="require('@/assets/avatar.png')"
                      alt=""
                      class="userImage"
                      id="user-profile-image"/>
                  </div>

                  <form
                    class="form"
                    name="form"
                    @submit.prevent="setEditComment()"
                  >
                    <textarea
                      id="commentEdit"
                      class="commentInput"
                      placeholder="Add comment..."
                      style="max-height: 650px"
                      v-model="edComment"
                    ></textarea>
                    <v-card-actions class="commentfooter">
                      <v-btn
                        color="blue darken-1"
                        text
                        @click="modalCloseEdit()"
                      >
                        Close
                      </v-btn>
                      <div>
                        <v-btn
                          type="submit"
                          value="Edit Comment"
                          color="blue darken-1"
                          text
                        >
                          Edit Comment
                        </v-btn>
                      </div>
                    </v-card-actions>
                  </form>
                  <hr />
                </div>
              </div>
            </div>
            <br>

            <div >
            <div v-if="this.pageBeginning != null">
              <h4 class="noComments">Comments</h4>
            </div>
            <div v-if="this.pageBeginning == null" class="nocomments-div">
              <h4 class="noComments">
                    No comments available
              </h4>
            </div>
            </div>
            <div
              class="comment-box-question" 
              :key="item.id"
              v-for="item in listt"
            >
            
              <div class="comments">
                <div class="comment">
                  <div class="comment-avatar-quetsion">
                    <div class="comment-img">
                      <div>
                        <img
                          v-if="item.profile_picture != null"
                          :src="item.profile_picture"
                         class="userImage"
                          referrerpolicy="no-referrer"
                          alt=""
                          id="user-profile-image"/>
                        <img
                          v-else
                          v-bind:src="require('@/assets/avatar.png')"
                          alt=""
                          class="userImage"
                          id="user-profile-image"/>
                      </div>
                    </div>
                  </div>
<div >
                  {{ item.comment }}</div>

                  <div v-if="item.created_at === item.updated_at">
                    <p>
                      posted on
                      {{ moment(item.created_at).calendar() }} by
                      {{ item.user_name }}
                    </p>
                  </div>
                  <div v-if="item.created_at !== item.updated_at">
                    <p>
                      updated on
                      {{ moment(item.updated_at).calendar() }} by
                      {{ item.user_name }}
                    </p>
                  </div>

                  <div v-if="item.user_id === currentUser.id">
                    <button @click="editComment(item.id)">
                      <em class="fas fa-edit">Edit</em>
                    </button>
                  </div>
                  <div
                    v-if="
                      item.user_id === currentUser.id 
                    "
                  >
                    <button @click="delComment(item.id)">
                      <i class="fa fa-trash" aria-hidden="true">Delete</i>
                    </button>
                  </div>
                </div>
                <div class="comment-footer"></div>
                <div class="comment-footer"></div>
              </div>
            </div>
            <div v-if="this.last_page != 1">
              <v-btn class="loadMore"
              
                @click="PaginatedQuestionComments()"
                >Load More</v-btn
              >
            </div> 
    <br><br>
    <div></div><div >
    <textarea placeholder="Enter your comments..." rows="20"  v-on:click="openAddQ()" v-model="addcomment" 
         name="comment[text]" id="comment_text" cols="40" class="ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"></textarea>
<br>
<div id="newAnswers">
    <v-btn    v-if="User.points >= 25" class="addCommentBtn"    v-on:click="AddComment()" 
    depressed color="blue darken-1"     :disabled="pausedFor2Seconds"
><span class="mdi mdi-plus"></span>Add</v-btn>


                <v-btn
              v-else 
              type="submit"
              id="AddCommmentButton"
         
            >
              <v-tooltip activator="parent" location="right" :height="50"
                >You must have atleast 25 points to add comment</v-tooltip
              >
              <fa icon="plus" style="margin-right: 10px"></fa>Add
    </v-btn> </div></div>
   
<br>
            <div class="heading" id="newAnswers">
           
                <div v-if="this.answerlength == 0">
              <h1 class="forColor"> No answers available</h1>
            </div>
            <div v-if="this.answerlength != 0">
              <h1 class="forColor" >Answers</h1>
            </div>
 
<div class="filterS" v-if="this.answerlength!=0">
  <button id="recentlyadded" class="btnSort" @click="onSortChange(2)">Like <v-tooltip activator="parent" location="bottom"
              :open-delay="500">Filter based on likes</v-tooltip
              ></button>
      
            <button id="like" class="btnSort" @click="onSortChange(1)">Recently added <v-tooltip activator="parent" location="bottom"
              :open-delay="500">Filter based on recently added</v-tooltip
              ></button></div>
            
              <br />
            </div>

            <div :key="item.id" v-for="(item, index) in answers" class="div-verify">
              <div elevation="6" outlined shaped class="v1" id="answerCard">
                <!--  -->
                <div class="answer-topDiv">
                <div class="answer-user" >
                  <img class="userImage"
                    v-if="item.answer_added_user_picture != null"
                    :src="item.answer_added_user_picture"
                    
                    referrerpolicy="no-referrer"
                    alt=""
                    id="user-profile-image"/>
                  <img class="userImage"
                    v-else
                    v-bind:src="require('@/assets/avatar.png')"
                    alt=""
                  
                    id="user-profile-image"/>
                  <div class="ans-name-time" >
                    <small class="answer-user-name">
                      {{ item.answer_added_user }}</small
                    >
                    <small class="answer-time">
                      {{ moment(item.created_at).calendar() }}
                    </small>
                  </div>
                </div>
                <div class="answerVerification">
                <em v-if="item.verified == 1" class="fa fa-check"
                  ></em>
                
                <div
                  v-if="
                    questionVerifyFlag != 1 && currentUser.id == questionUser
                  " v-show="questionStatus==1"
                >
                  <form action="" @submit.prevent="verifyAns(item.id)">
              
                    <button type="submit" class="btn-verify">Verify</button>
                  </form>
                </div>
                </div>
                </div>
                <!--  -->
                <div class="AnswerDiv" v-html="item.answer"></div>
         
                
                <v-row justify="left" id="EditAnswerright">
                  <div v-if="item.user_id == currentUser.id" class="answer-crud-div">
                    <button @click="editAnswerr(item.id)" style="width:fit-content">
                      <fa icon="pencil"></fa>
                  </button>
                  <button @click="deleteAnswer(item.id)" style="width:fit-content">
                      <fa icon="trash"></fa>
                  </button>
                  </div>
                </v-row>
                <div class="user-info" style="max-width:200px">
                  <div class="user__comment" ></div>
                  <div class="user__comment__reaction"  v-bind:id="`Answercomments${item.id}`">
                    <fa
                      v-if="item.like == 1"
                      icon="thumbs-up"
                      style="color: green"
                      class="like"
                      id="vote"
                      @click="UpvoteDown(item.id, 1)"
                    >
                    </fa>
                    <fa
                      v-else
                      icon="thumbs-up"
                      class="like"
                      id="vote"
                      @click="UpvoteDown(item.id, 1)"
                    >
                    </fa>
                 
                    <small>{{ item.upvote }}</small>
                  
                    <fa
                      v-if="item.like == 0"
                      icon="thumbs-down"
                      style="color: red"
                      class="dislike"
                      id="vote"
                      @click="UpvoteDown(item.id, 0)"
                    ></fa>
                    <fa
                      v-else
                      style="color: lightslategray"
                      icon="thumbs-down"
                      class="dislike"
                      id="vote"
                      @click="UpvoteDown(item.id, 0)"
                    ></fa>
                    <small>{{ item.downvote }}</small>
                    <div class="answer-history" @click="showHistory()">
<em class="fa fa-history"></em><v-tooltip activator="parent" location="right" 
                >Show activity history</v-tooltip
              >
</div>
                  </div>
                 
                </div>
              </div>
              <br />
        

            <div v-if="item.comment.data.length != 0" >
                <h4 class="H4-comments">Comments</h4>
              </div>
              <div v-else>
                <h4 class="H4-nocomments">
                  No comments available
                </h4>
              </div>

              <div
                class="comment-box-question" 
                :key="i.id"
                v-for="i in item.comment.data"
              >
              
      
                <div class="comments" >
                  <div class="comment">
                    <div class="comment-avatar-quetsion">
                      <div class="comment-img">
                        <div>
                          <img class="userImage"
                            v-if="i.profile_picture != null"
                            :src="i.profile_picture"
                          
                            referrerpolicy="no-referrer"
                            alt=""
                            id="user-profile-image"/>
                          <img class="userImage"
                            v-else
                            v-bind:src="require('@/assets/avatar.png')"
                            alt=""
                           
                          id="user-profile-image"/>
                        </div>
                      </div>
                    </div>

                    {{ i.comment }}

                    <div v-if="i.created_at === i.updated_at">
                      <p>
                        posted on
                        {{ moment(i.created_at).calendar() }} by
                        {{ i.user_name }}
                      </p>
                    </div>
                    <div v-if="i.created_at !== i.updated_at">
                      <p>
                        updated on
                        {{ moment(i.updated_at).calendar() }} by
                        {{ i.user_name }}
                      </p>
                    </div>

                    <div v-if="i.user_id === currentUser.id">
                      <button @click="editComment(i.id)">
                        <em class="fas fa-edit">Edit</em>
                      </button>
                    </div>
                    <div
                      v-if="
                        i.user_id === currentUser.id
                      "
                    >
                      <button @click="delComment(i.id)">
                        <i class="fa fa-trash" aria-hidden="true">Delete</i>
                      </button>
                    </div>
                  </div>
                  <div class="comment-footer"></div>
                  <div class="comment-footer"></div>
                </div>
              </div>

              <div v-if="item.comment.last_page != 1">
                <div>
                  <div
                    class="comment-box-question"
                    :key="i.id"
                    v-for="i in AnswerPaginatedCommentss"
                  >
                    {{i}}
                    <div class="comments" >
                      <div class="comment">
                        <div class="comment-avatar-quetsion">
                          <div class="comment-img">
                            <div>
                              <img class="userImage"
                                v-if="i.image != null"
                                :src="i.image"
                             
                                referrerpolicy="no-referrer"
                                alt=""
                                id="user-profile-image"/>
                              <img class="userImage"
                                v-else
                                v-bind:src="require('@/assets/avatar.png')"
                                alt=""
                           
                              id="user-profile-image"/>
                            </div>
                          </div>
                        </div>

                        {{ i.comment }}

                        <div v-if="i.created_at === i.updated_at">
                          <p>
                            posted on
                            {{ moment(i.created_at).calendar() }} by
                            {{ i.name }}
                          </p>
                        </div>
                        <div v-if="i.created_at !== i.updated_at">
                          <p>
                            updated on
                            {{ moment(i.updated_at).calendar() }} by
                            {{ i.name }}
                          </p>
                        </div>

                        <div v-if="i.user_id === currentUser.id">
                          <button @click="editComment(i.id)">
                            <em class="fas fa-edit">Edit</em>
                          </button>
                        </div>
                        <div
                          v-if="
                            i.user_id === currentUser.id 
                          "
                        >
                          <button @click="delComment(i.id)">
                            <i class="fa fa-trash" aria-hidden="true">Delete</i>
                          </button>
                        </div>
                      </div>
                      <div class="comment-footer"></div>
                      <div class="comment-footer"></div>
                    </div>
                  </div>
                
                  <div >
                
                    <v-btn class="loadMore" v-bind:id="`loadMore${item.id}`"
                      
                      @click="paginatedAnswerComment(item.id)" 
                      >Load More</v-btn
                    >
                  </div>
                </div>
              </div>
<br><br>

<textarea placeholder="Enter your comments..." rows="20" v-on:click="openAddA(item.id)" v-bind:id=item.id v-model="answersComments[index]" 
 name="comment[text]"  cols="40" class="ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"></textarea>

    <br>
    <v-btn  class="addCommentBtn"    v-if="User.points >= 25"    v-on:click="AddCommentAnswer(index)"  :disabled="pausedFor2Seconds"
    depressed color="blue darken-1"
     
><span class="mdi mdi-plus"></span>Add</v-btn>

                <v-btn
              v-else
              type="submit"
              id="AddCommmentButton"
            >
              <v-tooltip activator="parent" location="right" :height="50"
                >You must have atleast 25 points to add comment</v-tooltip
              >
              <fa icon="plus" style="margin-right: 10px"></fa>Add
                </v-btn> 


     

              
              <div
                v-bind:id="`viewanswercomments${item.id}`"
                style="display: none"
              >
                <div :key="itemm.id" v-for="itemm in answeroncomment.comment" >
                  <div
                    class="comment"
                    style="width: 923px"
                    width="923px"
                    v-if="item.id == itemm.answer_id"
                  >
              
                    <div class="comment-avatar">
                      <div class="comment-img">
                        <img class="userImage"
                          v-if="itemm.image != null"
                          :src="itemm.image"
         
                          referrerpolicy="no-referrer"
                          alt=""
                          id="user-profile-image" />
                        <img class="userImage"
                          v-else
                          v-bind:src="require('@/assets/avatar.png')"
                          alt=""
                          id="user-profile-image"  />
                      </div>
                    </div>
                    <div class="comment-box" >
                      {{ itemm.comment }}
                      <div v-if="itemm.created_at === itemm.updated_at">
                        <p>
                          posted on
                          {{ moment(itemm.created_at).calendar() }} by
                          {{ itemm.name }}
                        </p>
                      </div>
                      <div v-if="itemm.created_at !== itemm.updated_at">
                        <p>
                          updated on
                          {{ moment(itemm.updated_at).calendar() }} by
                          {{ itemm.name }}
                        </p>
                      </div>

                      <div v-if="itemm.user_id === currentUser.id">
                        <button @click="editComment(itemm.id)">
                          <i class="fas fa-edit">Edit</i>
                        </button>
                      </div>
                      <!-- TO CHECK -->
                      <div
                        v-if="
                          itemm.user_id === currentUser.id
                        "
                      >
                        <button @click="delComment(itemm.id)">
                          <i class="fa fa-trash" aria-hidden="true">Delete</i>
                        </button>
                      </div>
                    </div>
                    <div class="comment-footer"></div>
                  </div>
                </div>
              </div>
         
            </div>
            <form action="" name="addAnswer" @submit.prevent="addAnswer()" id="addAnswer-media" v-if="questionStatus==1">
              <label for="answer"><strong>Answer this question</strong></label>
              <hr />
              <div class="ckEditormedia-answer" >
              <ckeditor
                :editor="editor"
                v-model="txtAnswer"
                :config="editorConfig"
              ></ckeditor>
              </div>
              <span
                class="error-show"
                v-for="error in v$.txtAnswer.$errors"
                :key="error.$uid"
                >{{ error.$message }}</span
              >
              <hr />
              <div class="form_field checkbox" id="checkBox">
                <input
                  type="checkbox"
                  name="acceptCondition"
                  value="accept"
                  v-model="accept"
                />&nbsp;&nbsp;Make sure that this answer would not hurt
                anyone&nbsp;&nbsp;&nbsp;
              </div>
              <div v-if="txtAnswer == ''">
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
           

          <div class="sidebar">
            <div class="popular_creators">
              <h4>Popular Users</h4>
              <div
                class="row-userdisplay"
                v-for="userdetails in popularUsers"
                :key="userdetails.id"
              >
                <div class="imguser">
                  <img
                    v-if="userdetails.profile_picture != null"
                    :src="userdetails.profile_picture"
                    referrerpolicy="no-referrer"
                    alt=""
                    id="user-profile-image"  />
                  <img
                    v-else
                    v-bind:src="require('@/assets/avatar.png')"
                    alt=""
                    id="user-profile-image"  />
                </div>
                <div class="usernamedashboard">
                  {{ userdetails.name }}
                </div>
              </div>
            </div>
            <div class="popular_tags">
              <h4>Popular Tags</h4>
              <div class="tag-1">
                <div
                  class="his"
                  :key="item.id"
                  v-for="item in popularTags"
                  @click="viewTagQuestion(item.id)"
                >
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
import Swal from 'sweetalert2';
import Loader from '../../Loader.vue';
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import http from "../../../http-common";
import { useToast, POSITION } from "vue-toastification";
import { useVuelidate } from "@vuelidate/core";
import { required, helpers, maxLength } from "@vuelidate/validators";
let moment = require("moment");
export default {
  name: "QuestionView",

  setup() {
    const toast = useToast();
    return { toast };
  },
  validations() {
    return {
      edTitle: {
        required: helpers.withMessage("Question title is required", required),
        maxLength: helpers.withMessage(
          "Maximum length allowed is 150 characters",
          maxLength(150)
        ),
      },
      edDescription: {
        required: helpers.withMessage(
          "Question description is required",
          required
        ),
        maxLength: helpers.withMessage(
          "Maximum length allowed is 4096 characters",
          maxLength(4096)
        ),
      },
      edComment: {
        required: helpers.withMessage("Comment is required", required),
      },
      edAnswer: {
        required: helpers.withMessage("Answer is required", required),
      },
      editSelectedTagList: {
        required: helpers.withMessage("Tag is required", required),
        maxLength: helpers.withMessage(
          "You can select atmost 3 tags",
          maxLength(3)
        ),
        $autoDirty: true,
      },
      txtAnswer: {
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
      questionTags:{},
      questionStatus:null,
      deleteCountFlag:null,
      QuestionpostedDate:"",
      pausedFor2Seconds:false,
      isLoading: true,
      answerlength: '',
      userType: "",
      ansid:'',
      snackbar: false,
      v$: useVuelidate(),
      paginatedComment: undefined,
      notifications: "",
      moment: moment,
      userlist: undefined,
      questionUser: undefined,
      currentUser: undefined,
      currentuserpic: undefined,
      list: {},
      answeroncomment: {},
      dialog_qstn: false,
      ansssid: {},
      title: undefined,
      bio: "",
      dialog: false,
      dialogcomment: false,
      // answers: undefined,
      comments: undefined,
      listansid: undefined,
      txtAnswer: "",
      vflag: undefined,
      accept: "",
      dialog1: false,
      edComment: undefined,
      delComId: undefined,
      edComId: undefined,
      edAnswer: undefined,
      edAnswerId: undefined,
      AnswerPaginatedCommentss: undefined,
      listt: {},
      element: {},
      edTitle: "",
      edDescription: "",
      edQuestionId: undefined,
      edTagList: undefined,
      Tagid: [1, 2, 2, 3],
      TagList: undefined,
      dialog_delete: undefined,
      tagListForEdit: undefined,
      editSelectedTagList: [],
      selectDropdownFlag: false,
      questionTitle: "",
      questionPostedUser: "",
      Userpoint: "",
      dialog_answer: false,
      Profilepic: "",
      questionDescription: "",
      answers:undefined,
      addcommentA:"",
      answersComments:[
    
      ],
    addcomment: "",
      User: {},
      questionVerifyFlag: 0,
      editCommentOnAnswerId: undefined,
      disabled: false,
      timeout: null,
      popularUsers: "",
      popularTags: "",
      questionIdFromRoute: "",
      tagFlag: false,
      sideBarnav: false,
      perpage: "?perPage=",
      perrpage: "?perPage=",
      last_page: "",
      lastpageAnswercomment: "",
      numberrr: "",
      perpageenumber: 3,
      per_pagenumber: "",
      pageBeginning: "",
      QuestionAnswers: "",
      editor: ClassicEditor,
      pagination: {
        current_page: 1,
      },
   
      ansbtn: false,
     
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
      },
      dialog_verify: false,
      sorted:1
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
    async addAnswer() {
     

      let id = this.getQuestionId();
      this.v$.$validate();
      let answer1 = this.txtAnswer.split(" ").filter(Boolean).join(" ");
      const bodyParameters = {
        answer: answer1,
      };
      if (!this.v$.txtAnswer.$error) {
        await http
          .post("/answer/" + id, bodyParameters)
          .then(() => {
            this.accept = "";
            this.txtAnswer = "";
            this.toast.success("Answer added successfully", {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
            this.v$.$reset();
            this.getQuestionAnswers();
            this.addcommentA=""
            window.scroll({
 top: 430, 
 left: 0, 
 behavior: 'smooth' 
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
      this.onSortChange(1);
      if(this.sorted==1)
      {
        document.getElementById('like').style.backgroundColor="#dceff7";
        document.getElementById('recentlyadded').style.backgroundColor="white"; 
       }
      location.href=`#newAnswers`
    },
      
    async UpvoteDown(id, status) {
      const bodyParameters = {
        status: status,
      };
      await http
        .post("/add-vote/" + id, bodyParameters)

        .then(() => {
          this.getQuestionAnswers();
        })
        .catch((error) => {
          let msg = "Unable to process !!" + error.response.data.message;
          this.toast.error(msg, {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
        });
    },
     verifyAns(id)  {
      Swal.fire({
  title: 'Verify Answer!',
  text: "Are you sure want to verify?",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Verify',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {
          http
        .put("/answer/verify/" + id, {})
        .then(() => {
          this.toast.success("Answer verified successfully", {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
          this.getQuestionAnswers();
        })
        .catch((error) => {
          let msg = "Unable to process !!" + error.response.data.message;
          this.toast.error(msg, {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
        });
      }
    })
    },

    async AddComment() {
      document.getElementById("buttonaddquestioncomment").disabled = true;
      let id = this.getQuestionId();
      let comment1 = this.addcomment.replace(/ +/g, " ");
      const bodyParameters = {
        question_id: id,
        comment: comment1,
      };
      http
        .post("/comment", bodyParameters)
        .then((response) => {
      document.getElementById("buttonaddquestioncomment").disabled = false;
          
          this.addcomment = null;
          http.get("/commentsquestion/" + id).then((response) => {
            this.listt = response.data.comments.data;
             location.href = "#Questioncomments";
          });

          this.v$.$reset();
          this.toast.success("Comment added successfully!", {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
          this.modalCloseAddCommentQuestion();
               this.numberrr = this.numberrr-5;
          this.PaginatedQuestionComments();
          console.log(response);
        })
        .catch((error) => {
      document.getElementById("buttonaddquestioncomment").disabled = false;
      if(error.response.data?.message)
            { 
              this.toast.error(error.response.data?.message, {
                position: POSITION.TOP_CENTER,
                timeout: 1700,
              });}
              else if(error.response.data[0].comment[0])
              {  this.toast.error(error.response.data[0].comment[0], {
                position: POSITION.TOP_CENTER,
                timeout: 1700,
              }); }
            });
            this.pausedFor2Seconds = true;
    setTimeout(() => this.pausedFor2Seconds = false, 1000);
    },

    async AddCommentAnswer(index) {
      console.log(index);
      let comment1 = this.answersComments[index];
         if(this.addcommentA!='')
         {
          comment1=comment1.split(" ").filter(Boolean).join(" ");
         }
      const bodyParameters = {
        comment: comment1,
        answer_id: Number(localStorage.getItem("AnswerId")),
      };
      http
        .post("/comment", bodyParameters)
        .then((response) => {
          this.toast.success("Comment added successfully!", {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
          this.v$.$reset();
          let answer_id = response.data.comment.answer_id;
          http
            .get("/answer/comments/" + answer_id)
            .then(() => {
             
this.answersComments[index]=""
          let answer_id = response.data.comment.answer_id;
          this.paginatedAnswerComment(answer_id)
          location.href = "#Answercomments"
          if(answer_id){
          location.href = "#Answercomments" + answer_id;
          }

            })
        })
        .catch((error) => {
          if(error.response.data?.message)
            { 
              this.toast.error(error.response.data?.message, {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });}
            else if(error.response.data[0].comment[0])
            {  this.toast.error(error.response.data[0].comment[0], {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          }); }
           
        });
        this.pausedFor2Seconds = true;
    setTimeout(() => this.pausedFor2Seconds = false, 1000);
    },

    async ViewComment() {
      let id = this.getQuestionId();
      const bodyParameters = {
        question_id: id,
        comment: this.addcomment,
      };
      http
        .get("/commentsquestion/" + this.id, bodyParameters)
        .then()
        .catch(function (error) {
          throw error;
        });
    },
       onSortChange(sort) {
      this.sorted=sort;
      if(this.sorted==1)
      {
        document.getElementById('like').style.backgroundColor="#dceff7";
        document.getElementById('recentlyadded').style.backgroundColor="white"; 
       }
      if(this.sorted==2)
      { document.getElementById('recentlyadded').style.backgroundColor="#dceff7";
        document.getElementById('like').style.backgroundColor="white";
     
      }
      
           let id = this.getQuestionId();
      http.get("/answer/" + id + "?sort=" + this.sorted).then((response) => {
        this.questionTitle = response.data.Title;
        this.questionTags=response.data?.Tags;
        this.questionStatus=response?.data?.Question_status;
        console.log(response.data.Answers);
        this.QuestionAnswers = response.data.Answers;
        this.questionPostedUser = response.data.name;
        this.Profilepic = response.data.profile_picture;
        this.Userpoint = response.data.points;
        this.questionDescription = response.data.Description;
        this.questionUser = response.data.user_id;
        this.answers = response.data.Answers;
        for (let index = 0; index < this.answers.length; index++) {
          this.answersComments.push(
            {
              index:""
            }
          )
this.answersComments[index]=""

        }
        this.edQuestionId = response.data.Answers[0].question_id;
        this.ans = this.answers.answer;
        this.list = response.data;
        this.listansid = this.list.Answers.map((item) => item.id);
        this.questionVerifyFlag = this.list.Verified;
      });
    
      
    },

    async getQuestionAnswers() {
      let id = this.getQuestionId();
      http.get("/answer/" + id + "?sort=" + this.sorted).then((response) => {
        this.questionTitle = response.data.Title;
        this.questionTags=response.data?.Tags;
        this.questionStatus=response?.data?.Question_status;
        this.QuestionAnswers = response.data.Answers;
        this.questionPostedUser = response.data.name;
        this.QuestionpostedDate = response.data.date
        this.Profilepic = response.data.profile_picture;
        this.Userpoint = response.data.points;
        this.questionDescription = response.data.Description;
        this.questionUser = response.data.user_id;
        this.answers = response.data.Answers;
        
       
        this.answerlength = response.data.Answers.length
      
        this.edQuestionId = response.data.Answers[0].question_id;
        this.ans = this.answers.answer;
        this.list = response.data;
        this.listansid = this.list.Answers.map((item) => item.id);
        this.questionVerifyFlag = this.list.Verified;
      });
    },
    async paginatedAnswerComment(id) {
      await http
        .get("/answer/comments/" + id + "?perPage=" + this.perpageenumber)
        .then((response) => {
          for (const element of this.answers) {
            if (element.id == id) {
              element.comment.data =
                response.data.Answers.comment.data;
              break;
            }
          }

this.ansid = id

if(response.data.Answers.comment.to != response.data.Answers.comment.total){
  this.perpageenumber = response.data.Answers.comment.per_page;
}else{
  document.getElementById(`loadMore${id}`).style.display = "none";
}

          this.perpageenumber = response.data.Answers.comment.per_page;
          this.AnswerCommentPageBeginning = response.data.Answers.comment.from;
          this.lastpageAnswercomment = response.data.Answers.comment.last_page;
          this.paginatedComment = response.data.Answers.comment.data;
          this.edQuestionId = response.data.Answers[0].question_id;
          this.ans = this.answers.answer;
          this.list = response.data;
          this.listansid = this.list.Answers.map((item) => item.id);
          this.questionVerifyFlag = this.list.Verified;
        });
    },
    async PaginatedQuestionComments() {
      let id = this.getQuestionId();
      await http
        .get("/commentsquestion/" + id + this.perpage + this.numberrr)
        .then((response) => {
          this.listt = response.data.comments.data;
          this.numberrr = response.data.comments.per_page;
          this.last_page = response.data.comments.last_page;
          this.pageBeginning = response.data.comments.from;
          if (response.data.comments.length == 0) {
            this.snackbar = true;
          }
        });
      if (this.listt.Verified === 1) {
        this.vflag = 1;
      }
    },
    async viewQuestioncomments() {
      let id = this.getQuestionId();
      await http.get("/commentsquestion/" + id).then((response) => {
        this.listt = response.data.comments.data;
      
        this.numberrr = response.data.comments.per_page;
        this.last_page = response.data.comments.last_page;
        this.pageBeginning = response.data.comments.from;
        if (response.data.comments.length == 0) {
          this.snackbar = true;
        }
      });
      if (this.listt.Verified === 1) {
        this.vflag = 1;
      }
    },

    //
    navToProfile() {
      this.$router.push("/profile");
    },
    async getUser() {
      http.get("/getuser").then((response) => {
        this.User = response.data.user;
        this.userType = response.data.user.type;
      });
    },
    async getAnsid(id) {
      await http
        .get("/answer/view/" + id)
        .then()
        .catch(function (error) {
          throw error;
        });
    },

    ViewAnswercomments(id) {
      http
        .get("/answer/comments/" + id)
        .then((response) => {
          this.answeroncomment = response.data;

          if (response.data.comment.length == 0) {
            this.snackbar = true;
          }
        })
        .catch(function (error) {
          throw error;
        });
    },
    //
    editAnswerr(id) {
      this.$router.push("/edit-answer/" + id);
    },
    scrolltoTop() {
      window.scrollTo(0, 0);
    },

    setEditAnswer() {
      const bodyParameters = {
        answer: this.edAnswer,
      };
      if (this.edAnswer.length > 4096) {
        this.toast.error(
          "Your answer is too long.Please edit it down to 4096 characters",
          {
            position: POSITION.TOP_CENTER,
            timeout: 2000,
          }
        );
      } else {
        http
          .put("/answer/" + this.edAnswerId, bodyParameters)
          .then(() => {
            this.dialog_answer = false;

            this.toast.success("Answer edited successfully", {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
            this.getQuestionAnswers();
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

    editComment(id) {
      
      this.editCommentOnAnswerId = id;
      this.v$.$reset();
      http
        .get("/comment/" + id)
        .then((response) => {
          this.edComment = response.data.comment;
          this.edComId = response.data.id;
        })
        .catch(function (error) {
          throw error;
        });

      document.getElementById("edit-modal").style.display = "block";
    },
    setEditComment() {
      
     
      let comment1 = this.edComment.split(" ").filter(Boolean).join(" ");
      const bodyParameters = {
        comment: comment1,
      };
      http
        .put("/editComment/" + this.edComId, bodyParameters)
        .then((response) => {
        
          if (response.data.Answer_id) {
            let answer_id = response.data.Answer_id;
            this.perpageenumber = this.perpageenumber - 5
            this.paginatedAnswerComment(answer_id);
          } else {
            let qsid = this.getQuestionId();
            http.get("/commentsquestion/" + qsid).then((response) => {
              this.listt = response.data.comments.data;
            });
          }
          this.toast.success("Comment edited successfully!", {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
          this.modalCloseEdit();
               this.numberrr = this.numberrr-5;
          this.PaginatedQuestionComments();

        })
        .catch((error) => {
          let msg = "" + error.response.data[0].comment;
          this.toast.error(msg, {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
        });
      // }
    },
    delComment(id) {
      Swal.fire({
  title: 'Delete Comment!',
  text: "Are you sure want to delete?",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Delete',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {
      this.delComId = id;
      http
        .put("/comments/delete/" + this.delComId, {})
        .then((response) => {
          if (response.data.Answer_id) {
            let answer_id = response.data.Answer_id;
        
          
            this.paginatedAnswerComment(answer_id);
          } else {
            let qsid = this.getQuestionId();
            http.get("/commentsquestion/" + qsid).then((response) => {
              this.listt = response.data.comments.data;
            });
          }
          this.toast.success("Comment deleted successfully!", {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
          this.numberrr = this.numberrr-5;
          this.PaginatedQuestionComments();
        })

        .catch((error) => {
          let msg = "Unable to process !!" + error.response.data.message;
          this.toast.error(msg, {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
        });
    }
      })
    },
    deleteQuestion(){
    let alertmsg='Are you sure want to delete?';
      if(this.deleteCountFlag===true)
      {
alertmsg=alertmsg+"<br><small>Your reputation score will be deducted!!</small>";
      }
      Swal.fire({
  title: 'Delete Question!',
  html: alertmsg,
  text:alertmsg,
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Delete',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {
      let questionId = this.getQuestionId();
     
      http
          .put("/deleteQuestion/" + questionId)
          .then(() => { 
            this.$router.push("/question").then(()=>{
            this.toast.success("Question deleted successfully", {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
          })
          })
  }});
    },
    deleteAnswer(answerId){
      Swal.fire({
  title: 'Delete Answer!',
  text: "Are you sure want to delete?",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Delete',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {     
      http
          .put("/deleteAnswer/" + answerId)
          .then(() => { 
            this.getQuestionAnswers().then(()=>{
               this.toast.success("Answer deleted successfully", {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
            })
           
          })
  }});
    },
    editQuestion() {
      let questionId = this.getQuestionId();
      this.$router.push("/edit-question/" + questionId);
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
    async editQuestionSubmit() {
      this.v$.$validate();
      let edQuestionId = this.getQuestionId();
      const bodyParameters = {
        title: this.edTitle,
        description: this.edDescription,
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
            this.getCurrentUser();
            this.getQuestionAnswers();
            this.v$.$reset();
          })
          .catch((error) => {
            this.dialog_qstn = false;
            this.dialog_delete = false;
            this.getCurrentUser();
            this.getQuestionAnswers();
            let msg = "Unable to process !!" + error.response.data.message;
            this.toast.error(msg, {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
            this.v$.$reset();
          });
      }
    },

    
    getTagForEdit() {
      let id = this.getQuestionId();
      http
        .get("/tag/view/" + id)
        .then((response) => {
          this.tagListForEdit = response.data;
        })
        .catch(function (error) {
          throw error;
        });
    },
   async getallUsers() {
      this.flag = 0;
      http.get("/getAllUsers").then((response) => {
        this.userlist = response.data.users;
      });
    },

    async getCurrentUser() {
      http.get("/getuser").then((response) => {
        this.currentUser = response.data.user;
        this.User=response.data.user;
        this.currentuserpic = response.data.user.profile_picture;
        this.userType = response.data.user.type;
      });
    },
    selectedQuestionTagCheck(TagQuestioneditid) {
      if (this.editSelectedTagList.length > 2) {
        if (
          document.getElementById("editQuestionTagcheck" + TagQuestioneditid)
            .checked === true
        ) {
          document.getElementById(
            "editQuestionTagcheck" + TagQuestioneditid
          ).checked = false;
          let btnText = document.querySelector(".btn-text");
          btnText.innerText = "maximum tag limt reached";
          btnText.style.color = "red";
        } else {
          let btnText = document.querySelector(".btn-text");
          btnText.innerText = "Select Tags";
          btnText.style.color = "blue";
        }
      } else {
        let btnText = document.querySelector(".btn-text");
        btnText.innerText = "Select Tags *";
        btnText.style.color = "blue";
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
      http.post("/questionviews/" + Qid, {}).then(() => {});
      this.$router.push("/questionView/" + Qid).then(() => {
        window.location.reload();
      });
    },
    deleteNotification() {
      http.put("user/notification/delete").then(() => {
      
        window.location.reload();
      });
    },
    rand() {
      const input = document.querySelector("#tagInput");
      const tagForm = document.querySelector("#tagForm");
      const output = document.querySelector(".tags");

      tagForm.addEventListener("submit", (e) => {
        e.preventDefault();
        if (input.value === "") {
          e.preventDefault();
        } else if (output.children.length >= 4) {
          this.outputTag();
          input.disabled = true;
          input.placeholder = "max number of tags reached!";
        } else {
          this.outputTag();
        }
        e.preventDefault();
      });
      input.addEventListener("input", () => {
        const rmWhitespace = input.value.replace(/\s/g, "");
        input.value = rmWhitespace.replace(/\s[^a-zA-Z0-9]/g, "");
      });
      window.addEventListener("click", (e) => {
        if (e.target.classList.contains("remove-btn")) {
          e.target.parentElement.remove();
          input.disabled = false;
          input.placeholder = "Select tags...";
        }
      });
    },
    modalcomment() {
      this.addcomment = "";
      this.v$.$reset();
      document.getElementById("popup-2").classList.toggle("active");

      this.rand();
    },
    getQuestionId() {
      return this.$route.params.questionId;
    },
    viewTagQuestion(id) {
      http.get("/questions-onTags/" + id)
        .then(response => {
         localStorage.setItem('chosenTag', response.data.tagName);
          this.$router.push({ path: "/tagquestion/" + id });
        });
    },
    closeAddQCommentModal() {
      this.dialogcomment = false;
      this.addcomment = "";
      this.v$.$reset();
    },
    closeAddACommentModal() {
      this.v$.$reset();
    },
    checkTags() {
      if (this.editSelectedTagList) {
        this.tagFlag = false;
      }
    },
   closeEdCommentModal() {
     
      this.editComment();
      this.edComment = "";
    },

    openAddA(id) {
if(
  document.getElementById(`addCommentA${id}`)){
    this.addcommentA="";
  }else{
    console.log("");
  

  }

      this.v$.$reset();
      localStorage.setItem("AnswerId", Number(id));
   
    },
    modalCloseAddCommentAnswer() {
      this.addcommentA = "";
      document.getElementById("addA-modal").style.display = "none";
      document.getElementById("commentText").style.height = "80px";
    },
    modalCloseAddCommentQuestion() {
      this.addcomment = "";
     
      document.getElementById("addQ-modal").style.display = "none";
      document.getElementById("commentText").style.height = "80px";
      // this.PaginatedQuestionComments()
    },
    
    modalCloseEdit() {
      document.getElementById("edit-modal").style.display = "none";
      document.getElementById("commentEdit").style.height = "80px";
    },
    async getDeleteCount()
    {
      http.get("/deleteCount")
        .then(response => {
         this.deleteCountFlag=response.data.flag;
        });
    },
    showHistory()
    {
      this.$router.push('/history');
    }
  },
 async beforeMount() {
    
    this.vflag = 0;
    await this.getCurrentUser();
    await this.getallUsers();
    await this.getUser();
    await this.getDeleteCount();//to know how many times the user has deleted his questions
    await this.getQuestionAnswers();
    await  this.getPopulartags();
    http.get("/user/get-popular").then((response) => {
      this.popularUsers = response.data;
    });
    http
      .get("/user/notification")
      .then((response) => {
        this.notifications = response.data;
      })
      .catch(function (error) {
        throw error;
      });
      await  this.viewQuestioncomments().then(()=>
      {
        this.isLoading=false;
  });
  },
  beforeUnmount() {
    
    clearTimeout(this.timeout);
  },
};
</script>
<style scoped>
@import url("Currentuserpostedquestion.css");
@media screen and (max-width: 1050px) and (min-width: 850px){
.ma-2{
  left:9%
}
} 
@media screen and (max-width: 700px) and (min-width: 340px){
.ma-2{
  left:9%
}
} 
</style>