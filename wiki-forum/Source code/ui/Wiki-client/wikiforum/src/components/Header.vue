<template>
    <div class="header-container" v-if="tokenFlag == true" ref="header" @click="handleHeaderClick">
        <div class="wiki-Data">
            <mobile-sidebar ref="sidebar"></mobile-sidebar>
            <a class="wiki-logo-router" id="wiki-routerLink" :active-class="wiki - logo - router">
                <div class="wikiLogoToRoute" @click="loadDashboard()">
                    <img :src="require('@/assets/wiki.png')" alt="" class="wf-logo" />
                </div>
                <div class="wiki-name" @click="loadDashboard()">
                    <span>Wiki Forum</span>
                </div>
            </a>
        </div>
        <div class="heading">
            <h3 id="forwidth" class="headerPageHeading">{{ routePath }}
                <v-tooltip activator="parent" location="bottom" v-if="tooltipFlag">{{ tooltipTagName }}</v-tooltip>
            </h3>

        </div>
        <!-- user details on header -->
        <div class="userDataonHeader">
            <div class="profileImage">
                <img v-if="User.profile_picture != null" :src="User.profile_picture" referrerpolicy="no-referrer" alt=""
                    class="user-ProfilePic" @click="navToProfile()" />
                <img class="user-ProfilePic" v-else v-bind:src="require('@/assets/avatar.png')" alt=""
                    @click="navToProfile()" id="user-profile-image" />
            </div>
            <div class="userBadge">
                <img class="user-reputation" v-if="User.points >= 5000" :src="require('@/assets/gold.png')" alt=""
                    @click="navToProfile()" />
                <img class="user-reputation" v-else-if="User.points >= 1000" :src="require('@/assets/silver.png')" alt=""
                    @click="navToProfile()" />
                <img class="user-reputation" v-else-if="User.points >= 100" :src="require('@/assets/bronze.png')" alt=""
                    @click="navToProfile()" />
                <img class="user-noreputation" v-else :src="require('@/assets/nopoint.jpg')" alt=""
                    @click="navToProfile()" />
            </div>
            <div class="score-div">
                <span v-if="User.points > 0" class="user_score" @click="navToProfile()">Points:{{ User.points }}</span>
                <span v-else class="user_score" @click="navToProfile()">Points:0</span>
            </div>
            <!-- details end -->
            <div class="notificationbellandcount">
                <div class="numberCount">
                        <span class="badge" v-if="notifications != '' || notifications.count == 0">{{
                            notificationCountValue
                        }}</span>
                    </div>
                <span class="bell-icon" @click="toggleDropdown">
                    <fa id="iconNoti" icon="bell" class="notification-bell"></fa>
                </span>
            </div>
            <notification-dropdown v-if="isDropdownOpen" @close="closeDropdown" :notifications="notifications"></notification-dropdown>
        </div>
    </div>
</template>

