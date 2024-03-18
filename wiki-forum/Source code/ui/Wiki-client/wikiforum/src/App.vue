<template>
  <v-app>
    <v-main>
      <div class="main-header">
        <Header></Header>
      </div>
      <div class="main-grid" id="gridMain">
        <div class="main-sidebar">
          <Sidebar></Sidebar>
        </div>
        <div class="main-router">
          <router-view />
        </div>
      </div>
    </v-main>
  </v-app>
</template>

<script>
import Header from '../src/components/Header.vue';
import Sidebar from "../src/components/Sidebar.vue";
export default {
  name: "App",

  components: { Header, Sidebar },
  data: () => ({
    //
  }),
};
</script>
<style>
.body {
overflow-x: hidden !important;
overflow-y: auto;
}
.mobileSideBarOpenBtn{
  display: none;
 }
 #searchQuestions{
  margin-left: 0px !important;
 }
 .ckEditormedia-answer{
  width: 98%;
 }
 /* for mobile devices */
@media only screen and (max-width: 700px){
  /* login area */
  .wf-logoo{
    margin-left: 0 !important;
  }
#cardA{
  display: block !important;
  left: 0 !important;
}
.py-4{
  padding-left: 0!important;
}

  /* login area end */
  .ignoredags-div, .interestedtags-div, .question5-div, .answer5-div
{
width:100% !important;
}
  .question-answer-div,.section-watchtags {
  display: flex !important;
  flex-direction: column !important;
  justify-content: center !important;
  align-items: flex-start !important;
}
#profileSection{
  width: 100%;
}
.innerwrap{
  margin: 0 !important;
width: 64% !important;
}
  .btn-verify{
    right:30px !important
  }
  .notification-dropdown{
    right: 5px !important;
    max-height: 400px !important;
  }
  .btn-add {
    font-size: 11px !important;
    width: 84px !important;
    height: 35px !important;
    padding: 0 !important;
  }
  .addTagDiv{
    padding-right: 0 !important;
  }
  #addAnswer-media{
    margin-left: 0px !important;
  }
  .forColor{
    font-size: 15px;
  }
  .form_field {
    padding: 9px 0 9px 0px !important;
    margin-left: 0px !important;
  }
  #newAnswers{
    margin-left: 0 !important;
  }
   #searchQuestions{
    max-width: 100% !important;
    margin-left: 0px !important;
  }
  .main-content{
    margin: 0px !important;
    width: 100%;
    padding: 0px !important;
  }
  .main-body{
    width: 100% !important;
  }
#sideBarNav{
  display: none;
}
#wiki-routerLink {
    display: none !important;
}
.mobileSideBarOpenBtn{
  display: contents !important;
  order: -1;
 }
.main-grid {
  grid-template-columns: 0% 100% !important;
}
.wiki-Data[data-v-61dd7a3d] {
    display: flex;
    flex-direction: row;
    align-items: center;
}
}

#body {
  background: rgb(226, 226, 226);
  min-height: calc(100vh - 90px);
  overflow: hidden;
}
.main-header{
  height: 90px;
}
.noQuestionsPostedDiv{
  order: -1 !important;
}
.popular_creators ,.popular_tags{
  word-break:break-word !important;
}
.main-content {
  position: relative;
  width: 75%;
  word-break: break-word !important;
}
/* for ck editor width adjustment */
@media screen and (min-width: 1409px){
.ckEditormedia{
  max-width: 800px !important;
}
}
@media screen and (max-width:1500px) {
    .main-content{
      order: -1;
    }
}
@media screen and (max-width: 285px){
  .avatar-upload .avatar-edit{
    left: 136px !important;
  }
  .sidepanel #closebtn{
    padding-top: 14px !important;
  }
  #mobileSidebarHeader{
    height: 60px !important;
  }
.ckEditormedia{
width: 200px !important;
}
.questionPostedUser-Details{
  word-break: break-word;
  width:100px
}
.section1 div .row:first-child {
    padding: 10px!important;
}
.container {
    padding: 0px !important;
}
.notificationdropdowntitle {
    font-size: 12px !important;
}
.notification-dropdown{
  min-width: 240px !important;
  max-width: 250px !important;
}
}
.ckEditormedia{
  width: 84%;
}
/* end */
.card-detail{
  word-break: break-word !important;
}
.notification-bell{
  cursor: pointer;
  padding-top: 10px;
  font-size: 25px;
  color:white;
}
.user_score{
  color:white;
}
.badge {
    top: 3px;
    right: -19px;
    height: 22px;
    width: auto;
    font-size: medium;
    z-index: 99;
    border-radius: 50%;
    font-weight: bold;
    color: white;

}
body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
    padding-right: 0px !important;
    overflow: hidden;
}
.main-grid {
  display: grid;
  grid-template-columns: 80px calc(100vw - 80px);
}

