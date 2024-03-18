<template>
  <div class="scrolll">
    <div id="unsubscribe" >
      <div class="letter">
        <div class="shadow"></div>
        <div class="background"></div>
        <div class="body"></div>
      </div>
      <h1>Email confirmation</h1>
      <div class="emailVer">
      <p>Click on the below button to confirm your email address</p></div>
      <div class="cta">
        <div>
          <loading
            v-model:active="isLoading"
            :can-cancel="true"
            :on-cancel="onCancel"
            :is-full-page="fullPage"
          />
          <button class="confirm" v-on:click="verify()">Confirm</button>
        </div>
        <div class="imgBackground">
          <img :src="require('@/assets/verify.jpg')" 
            class="img-fluid"
           
           id="bimg"
          
            alt=""
          />
        </div>
      </div>
    </div>
  </div>
</template>


<script>
// import http from "../../http-common";
// import http from '../VerifyRegisteredUser/http-import';
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import axios from "axios";
import { useToast, POSITION } from "vue-toastification";
export default {
  name: "sfdvc-c",
  data() {
    return {
      isLoading: false,
      fullPage: true,
    };
  },
  components: {
    Loading,
  },

  setup() {
    const toast = useToast();
    return { toast };
  },
  methods: {
    async verify() {
      this.isLoading = true;
      let res = await axios
        .create({
          // baseURL: "http://localhost:8000/api",
          baseURL: "https://wikiforum-api.innovaturelabs.com/api",

          headers: {
            // "Content-type": "application/json",
            "Cache-Control": "no-cache",
            Authorization:
              `Bearer ` + JSON.parse(localStorage.getItem("TOKENN")),
          },
        })
        .post("/user/confirm")
        .then((resp) => {
          console.log("data", resp);
          this.toast.success(resp.data.Message, {
            position: POSITION.TOP_CENTER,
            timeout: 3000,
          });
          this.$router.push("/");
        });

      setTimeout(() => {
        this.isLoading = false;
      }, 3000);

      console.log("sssssss", res);
    },
  },
};
</script>


<style>
#unsubscribe{
margin-top: 2%;margin-left: -9px;
}
#bimg{
  width: 47%;

margin-left: 25%;
}
@import url("VerifyRegisteredUser.css");
</style>
