<template>
  <div class="notification-dropdown" ref="dropdown">
    <!-- dropdown content here -->
    <h2 class="notificationdropdowntitle">
      Notifications <span>{{ notifications.count }}</span>
      <p class="colorNotification0" v-show="notifications.count==null" id="nonotification">No notification to show </p>
      <span class="clearAllnotifications" v-show="notifications.count!=null" @click="deleteNotification()">clear all</span>
    </h2>
    <div class="notifi-item" v-for="v in notifications.Success" :key="v.id">
      <div v-if="v.status === 0" class="ForflexNotification">
        <img class="userPicture" v-if="v.photo != null" :src="v.photo" referrerpolicy="no-referrer" alt=""
          id="user-profile-image" />
        <img class="userPicture" v-else v-bind:src="require('@/assets/avatar.png')" alt="" id="user-profile-image" />
        <div class="notification-text" v-if="v.type == 1" @click="NotificationDetails(v.question_id,null,v.answer_id)">
          <h4 class="colorNotification0">
            {{ v.posted_user_name }} answered your question
          </h4>
        </div>
        <div class="notification-text" v-if="v.type == 2" @click="NotificationDetails(v.question_id,null,v.answer_id)">
          <h4 class="colorNotification0">
            {{ v.posted_user_name }} liked your answer
          </h4>
        </div>
        <div class="notification-text" v-if="v.type == 3" @click="NotificationDetails(v.question_id)">
          <h4 class="colorNotification0">
            Help {{ v.posted_user_name }} to find solution
          </h4>
        </div>
        <div class="notification-text" v-if="v.type == 4 && v.answer_id == null" @click="NotificationDetails(v.question_id)">
          <h4 class="colorNotification0">
            {{ v.posted_user_name }} commented on your question
          </h4>
        </div>
        <div class="notification-text" v-if="v.type == 4 && v.answer_id != null" @click="NotificationDetails(v.question_id,v.answer_id)">
          <h4 class="colorNotification0">
            {{ v.posted_user_name }} commented on your answer
          </h4>
        </div>
      </div>
      <div v-if="v.status === 1" class="ForflexNotification">
        <img class="userPicture" v-if="v.photo != null" :src="v.photo" referrerpolicy="no-referrer" alt=""
          id="user-profile-image" />
        <img class="userPicture" v-else v-bind:src="require('@/assets/avatar.png')" alt="" id="user-profile-image" />
        <div class="notification-text" v-if="v.type == 1" @click="NotificationDetails(v.question_id,null,v.answer_id)">
          <h4 class="colorNotification1">
            {{ v.posted_user_name }} answered your question
          </h4>
        </div>
        <div class="notification-text" v-if="v.type == 2" @click="NotificationDetails(v.question_id,null,v.answer_id)">
          <h4 class="colorNotification1">
            {{ v.posted_user_name }} liked your answer
          </h4>
        </div>
        <div class="notification-text" v-if="v.type == 3" @click="NotificationDetails(v.question_id)">
          <h4 class="colorNotification1">
            Help {{ v.posted_user_name }} to find solution
          </h4>
        </div>
        <div class="notification-text" v-if="v.type == 4 && v.answer_id == null" @click="NotificationDetails(v.question_id)">
          <h4 class="colorNotification1">
            {{ v.posted_user_name }} commented on your question
          </h4>
        </div>
        <div class="notification-text" v-if="v.type == 4 && v.answer_id != null" @click="NotificationDetails(v.question_id,v.answer_id)">
          <h4 class="colorNotification1">
            {{ v.posted_user_name }} commented on your answer
          </h4>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
import http from '@/http-common';
export default {
  props: {
    notifications: {}
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside);
  },
  handleClickOutside(event) {
    if (this.$refs.dropdown && !this.$refs.dropdown.contains(event.target)) {
      this.$emit('close');
    }
  },
  methods: {
     async NotificationDetails(Qid,answer_id=null,focusanswer_id=null) {
     if(answer_id!=null)
     {
      http.post("/questionviews/" + Qid, {});
      localStorage.setItem('answerId',answer_id)
            this.$router.push("/questionView/" + Qid).then(() => {
                this.$router.go();
            });
     }
     else if(focusanswer_id!=null)
     {
      http.post("/questionviews/" + Qid, {});
      localStorage.setItem('focusanswer_id',focusanswer_id)
            this.$router.push("/questionView/" + Qid).then(() => {
                this.$router.go();
            });
     }
      else   {   http.post("/questionviews/" + Qid, {});
            this.$router.push("/questionView/" + Qid).then(() => {
                this.$router.go();
            });}
        },
        deleteNotification() {
            http.put("user/notification/delete").then(() => {
                this.$router.go();
            });
        },
  }
};
</script>
<style scoped>
.notification-dropdown {
  position: absolute;
  top: 92px;
    right: 16px;
    min-width: 300px;
    max-width: 300px;
    min-height: 300px;
    max-height: 600px;
    overflow-y: auto;
    padding: 8px;
    background-color: #fcfcfc;
    border-radius: 4px;
    box-shadow: 3px 2px 13px 17px rgb(0 0 0 / 10%);
    z-index: 1;
}
.notificationdropdowntitle
{
  font-size: 15px;
}
.notificationdropdowntitle span{
  color: red;
}
.ForflexNotification{
  display: flex;
  flex-direction: row;
  word-break: break-word;
}
.notification-text{
  display: flex;
  flex-direction: column;
  justify-content: center;
  font-size: 13px;
  cursor: pointer;
}
#nonotification{
  justify-content: center;
    display: flex;
  padding-top: 30px;
}
</style>  