.main {
  position: relative;
  padding: 20px;
  width: 100%;
}
.smalltri{
  display: none!important;
}
body{
  overflow-x:hidden ;
}
.v-overlay__scrim {
  display: none;
}
/* css for providing tags as aligned in questions display pages */
#tagsforquestion 
{
  flex-wrap: wrap !important;
}


.ck.ck-dropdown .ck-button.ck-dropdown__button:not(.ck-button_with-text) {
  display: none;
}

#ck-editor__aria-label_e85d7478308d2dad827fc7f483584798a {
  display: none;
}

.ck.ck-editor {
  position: relative;

  width: 100% !important;
}

.ck.ck-editor ul li {
  list-style-type: square !important;
}

.ck.ck-editor__main>.ck-editor__editable:not(.ck-focused),
.ck-rounded-corners .ck.ck-editor__main>.ck-editor__editable,
.ck.ck-editor__main>.ck-editor__editable.ck-rounded-corners {
  height: 300px;
}

li {
  margin-left: 2%;
}

::-webkit-scrollbar {
  width: 12px;
}

/* Track */
::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  -webkit-border-radius: 10px;
  border-radius: 10px;
}

/* Handle */
::-webkit-scrollbar-thumb {
  -webkit-border-radius: 10px;
  border-radius: 10px;
  background: rgba(182, 182, 182, 0.8);
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
}
.row2tab li[data-v-69e22fa3]:first-child{
  color: black!important;
  border: none!important;
}
.main-top {
  left: 0;
  padding-left: 16px;
  position: fixed;
  z-index: 999;
  padding-top: 16px;
  width: 101vw !important;
  background-color: #D4D8E8;
  height: 79px;
  margin-top: -20px;
  margin-left: -20px;
}
.usernamedashboard{
  word-break: break-word;
}
.main-top #i {

  margin-top: -9px !important;
}


.wiki {
  margin-top: -24px;
}

.pageHaedings {
  margin-left: 15px;
  margin-top: 11px;
  cursor: default !important;
}



.logo {
  height: 40px;
}

.formSearch {
  width: 100%;
}

.main-top .search {
  padding: 6px;
  font-size: 17px;
  border: none;
  width: 400px !important;
}

.pop-color {
  color: #005176;
}

/* for headings newly added */
.heading {
  white-space: pre-wrap !important;

  min-width: 180px !important;
}

/* style for bulleted list start */
ul li {
  list-style-type: square !important;
  list-style-position: inside !important;
}

.dashboard-pagination {
  display: flex;
  flex-direction: row;
  margin-left: 21%;
  color: #24335b;
}
.dashboard-footer{
  display: flex;
  justify-content: center;
}
@media screen and (max-width: 1700px){
  .nodatauq {
    color: black;
    font-size: 50px;
    width: -moz-max-content;
    width: max-content;
    margin-left: 0; 
    margin-top: 0;
}
.nodataD{
  margin-left: 0!important;
  margin-top: 18px;
  /* margin-top: 0!important; */
}
.null-result{
  margin-left: 0!important;
  margin-top: 0!important;

}
#noData {
    margin-left: 78%!important;
   margin-top: 0px;
    width: 100%;
}
.nodataTQE{
  margin-left: 0!important;
}
}
@media screen and (max-width: 400px) and (min-width: 285px){
  .avatar-upload .avatar-edit{
    left: 129px !important;
  }
}
@media screen and (max-width: 600px) and (min-width: 400px){
  .avatar-upload .avatar-edit{
    left: 150px !important;
  }
}
@media screen and (max-width: 357px){
.main-grid{
  grid-template-columns: 45px calc(100vw - 45px);
}
}
@media screen and (max-width: 1700px) and (min-width: 1200px){
  .userDataonHeader{
  margin-top: 18%;
  margin-left: -20px;
}

.contentAddQuestion-Add {
    margin-left: 5%!important;
}

.qtitle{
     width: 100%!important;
      }
      #tg {
        width: 100%!important;
    }
    #QuestionAddFrom{
        width: 100%!important;
    }
 
