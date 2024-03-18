<template >

  <div class="mainn" style="height: 100vh"  v-if="pageSuccess">
    <h2 style="color: #507082;">Reset password</h2>
    <div class="py-4" style="padding-top: 3px !important">
      <div id="logo" class="google-logo" title="Google">
        <img :src="require('@/assets/wiki.png')" alt="" class="wf-logo" />
      </div>

      <v-card style="max-width: 570px" class="mx-auto pa-12 pb-8" elevation="8" max-width="448" rounded="lg" id="cardA">
        <div class="
                text-subtitle-1 text-medium-emphasis
                d-flex
                align-center
                justify-space-between
              ">
          New password
        </div>


        <v-text-field  @keydown.space.prevent :append-inner-icon="visiblenewpass ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visiblenewpass ? 'text' : 'password'" density="compact" placeholder="Enter your new password"
          prepend-inner-icon="mdi-lock-outline" variant="outlined"
          @click:append-inner="visiblenewpass = !visiblenewpass" v-model="newpassword" @blur="v$.newpassword.$touch()"
          @keyup="v$.newpassword.$touch()" v-bind:class="{
            error: v$.newpassword.$error,
            valid: v$.newpassword.$dirty && !v$.newpassword.$invalid,
          }" autocomplete="off"></v-text-field>
        <span class="error-show-questionadd" v-for="(error,index) in v$.newpassword.$errors" :key="error.$uid"
                style="top: -17px;
    position: inherit;
    margin-top: 0%;"><div v-if="index == 0">
          {{ error.$message }}
        </div></span>
        <div class="text-subtitle-1 text-medium-emphasis">
          Confirm password
        </div>

        <v-text-field @keydown.space.prevent :append-inner-icon="visiblecpass ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visiblecpass ? 'text' : 'password'" density="compact" placeholder="Enter your password again"
          prepend-inner-icon="mdi-lock-outline" variant="outlined" @click:append-inner="visiblecpass = !visiblecpass"
          v-model="cpassword" @blur="v$.cpassword.$touch()" @keyup="v$.cpassword.$touch()" v-bind:class="{
            error: v$.cpassword.$error,
            valid: v$.cpassword.$dirty && !v$.cpassword.$invalid,
          }" autocomplete="off"></v-text-field>
        <span class="error-show-questionadd" v-for="(error,index) in v$.cpassword.$errors" :key="error.$uid"
               style="top: -17px;
    position: inherit;
    margin-top: 0%;"><div v-if="index == 0">
          {{ error.$message }}
        </div></span>
        <v-card-text class="text-medium-emphasis text-caption">
        </v-card-text>

        <br />

        <v-card class="mb-12" color="surface-variant" variant="tonal">
        </v-card>

        <v-btn block class="mb-8" style="margin-top: -13%" color="blue" size="large" variant="tonal"
          v-on:click="changepassword">
          Submit
        </v-btn>
   

      </v-card>
    </div>
  </div>

</template>
<script>

import http from "../../http-common";
import { useRoute } from 'vue-router'
import { useVuelidate } from "@vuelidate/core";
import { required,sameAs, helpers } from "@vuelidate/validators";

import { useToast, POSITION } from "vue-toastification";

export default {


  validations() {
    return {
      newpassword: {
        required: helpers.withMessage("Password is required", required),

        containsPasswordRequirement: helpers.withMessage(
          `Enter a password that contains an uppercase, lowercase, number and special character,8 characters long`,
          (value) =>   /^(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*])(?=.*\d)[A-Za-z\d!@#$%^&*]{8,}$/.test(value)
        ),
        $autoDirty: true,

      },
         cpassword: {
        required: helpers.withMessage("Confirm password is required.", required),
        sameAsPassword: helpers.withMessage(
          "Password not match",
          sameAs(this.newpassword)
        ),
      },
     

    };
  },
  name: "dff-s",
  data: () => ({

    v$: useVuelidate(),
    newpassword: "",
    cpassword: "",
 
    passwordRules: [],
    cpasswordRules: [],
    email: '',
    token: '',
    visiblecpass: false,
    visiblenewpass: false,
    pageSuccess:false,
  }),

 
  setup() {
    const toast = useToast();
    return { toast };
  },
  beforeMount() {


  const route = useRoute();
    let text = route.query.token;
    let token = text.substr(0, 30);
    console.log(token);

    const verifyToast = useToast();
    let email = text.substr(37);
    email=email.replace(/ /g,"+");
    http.post("/user/verify/token/reset-password", {
      email: email,
      token: token,
    })
      .then((response) => {
        this.pageSuccess=true;
        this.notifications = response.data;
      })
      .catch((error) => {
        this.pageSuccess =false;
        localStorage.clear();
        this.$router.push('/');
        let msg=error.response.data?.message;
        verifyToast.error(msg,{
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
        throw error;
      });
    // 
  },
  methods: {

    async changepassword() {
      this.v$.$validate();
if(this.v$.newpassword.$error
||this.cpassword.$error)
{
  console.log();
}
else{
      let b = console.log("aaaa", this.$route.fullPath)
      console.log(b)
      let bb = this.$route.fullPath
      let fpasstoken = bb.substr(23, 30);
      console.log("sssssssssss", fpasstoken);
      let fpassemail = bb.substr(60);
      console.log("emailllllllll", fpassemail)
      this.v$.$validate();
      let result = await http
        .put("/user/reset/password", {
          email: fpassemail,
          token: fpasstoken,
          new_password: this.newpassword,
          password_confirm: this.cpassword,
        }).then((result) => {
          console.log("ressssssssssssss", result)
          let msg = result.data.Message
          this.toast.success(msg, {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
          this.$router.push("/");
        })

        .catch((error) => {
          console.log("sssssssssss",error)

          let msg = "Unable to process !! " + error.response.data.message;
          this.toast.error(msg, {
            position: POSITION.TOP_CENTER,
            timeout: 1700,
          });
        });
      console.log("1111111", result);

    }
    },
  },
};
</script>
<style scoped>
@import url(newPass.css);
@media screen and (max-width: 2000px) {
.py-44{
  height: fit-content;
  margin-top: -90px;

  /* margin-top: -15% */
}
}
@media screen and (max-width: 650px) {
#cardA{
  width: 390px;

    display: inline-block; 
}

.wf-logoo {
    margin-left: -24%;
  
}
}
@media screen and (max-width: 498px) {
#cardA{
  width: 366px;
    left: -11%;
    display: inline-block; 
    
}
.wf-logoo {
    margin-left: -34%;
  
}

}
@media screen and (max-width: 430px) {
#cardA{
  width: 300px;
    left: -9%;
    display: inline-block;
     
}
#signup{
  margin-left:-45px
}
.wf-logoo {
    margin-left: -54%;
  
}
}
@media screen and (max-width: 360px) {
#cardA{
  width: 290px;
    left: -22%;
    display: inline-block; 

}
#googleButton{
  margin-left: 2%;
}
.wf-logoo {
    margin-left: -54%;
  
}
}
@media screen and (max-width: 316px) {
#cardA{
  width: 250px;
    left: -11%;
    display: inline-block; 
    
}
#googleButton{
  margin-left: 2%;
}
#signup{
  margin-left:-65px
}
.wf-logoo {
    margin-left: -34%;
  
}

}
</style>
