<template >
  <div id="body" @click="closeMOdal()">
    <div class="container">
      <div class="main" style="height: 100vh">
        <div class="py-4" style="padding-top: 3px !important">
          <v-card style="max-width: 570px" class="mx-auto pa-12 pb-8" elevation="8" max-width="448" rounded="lg" id="changePass">
            <div class="
                text-subtitle-1 text-medium-emphasis
                d-flex
                align-center
                justify-space-between
              ">
              Old password
            </div>

            <v-text-field id="oldPass"
              :append-inner-icon="visibleoldpass ? 'mdi-eye-off' : 'mdi-eye'"
              :type="visibleoldpass ? 'text' : 'password'"
              density="compact"
              placeholder="Enter your old password"
              prepend-inner-icon="mdi-lock-outline"
              variant="outlined"
              @click:append-inner="visibleoldpass = !visibleoldpass"
              v-model="oldpassword"
               @blur="v$.oldpassword.$touch()" @keyup="v$.oldpassword.$touch()" v-bind:class="{
                      error: v$.oldpassword.$error,
                      valid: v$.oldpassword.$dirty && !v$.oldpassword.$invalid,
                    }" autocomplete="off"></v-text-field>
                  <span class="error-show-questionadd" v-for="(error,index) in v$.oldpassword.$errors" :key="error.$uid"
                  style="top: -17px;
    position: inherit;
    margin-top: 0%;">    <div v-if="index == 0">
          {{ error.$message }}
        </div></span>
            <div
              class="
                text-subtitle-1 text-medium-emphasis
                d-flex
                align-center
                justify-space-between
              ">
              New password
            </div>

         
            <v-text-field @keydown.space.prevent
              :append-inner-icon="visiblenewpass ? 'mdi-eye-off' : 'mdi-eye'"
              :type="visiblenewpass ? 'text' : 'password'"
              density="compact"
              placeholder="Enter your new password"
              prepend-inner-icon="mdi-lock-outline"
              variant="outlined"
              @click:append-inner="visiblenewpass = !visiblenewpass"
              v-model="newpassword"
               @blur="v$.newpassword.$touch()" @keyup="v$.newpassword.$touch()" v-bind:class="{
                      error: v$.newpassword.$error,
                      valid: v$.newpassword.$dirty && !v$.newpassword.$invalid,
                    }" autocomplete="off"></v-text-field>
                  <span class="error-show-questionadd" v-for="(error,index) in v$.newpassword.$errors" :key="error.$uid"
                    style="top: -17px;
    position: inherit;
    margin-top: 0%;">     <div v-if="index == 0">
          {{ error.$message }}
        </div></span>
            <div class="text-subtitle-1 text-medium-emphasis">
              Confirm password
            </div>

            <v-text-field :append-inner-icon="visiblecpass ? 'mdi-eye-off' : 'mdi-eye'" @keydown.space.prevent
              :type="visiblecpass ? 'text' : 'password'" density="compact" placeholder="Enter your password again"
              prepend-inner-icon="mdi-lock-outline" variant="outlined"
              @click:append-inner="visiblecpass = !visiblecpass" v-model="cpassword" @blur="v$.cpassword.$touch()"
              @keyup="v$.cpassword.$touch()" v-bind:class="{
                error: v$.cpassword.$error,
                valid: v$.cpassword.$dirty && !v$.cpassword.$invalid,
              }" autocomplete="off"></v-text-field>
            <span class="error-show-questionadd" v-for="(error,index) in v$.cpassword.$errors" :key="error.$uid"
           style="top: -17px;
    position: inherit;
    margin-top: 0%;">     <div v-if="index == 0">
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
    </div>
  </div>
</template>
<script>

import http from "../../http-common";
import { useVuelidate } from "@vuelidate/core";
import { required,sameAs, helpers } from "@vuelidate/validators";
import { useToast, POSITION } from "vue-toastification";

export default {
  validations() {
    return {
       oldpassword: {
        required: helpers.withMessage("Old password is required",required),
        containsPasswordRequirement: helpers.withMessage(
          `Enter a valid password`,
          (value) =>  /^(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*])(?=.*\d)[A-Za-z\d!@#$%^&*]{8,}$/.test(value)
        ),
        $autoDirty: true,
      },

              newpassword: {
        required: helpers.withMessage("New password is required",required),
                containsPasswordRequirement: helpers.withMessage(
          `Enter a password that contains an uppercase, lowercase, number and special character,8 characters long`,
          (value) =>  /^(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*])(?=.*\d)[A-Za-z\d!@#$%^&*]{8,}$/.test(value)
        ),
        $autoDirty: true,

      },
 
 cpassword: {  required: helpers.withMessage("Confirm password is required ",required),
       sameAsPassword:helpers.withMessage("Password not match", sameAs(this.newpassword)) },
 


    };
  },
  setup() {
    const toast = useToast();
    return { toast };
  },
  name: "dff-s",
  data: () => ({

    oldpassword: "",
    v$: useVuelidate(),
    newpassword: "",
    cpassword: "",
    usernameRules: [],
    emailRules: [],
    passwordRules: [],
    cpasswordRules: [],
    visibleoldpass: false,
    visiblecpass: false,
    visiblenewpass: false,

  }),
  

 
  methods: {
    async changepassword() {
      this.v$.$validate();
       if(
        this.v$.oldpassword.$error ||
        this.v$.newpassword.$error ||
        this.v$.cpassword.$error
      ){
        console.log();
      }
      else{
      let result = await http
        .put("/user/change/password", {
          current_password: this.oldpassword,
          new_password: this.newpassword,
          password_confirm: this.cpassword,
        })
        .then(() => {
          this.toast.success("Passoword updated succesfully", {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
this.$router.push('/dashboard')
     
        }        )
        .catch((error) => {
          console.log("ss", error.response.data.message);
          let msg = error.response.data.message
         
              this.toast.error(msg, {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
        });
      console.log("1111111", result);

      if (result.data.Message == "Password changed successfully") {
          this.toast.error("Password changed successfully", {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
     
        window.location.href = "dashboard"
      }
    }
    },
  },
};
</script>
<style>
@import url("Changepass.css");
@media screen and (max-width: 450px) {
#changePass{
  width: 210px;
    left: -11%;
}
}
@media screen and (max-width: 320px) {
#changePass{
  width: 210px;
    left: -24%;
}
}
@media screen and (max-width: 300px) {
#changePass{
  width: 200px;
    left: -25%;
}
}
@media screen and (max-width: 290px) {
#changePass{
  width: 200px;
    left: -19%;
}}
</style>