.sub-btnn {
    margin-left: 173px!important;
  width: 300px;
}

}











@media screen and (max-width: 1050px){
  #ProfilePage{
  flex-direction: column;
}
}

@media screen and (max-width: 1200px){
.main-body {
    flex-direction: column;
    width: 70%;
    /* margin-left: 15%; */
}

.comment-box-question .comment{
        width: 100%!important;
    }

.heading{
  width: 395px !important;
}
.wiki-name{
  display: none;
}
/* .headerPageHeading{
  margin-top: 8.75%!important;
} */
.notification{
max-width: 10px;
}

.score-div{
  display: none;
}
.userDataonHeader{
  margin-top: 18%;
  margin-left: -80px;
}
.cardbox{
  height: auto!important;
}
.main-content-profile{
  font-size: 10px;
  width: 150%!important;
}
.answer-topDiv{
  flex-direction: column!important;
}
.comments {

  margin-left: 11%!important;
}
.popular_creators ,.popular_tags{
  height: auto!important;
}
.H4-comments{
  font-size: small!important;
}
.Forflex{
    flex-direction: column;
  }
  .date{
    width: 100%!important;
  }
}
@media only screen and (min-width: 700px) and (max-width: 850px) {
.card-user{
  width: 200px !important;
}
.userlistImg{
  width: 80px !important;
  height: 80px !important;
}
}
@media screen and (max-width: 600px)
{.wf-logoo {
    height: 66px!important;
    width: 100px!important;
}
}

@media screen and (max-width: 440px)
{.tagpage-container{
  padding: 7px !important;
}}

@media screen and (max-width: 600px){
  .box{
    width: 100%!important;
    font-size: xx-small!important;
  }
  .wf-logo{
    height: 30px!important;
    width: 30px!important;
  }
  .ckEditormedia{
    width: 84%;
    overflow-x: scroll;
  }
  .ck.ck-editor {
    width: 399px!important;
  }

}
@media screen and (max-width: 700px){

 
#backButton{
margin-left: 10%!important;
}
.ft-modal-content-comment{
  width: 100%!important;
  padding: 20px!important;
}
.commentfooter{
  margin-left: 0!important;
}

#QuestionAddFrom{
        width: 100%!important;
        margin-left: 5%!important;
    }
    .contentAddQuestion-Add {
    margin-left: 5%!important;
}
/* .openBtn ,.popular_creators, .popular_tags, .cardbox{
    margin-left: 15%!important;
} */
.heading{
  width: 0 !important;
}
.ft-modal-content[data-v-69e22fa3]{
  width: 100%!important;
}
}
@media screen and (max-width: 780px){
  .search-container-Tag{
  flex-wrap: wrap;
  
}
.ft-modal-content{
  width: 100%!important;
}
.btn-add{
margin-left: 40%;
}
}
@media screen and (max-width: 430px){
  .userBadge{
  display: none;
}
.ckEditormedia-answer{
  width: 100%;
  overflow-x: scroll;
}
#newAnswers{
  font-size: small !important;;
}
  .headerPageHeading{
margin-left: 18px;
}
.noComments{
  margin-top: 15%!important;
}
#title{
  font-size: large!important;
}
.usernamedashboard{
  word-break: break-word;
}
.userDataonHeader{
  margin-left: -28px;
  max-height: 50px!important;
  gap:0!important;
}
.dashboard-pagination{
  margin-left: 10%!important;
}
.date{
width: 100%!important;
}
.Forflex{
  flex-direction: column;
}
.open-close{
  display: none;
}
.container{
  font-size: 15px;
}
#lastPageButton{
  display: none!important;
}

  .question-title{
font-size: large!important;
  }
  #QuestionDiv {
    font-size: small!important;
}

