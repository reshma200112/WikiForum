<template>
  <br>
  <br>
  <div class="log">
    <div class="py-4">
      <div id="logo" class="google-logo" title="Google">

      </div>




      <button id="googleButton" class="ui button google-auth__button" @click="login">
        <img class="google-auth__logo" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"
          alt="" />
        Sign in with Google
      </button>
      <br />



    </div>
  </div>
</template>

<script setup>
import { googleTokenLogin } from "vue3-google-login";
// import http from "../../http-common";

// import { useToast,  POSITION} from "vue-toastification";



// const toast = useToast()

const login = () => {
  googleTokenLogin().then((response) => {
    const data = {
      access_token: response.access_token,
    };

console.log(data);
  });

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