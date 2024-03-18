<template>
    <div class="mobile-sidebar">
        <div id="mySidepanel" class="sidepanel">
            <div id="mobileSidebarHeader">
                <div id="mobileSideBarCloseBtn"> <a href="javascript:void(0)" id="closebtn" @click="closeNav()">x</a></div>
                <div id="ms-headerwikititle-logo">
                    <div id="mobileSideBarLogo"> <img :src="require('@/assets/wiki.png')" alt="" class="ms-wf-logo" /></div>
                    <div id="mobileSideBarTitle">
                        <h2 class="ms-wikiforum-title"> WIKI-FORUM</h2>
                    </div>
                </div>
            </div>
            <div id="mobilesidebarLinks">
                <a @click="handleClick('/dashboard')" class="mobilesidepanelLinks">
                    <fa class="fas" icon="house" />
                    <span class="ms-navitem">Dashboard</span>
                </a>
                <a @click="handleClick('/profile')" class="mobilesidepanelLinks">
                    <fa class="fas" icon="user" />
                    <span class="ms-navitem">Profile</span>
                </a>
                <a @click="handleClick('/tagList')" class="mobilesidepanelLinks">
                    <fa class="fas" icon="tags" />
                    <span class="ms-navitem">Tags</span>
                </a>
                <a @click="handleClick('/userlist')" class="mobilesidepanelLinks">
                    <fa class="fas" icon="users" />
                    <span class="ms-navitem">Users</span>
                </a>

                <a @click="handleClick('/AskQuestion')" class="mobilesidepanelLinks">
                    <fa class="fas" icon="question" />
                    <span class="ms-navitem">Ask Question</span>
                </a>

                <a v-if="this.userType == 0" @click="handleClick('/changepass')" class="mobilesidepanelLinks">
                    <fa class="fas" icon="cog" />
                    <span class="ms-navitem">Change password</span>
                </a>
                <a href="#" @click="clear()" class="mobilesidepanelLinks">
                    <fa class="fas" icon="sign-out-alt" />
                    <span class="ms-navitem">Logout</span>
                </a>
            </div>
        </div>
        <div id="ms-openbtn"><button class="mobileSideBarOpenBtn" @click="openNav()" v-if="userTag == true">☰</button></div>
    </div>
</template>
  
<script>
import Swal from 'sweetalert2';
import http from '../http-common';
export default {
    name: 'mobilesidebar-component',
    data() {
        return {
            userType: 1,
            userTag: false
        }
    },
    async beforeMount() {
        if (localStorage.getItem('userTag') != null) {
            this.userTag = true
        }
        else {
            this.userTag = false
        }
        await this.getCurrentUser();
    },
    methods: {
        async getCurrentUser() {
            http.get("/getuser").then((response) => {
                this.userType = response.data.user.type;
            });
        },
        openNav() {
            document.getElementById('mySidepanel').style.width = '100vw';
        },
        closeNav() {
            document.getElementById('mySidepanel').style.width = '0';
        },
        handleClick(route) {
            this.$router.push(route).then(() => {
                document.getElementById('mySidepanel').style.width = '0';
            });
        }
        ,
        async clear() {
            document.getElementById('mySidepanel').style.width = '0';
            Swal.fire({
                title: 'Logout!',
                text: "Are you sure want to logout?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Logout',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem("TOKEN");
                    localStorage.removeItem("User Details");
                    localStorage.removeItem("User image");
                    localStorage.removeItem("User name");
                    localStorage.removeItem("email");
                    localStorage.removeItem("questionId");
                    localStorage.clear();
                    window.location.href = "/";
                }
            })
        },
    },
};
</script>
  
<style>
body {
    font-family: 'Lato', sans-serif;
}

.sidepanel {
    width: 0;
    position: fixed;
    z-index: 999;
    height: 100vh;
    top: 0;
    left: 0;
    background-color: white;
    overflow-x: hidden;
    transition: 0.3s;
    padding-top: 60px;
}

.mobilesidepanelLinks {
    display: flex;
    flex-direction: row;
}

.sidepanel a {
    border-bottom: 1px solid #dbdbdb;
    width: auto !important;
    padding: 20px 16px 20px 6px;
    text-decoration: none;
    color: rgb(60, 77, 105);
    transition: 0.2s;
}

.sidepanel a:hover {
    color: #e8dddd;
    background-color: rgb(60, 77, 105);
}

.sidepanel #closebtn {
    border: none;
    font-size: 20px;
    padding-bottom: 0;
    padding-left: 15px;
}

#closebtn:hover {
    color: #444;
    background-color: transparent !important;
}

.mobileSideBarOpenBtn {
    font-size: 30px;
    cursor: pointer;
    background-color: transparent;
    color: white;
    border: none;
}

#mobileSidebarHeader {
    top: 0;
    width: 100%;
    height: 70px;
    position: absolute;
    display: flex;
    flex-direction: row;
    box-shadow: 0px 0px 14px 0px rgb(78 122 131 / 41%);
}

#ms-headerwikititle-logo {
    display: flex;
    width: 100%;
    justify-content: center;
}

.openbtn:hover {
    background-color: #444;
}

#mobileSideBarLogo {
    padding-top: 10px;
}

.ms-wf-logo {
    width: 45px;
    height: 45px;
}

.ms-wikiforum-title {
    color: rgb(60, 77, 105);
    padding-top: 18px;

}

#ms-openbtn {
    padding-left: 13px;
}

#mobilesidebarLinks {
    padding-top: 15px;
    padding-left: 36px;
    padding-right: 36px;
}

.ms-navitem {
    position: relative;
    bottom: 3px;
    margin-left: 8px;
}
.mobile-sidebar{
    display: flex;
    align-items: center;
}
</style>
  