.filterS {
    margin-left: 5%!important;
    margin-top: 6%!important;
    display: flex!important;
    height: 56px!important;
}
h4{
  font-size: small!important;
}
#AddCommmentButton
{
height:30px!important;
width:170px!important;
}
.answerVerification{
  margin-top: 10%;
}
/* .ck.ck-editor {
  width: 200px!important;
  font-size: smaller!important;
} */
.userImg {
    margin-left:0!important;
    display: flex;

    justify-content: center;
}
#user-profile-image{
  height: 40px!important;
  width: 40px!important;
}
.nodataTQE{
    font-size: x-large!important;
  }
  .nodataD ,.nodataTL , .nodataU , .nodatauq1,.nodataTQ, .nodatauq{
    font-size: x-large!important;
  }

  @media screen and (max-width: 430px)
{
  .nodataD, .nodataTL, .nodataU, .nodatauq1, .nodataTQ, .nodatauq {
    font-size: x-large!important;
    margin-left: 21px !important;
}
}
  #noData{
    margin-left: 40%!important;
    font-size: x-large!important;
  }
}
@media screen and (max-width: 475px){
  .column-tag {
    max-width: 100%;
    padding-right: 10px;
}
#TagnameSize{
  font-size: 15px;
}
.card-tag{
  width: 150px !important;
  padding: 10px !important;
}
   .main-content{
    margin-left: 0!important;
  }
  .contentAddQuestion-Add{
    margin-left: 0!important;
  }
  .main-content-profile{
    margin-left: -30px!important;
  }
  .sort-icon{
    margin-left: 0!important;
  }
 .card-user{
  width: 175px !important;
 }
  #listUl{
    /* margin-left: 25px!important;
     */
  display: flex;
  justify-items: center;
    }

}
@media screen and (max-width: 1500px){
  #questionListDiv{
    order: -1;
  }
}
@media only screen and (min-width: 300px) and (max-width: 400px) {
 .interestedTags{
  padding-left: 0!important;
 }
 .column-userTag{
  padding: 0 !important;
 }
 .row-userTag{
  padding-left: 0 !important;
 }
 #profileList{
width: 251px !important;
 }
 .card-user{
  width: 150px !important;
 }
}

@media screen and (max-width: 285px){
  .innerwrap{
    padding-left: 12px !important;
  }
  .main-content-profile{
    margin-left: 0px !important;
  }
  #dashboard-page{
    display: block !important;
  }
  .ms-wf-logo {
    width: 35px;
    height: 35px;
}
  .ms-wikiforum-title {
    font-size: 15px;
  }
  #addAnswer-media {
    margin-left: 0px !important;
}
  #QuestionAddFrom{
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 100%!important;
    margin-left: -10px!important;

  }
  #tg {
    margin-top: 0!important;
    max-width: 0!important;
    margin-left: -34px;
}
.autocflex{
  display: flex;
  flex-direction: column;
  justify-content: center;
  width: 230px;
  
}
.question {
    width: 197px!important;
    margin-left: -34px!important;
}

#comment-box-media{
  width: 200px;
  
}
.form_field{
  width: 200px;
}
.row-userTag{
  margin-left: 0!important;
}
  .column-user{
    padding: 0!important;
  }
  .sideBarUl{
    margin-left: -16px!important;
  }
.card-userTag{
  margin-left: -10px;
  width: 195px!important;
  font-size: x-small!important;

}
  nav{
    width: 45px!important;
  }
  .container{
    font-size: 12px;
  }
  .userDataonHeader{
    margin-left: -36px;
    margin-top: 20px!important;
  }
  .headerPageHeading{
    font-size: 12px;
    margin-left: 40px!important;
    margin-top: 35px!important;
  }
  .openBtn{
    font-size: 12px!important;
  }
   .dashboard-pagination{
margin-left: 0!important;
  }
  .notifi-box{
width: 230px!important;
  }
  .card-tag{
    min-width: 230px!important;
    width: auto!important;
  }

  .your-questions-footer {
        margin-left: 0!important;
    }
      .comment-box-question .comment{
      min-width: 130px!important;
      width: 88%!important;
    }
    .sort-icon {
    margin-left: 10%!important;
    margin-top: 0;
}
.card-user {
    width: 235px!important;

}
.row-userlist{
  margin-left: 0!important;
}
#user-profile-image{
  margin-left: 0px!important;
}
.userPnts{
margin-left: 0px !important;
}
    .contentAddQuestion-Add {
    margin-left: 1%!important;
}
.userBadge{
  display: none;
}
.mpage{
  display: contents!important;
}
}
.error-show-questionadd{
  top: -17px;
    position: inherit;
    margin-top: 0%;
}
@media screen and (max-width: 1050px) and (min-width: 850px) {
     .sidebar{
      margin-left: 15%!important;
     }
    }
/* style for bulleted list end */
</style>