<script>
import MobileSidebar from "../components/MobileSideBar.vue";
import NotificationDropdown from '../components/NotificationDropdown.vue';
import http from "../http-common";
export default {
    name: 'header-component',
    data() {
        return {
            isDropdownOpen: false,
            interval: null,
            notificationCount: 0,
            tagNameOnTooltipActive: '',
            tagQuestionFlag: false,
            tooltipTagName: '',
            User: {},
            userType: undefined,
            notifications: {},
            down: false,
            tokenFlag: false,
            tooltipFlag: false
        }
    },
    components: {
        MobileSidebar,
        NotificationDropdown,
    },
    mounted() {
        document.addEventListener('click', this.handleClickOutside);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside);
    },
    beforeMount() {
        this.getAllNotification();
        this.getUser();
        let token = localStorage.getItem('TOKEN');
        if (token != null) {
            this.tokenFlag = true
        }
        else this.tokenFlag = false
    },
    watch: {
        notificationCountValue() {
            this.getAllNotification();
        }
    },
    computed: {
        notificationCountValue() {
            return this.notificationCount;
        },
        routePath() {
            let path = this.$route.path;
            if (path == '/dashboard') {
                return 'Dashboard'
            }
            else if (path.includes('/tagquestion')) {
                let tag = localStorage.getItem('chosenTag');
                if (tag.length > 12) {
                    return this.setTag(tag);
                }
                return tag;
            }
            else if (path.includes('/userlist')) {
                return 'Users List'
            }
            else if (path == ('/tagList')) {
                return 'Tag List'
            }
            else if (path.includes('/profile')) {
                return 'Profile'
            }
            else if (path === ('/question')) {
                return 'Your Questions'
            }
            else if (path === ('/AskQuestion')) {
                return 'Ask Question'
            }
            else if (path.includes('/edit-question')) {
                return 'Edit Question'
            }
            else if (path.includes('/edit-answer')) {
                return 'Edit Answer'
            }
            else if (path === '/changepass') {
                return 'Change Password'
            }
            return null;
        },
    },
    created() {
        if (localStorage.getItem('userTag')) {
            this.interval = setInterval(() => {
                this.getUser();
                this.getAllNotification();
            }, 3000)
        }
    },
    unmounted() {
        clearInterval(this.interval)
    },
    methods: {
        handleHeaderClick(event) {
            if (
                !event.target.closest('.bell-icon') &&
                !event.target.closest('.notification-dropdown')
            ) {
                this.closeDropdown();
            }
        },
        handleClickOutside(event) {
            if (this.isDropdownOpen && !this.$el.contains(event.target)) {
                this.isDropdownOpen = false;
            }
        },
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },
        closeDropdown() {
            this.isDropdownOpen = false;
        },
        openSidebar() {
            this.$refs.sidebar.openNav();
        },
        async getAllNotification() {
            http
                .get("/user/notification")
                .then((response) => {
                    this.notifications = response.data;
                    this.notificationCount = response.data.count;
                })
                .catch(function (error) {
                    throw error;
                });
        },
        getUser() {
            http.get("/getuser").then((response) => {
                this.User = response.data.user;
                this.userType = response.data.user.type
            }).catch(function (error) {
                if (error.response.status == 401) {
                    if (localStorage.getItem('userTag')) {
                        localStorage.clear();
                        window.location.href = '/';
                       window.location.reload();
                    }
                }
            });
        },
        setTag(tagName) {
            let screenWidth = window.innerWidth;

            this.tooltipTagName = tagName;
            this.tooltipFlag = true;
            if (screenWidth < 460) {
                tagName = tagName.slice(0, 12) + '...';
            }
            return tagName;
        },
      
        async NotificationDetails(Qid) {
            http.post("/questionviews/" + Qid, {}).then(() => {
                this.getAllNotification();
            });
            this.$router.push("/questionView/" + Qid).then(() => {
                this.$router.go();
            });
        },
        deleteNotification() {
            http.put("user/notification/delete").then(() => {
                window.location.reload();
            });
        },
        viewDetails(id) {
            http.post("/questionviews/" + id, {}).then(() => { });
            this.$router.push("/questionView/" + id);
        },
        navToProfile() {
            this.$router.push("/profile");
        },
        loadDashboard() {
            this.$router.push('/dashboard').then(() => {
                this.$router.go();
            })
        }
    }
}
</script>

<style scoped>
.wiki-Data {
    display: flex;
}

.header-container {

    background-color: rgb(60, 77, 105);
    height: 90px;
    display: grid;
    width: 100vw;
    grid-template-columns: 15% 65% 20%;
    position: fixed;
    z-index: 9999;
}

.user-Pro {
    border-radius: 50px;
    top: 5px;
    color: rgb(110, 109, 109);
    cursor: pointer;
}

.user_rep {
    border-radius: 50px;
    color: rgb(110, 109, 109);
    cursor: pointer;
}

.user_points {
    position: fixed;
    color: rgb(110, 109, 109);
    cursor: pointer;
}

.clearAllnotifications {
    margin-left: 40%;
    color: blue !important;
    cursor: pointer;
}

.colorNotification0 {
    color: rgb(116, 116, 116) !important;
}

.colorNotification1 {
    color: rgb(0, 0, 0) !important;
}

/* newly added when new header is created */
.userDataonHeader {
    /* display: flex;
    flex-direction: row; */
    margin-top: 19px;
    display: grid;
    grid-template-columns: auto auto auto auto;
    gap: 10px;
    left: 0;
}

#wiki-routerLink {
    display: flex;
    height: fit-content;
    width: fit-content;
}

.wikiLogoToRoute {
    cursor: pointer;
}

.wiki-name {
    height: fit-content;
    margin-top: 19px;
    color: white;
    cursor: pointer;
}

.headerPageHeading {
    color: #91ddf9;
    margin-top: 29px;
    width: max-content;
}

.user-ProfilePic {
    cursor: pointer;
    border-radius: 50px;
    width: 45px;
    height:45px;
}

.score-div {
    height: fit-content;
    width: fit-content;
    cursor: pointer;
    margin-top: 10%;
}

.user-reputation {
    cursor: pointer;
    width: 45px;
}

.user-noreputation {
    cursor: pointer;
    border-radius: 40px;
    height: 45px;
    width: 46px !important;
    border: solid 2px red;
}

.userPicture {
    border-radius: 50%;
    width: 60px;
    height: 60px;
}

.notification-icon {
    cursor: pointer;
    width: fit-content;
}

.wiki-logo-router {
    text-align: center;
    display: flex;
    background-color: transparent;
}

.wiki-logo-router span {
    font-weight: bold;
    padding-left: 15px;
    font-size: 18px;
    margin-top: 16px;
    margin-left: 0px;
    text-transform: uppercase;
}

.wiki-logo-router .wf-logo {
    width: 63px;
    height: 60px;

}

.bell-icon {
    width: fit-content;
    height: fit-content;
}

.numberCount {
    display: flex;
    order: 1;
    justify-content: center;
    height: fit-content;
    width: fit-content;
}
.notificationbellandcount{
    display: flex;
}
</style>