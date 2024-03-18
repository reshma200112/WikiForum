<template>
  <div class="log">
    <div class="py-4">
      <div id="logo" class="google-logo" title="Google">
        <img :src="require('@/assets/wiki.png')" alt="" class="wf-logoo" />
      </div>

      <v-card id="cardA" class="mx-auto pa-12 pb-8" elevation="8" max-width="448" rounded="lg">
        <div class="text-subtitle-1 text-medium-emphasis">Email</div>

        <v-text-field density="compact" @keydown.space.prevent placeholder="Email address"
          prepend-inner-icon="mdi-email-outline" variant="outlined" v-model="e_Mail" @blur="v$.e_Mail.$touch()"
          @keyup="v$.e_Mail.$touch()" v-bind:class="{
            error: v$.e_Mail.$error,
            valid: v$.e_Mail.$dirty && !v$.e_Mail.$invalid,
          }" autocomplete="off"></v-text-field>
        <span class="error-show-questionadd" v-for="error in v$.e_Mail.$errors" :key="error.$uid">{{ error.$message
        }}</span>
        <div class="
            text-subtitle-1 text-medium-emphasis
            d-flex
            align-center
            justify-space-between
          ">
          Password
        </div>

        <v-text-field @keydown.space.prevent :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible ? 'text' : 'password'" density="compact" placeholder="Enter your password"
          prepend-inner-icon="mdi-lock-outline" variant="outlined" @click:append-inner="visible = !visible"
          v-model="password" @blur="v$.password.$touch()" @keyup="v$.password.$touch()" v-bind:class="{
            error: v$.password.$error,
            valid: v$.password.$dirty && !v$.password.$invalid,
          }" autocomplete="off"></v-text-field>
        <span class="error-show-questionadd" v-for="(error, index) in v$.password.$errors" :key="error.$uid">
          <div v-if="index == 0">
            {{ error.$message }}
          </div>
        </span>
        <a class="text-caption text-decoration-none text-blue" href="fpassword" rel="noopener noreferrer">
          Forgot password?</a>
        <br />
        <!-- <div>
          <p v-if="alert == 0">{{}}</p>
          <p style="color: red" v-if="alert == 1">{{ alertMessage }}</p>
        </div> -->

        <v-card class="mb-12" color="surface-variant" variant="tonal">

        </v-card><br />

        <v-btn block class="mb-8" style="margin-top: -18%" color="blue" size="large" variant="tonal" v-on:click="loginn"      :disabled="pausedFor2Seconds">
          Login
        </v-btn>
        <p class="loginOr">Or</p>
        <br />

      <Glogin/>
        <br />
        <p class="loginOr">
          Don't have an account!
        </p>
        <v-card-text class="text-center">
          <a class="text-blue text-decoration-none" href="register" rel="noopener noreferrer" id="signup">
            Sign up now <v-icon icon="mdi-chevron-right"></v-icon>
          </a>
        </v-card-text>
      </v-card>
    </div>
  </div>
</template>
<script>

import { useVuelidate } from "@vuelidate/core";
import { required, maxLength, email, helpers } from "@vuelidate/validators";
import { useToast, POSITION } from "vue-toastification";
import http from "../../http-common";
import Glogin from '../../components/GoogleLogin/G-login.vue'

