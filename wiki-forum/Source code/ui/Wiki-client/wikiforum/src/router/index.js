import { createRouter, createWebHistory } from 'vue-router'
import QuestionDetails from '../components/Questions/QuestionDetails/QuestionDetails.vue'
import TagList from '../components/TagList/TagList.vue'
import GoogleLoginView from '../components/GoogleLogin/GoogleLogin.vue'
import Errorpage from '../components/404 page/Errorpage.vue'
import Userlist from '../components/Userlist/Userlist.vue'
import Profile from '../components/Profile/Profile.vue'
import QuestionView from '../components/Questions/QuestionView/QuestionView.vue'
import Tagpage from '../components/Tagpage/Tagpage.vue'
import QuestionAdd from '../components/Questions/Askquestion/QuestionAdd.vue'
import Dashboard from '../components/DashBoard/DashBoard.vue'
import TagQuestion from '../components/TagQuestion/TagQuestion.vue'
import Forgotpassword from '../components/Forgotpassword/ForgotPassword.vue'
import Userpostedquestion from '../components/Questions/Currentuserpostedquestion/Currentuserpostedquestion.vue'
import Userpostedanswer from '../components/UserPostedAnswer/UserPostedAnswer.vue'
import EditQuestion from '../components/Questions/EditQuestion/EditQuestion.vue'
import EditAnswer from '../components/Questions/EditAnswer/EditAnswer.vue'
import Register from '../components/Register/register-user.vue'
import Registereduser from '../components/RegisteredUserLogin/RegisteredUserLogin.vue'
import Verifyregistereduser from '../components/VerifyRegisteredUser/VerifyRegisteredUser.vue'
import changepass from '../components/Changepass/Changepass.vue'
import newPass from '../components/Forgotpassword/newPass.vue'
import ActivityHistory from '../components/ActivityHistory/ActivityHistory.vue'
import ActivityHistoryAnswer from '../components/ActivityHistoryAnswer/ActivityHistoryAnswer.vue'

function guardMyroute(to, from, next) {
  let isAuthenticated = false;

  if (localStorage.getItem('TOKEN'))
    isAuthenticated = true;
  if (isAuthenticated) {
    next(); // allow to enter route
  }
  else {
    next('/'); // go to '/login';
  }
}



function guardRegLoginRoute(to, from, next){
  let isAuthenticated = false;
  if (localStorage.getItem("TOKEN") && localStorage.getItem("userTag") != "false")
    isAuthenticated = true;
  if (isAuthenticated) {
    next('/dashboard'); // allow to enter route
  }
  else {
    next(); 
  }
}

function guardLogin(to, from, next) {
  let isAllowed = false;

  if (localStorage.getItem('TOKEN'))
    isAllowed = true;
  if (isAllowed) {
    next('/dashboard'); // allow to enter route
  }
  else {
    next();
  }
}

const routes = [
  {
    path: '/',
    name: 'glogin',
    component: GoogleLoginView,
    beforeEnter: guardLogin
  },
  {
    path: '/fpassword',
    name: 'fpassword',
    component: Forgotpassword,
    beforeEnter: guardRegLoginRoute
  },
  {
    path: '/vregistereduser',
    name: 'Verifyregistereduser',
    component: Verifyregistereduser,
    beforeEnter: guardRegLoginRoute

  },
  
  {
    path: '/changepass',
    name: 'changepass',
    component: changepass,
    beforeEnter: guardMyroute,


  },
  {
    path: '/forgot-password',
    name: 'newPass',
    component: newPass,
    beforeEnter: guardRegLoginRoute
  },
  {
    path: '/question',
    // name: 'QuestionDetails',
    beforeEnter: guardMyroute,
    component: QuestionDetails
  },
  {
    path: '/registration',
    name: 'QuestionDetails',
    component: Registereduser,
    beforeEnter: guardRegLoginRoute

  },
  {
    path: '/register',
    name: 'register',  
    component: Register,
    beforeEnter: guardRegLoginRoute

  },
  {
    path: '/dashboard',
    name: 'DashBoardComponent',
    // beforeEnter: loginMyroute,
    beforeEnter: guardMyroute,
    component: Dashboard
  },
  {
    path: '/AskQuestion',
    name: 'QuestionAddComponent',
    beforeEnter: guardMyroute,
    component: QuestionAdd
  },
  {
    path: '/questionView/:questionId',
    name: 'QuestionView',
    beforeEnter: guardMyroute,
    component: QuestionView
  },
  {
    path: '/QView/:questionId',
    name: 'Userpostedquestion',
    beforeEnter: guardMyroute,
    component: Userpostedquestion
  },

  {
    path: '/tagList',
    component: TagList,
    beforeEnter: guardMyroute,
  },
  {
    path: '/profile',
    name: 'Profile-user',
    beforeEnter: guardMyroute,
    component: Profile
  },
  {
    path: '/userlist',
    component: Userlist,
    beforeEnter: guardMyroute,
  },
  {
    path: '/:pathMatch(.*)*',
    component: Errorpage,
    beforeEnter: guardMyroute,
  },
  {
    path: '/tagpage',
    name: 'tagpage',

    component: Tagpage
  },
  {
    path: '/tagquestion/:tagId',
    name: 'TagQuestion',
    beforeEnter: guardMyroute,
    component: TagQuestion
  },
  {
    path: '/edit-question/:questionId',
    name: 'edit-question',
    beforeEnter: guardMyroute,
    component: EditQuestion
  },
  {
    path: '/edit-answer/:answerId',
    name: 'edit-answer',
    beforeEnter: guardMyroute,
    component: EditAnswer
  },
  {
    path: '/history',
    component: ActivityHistory,
    beforeEnter: guardMyroute
  },
  {
    path: '/answerhistory',
    component: ActivityHistoryAnswer,
    beforeEnter: guardMyroute
  },
  {
    path: '/postedAnswer',
    component: Userpostedanswer,
    beforeEnter: guardMyroute
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router