export default {
  components:{
    Glogin
  },
  validations() {
    return {
      username: {
        required: helpers.withMessage("User name is required", required),
        maxLength: helpers.withMessage(
          "Maximum length allowed is 30 characters",
          maxLength(30)
        ),
        $autoDirty: true,
      },
      e_Mail: {
        required: helpers.withMessage("Email is required", required),
        email: helpers.withMessage("Enter valid email address", email),
        $autoDirty: true,
      },

      password: {
        required: helpers.withMessage("Password is required", required),

        containsPasswordRequirement: helpers.withMessage(
          `Enter a valid password`,
          (value) =>
            /^(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*])(?=.*\d)[A-Za-z\d!@#$%^&*]{8,}$/.test(
              value
            )
        ),
        $autoDirty: true,
      },
    };
  },


  data: () => ({
    pausedFor2Seconds:false,
    alert: 0,
    e_Mail: "",
    v$: useVuelidate(),
    emailRules: [],
    password: "",
    passwsordRules: [],
    visible: false,
    alertMessage: "",
  }),
  setup() {
    const toast = useToast();
    return { toast };
  },
  methods: {
    async loginn() {
      this.alert = 0;
      this.v$.$validate();
      if (this.v$.e_Mail.$error || this.v$.password.$error) {
        console.log();
      } else {
        let result = await http
          .post("/user/login", {
            email: this.e_Mail,
            password: this.password,
          })
          .then((res) => {
            console.log(res);
            console.log(res.data.usertag);
            if (res.data.status == 0) {
              console.log();
              localStorage.setItem("TOKENN", JSON.stringify(res.data.token));
              window.location.href = "/vregistereduser";
            } else if (res.data.status == 1 && res.data.hasTag == "false") {


              window.location.href = "/tagpage";
              localStorage.setItem("TOKEN", JSON.stringify(res.data.token));

            } else {
              localStorage.setItem("TOKEN", JSON.stringify(res.data.token));
              localStorage.setItem("userTag", JSON.stringify(res.data.hasTag));

              window.location.href = "dashboard";
            }
  
          })
          .catch((error) => {
              this.pausedFor2Seconds = true;
    setTimeout(() => this.pausedFor2Seconds = false, 1500);
            if (error.response) {
              this.alert = 1
            }
            this.alertMessage = error?.response?.data?.message;
            this.closeAlert();

            let alertMessage = error?.response?.data?.message;
            console.log(alertMessage);
            let msg = error.response.data.message;
            this.toast.error(msg, {
              position: POSITION.TOP_CENTER,
              timeout: 1700,
            });
          });

        console.log(result.data);

      }

    },
    closeAlert() {
      setTimeout(() => {
        this.alert = false;
      }, 1700);
    },
  },

};
</script>

<style scoped>
.py-4 {
  padding-top: 3px !important
}

.loginOr {
  text-align: center;
  color: grey;
  padding-bottom: 1%
}

#googleButton {
  margin: 10px auto;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  flex-wrap: wrap;
  max-width: 320px;
}

.glogo {
  margin-top: 7%;
  margin-left: 43%;
  display: flex;
  flex-direction: row;
  width: 100%;
}

.ui.button.google-auth__button {
  padding: 12px 16px;
  background: #fff;
  box-shadow: 0 0 1px 0 rgba(0, 0, 0, 0.12), 0 1px 1px 0 rgba(0, 0, 0, 0.24);
  border-radius: 2px;
  font-size: 16px;
  line-height: 1.5em;
  letter-spacing: 0.22px;
  color: rgba(0, 0, 0, 0.54);
}

.ui.button.google-auth__button:hover {
  box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.12), 0 2px 2px 0 rgba(0, 0, 0, 0.24);
}

.ui.button.google-auth__button .google-auth__logo {
  display: inline-block;
  margin-right: 16px;
  height: 24px;
  width: 24px;
  line-height: 24px;
  vertical-align: top;
}

.ui.button.google-auth__button.disabled,
.ui.button.google-auth__button:disabled {
  background: #eee;
  box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.12), 0 2px 2px 0 rgba(0, 0, 0, 0.24);
}

@media screen and (max-width: 2000px) {
  .py-4 {
    height: fit-content;
    margin-top: -90px;
  }
}

@media screen and (max-width: 720px) {
  #cardA {
    width: 366px;

    display: inline-block;
  }

  .wf-logoo {
    margin-left: -24%;

  }
}

@media screen and (max-width: 498px) {
  #cardA {
    width: 366px;
    left: -25%;
    display: inline-block;

  }

  .wf-logoo {
    margin-left: -34%;

  }

}

@media screen and (max-width: 430px) {
  #cardA {
    width: 300px;
    left: -35%;
    display: inline-block;

  }

  #signup {
    margin-left: -45px
  }

  .wf-logoo {
    margin-left: -54%;

  }
}

@media screen and (max-width: 360px) {
  #cardA {
    width: 290px;
    left: -42%;
    display: inline-block;

  }

  #googleButton {
    margin-left: 2%;
  }

  .wf-logoo {
    margin-left: -54%;

  }
}

@media screen and (max-width: 316px) {
  #cardA {
    width: 250px;
    left: -35%;
    display: inline-block;

  }

  #googleButton {
    margin-left: 2%;
  }

  #signup {
    margin-left: -65px
  }

  .wf-logoo {
    margin-left: -34%;

  }

}
</style>