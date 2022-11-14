<template>
    <li class="nav-item dropdown">
        <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
            <i class="feather-bell"></i> <span v-if="count" class="badge badge-pill">{{ count }}</span>
        </a>
        <div class="dropdown-menu notifications">
            <div class="topnav-dropdown-header" v-if="count">
                <span class="notification-title">{{$t("notification.Notifications")}}</span>
                <a href="#" @click.prevent="clearAll" class="clear-noti">{{$t("notification.clear")}}</a>
            </div>
            <div class="noti-content" v-if="count">
                <ul class="notification-list">

                    <li class="notification-message" :key="index" v-for="(notification,index) in notifications">

                        <!-- start trust Design -->
                        <router-link
                            :to="{name:notification.data.name,params: {id:notification.data.id}}"
                        >
                            <div class="media d-flex" @click="clearItem(notification.id,index)">
                                <span class="avatar avatar-sm flex-shrink-0">
                                    <img
                                        class="avatar-img rounded-circle"
                                        :src="notification.data.image?notification.data.image:''"
                                        onerror="src='/web/img/logo.png'"
                                    >
                                </span>
                                <div class="media-body flex-grow-1">
                                    <p class="noti-details">
                                        {{notification.data.message}}
                                    </p>
                                    <p class="noti-time"><span class="notification-time">{{notification.data.timeDate}}</span></p>
                                </div>
                            </div>
                        </router-link>
                        <!-- end trust Design -->

                    </li>
                </ul>
            </div>
            <div class="topnav-dropdown-footer">
                <router-link
                    :to="{name:'notification'}"
                >
                    {{$t('notification.all')}}
                </router-link>
            </div>
        </div>
    </li>
</template>

<script>
import {ref,onMounted} from 'vue';
import adminApi from "../../../api/adminAxios";

export default {
    name: "notification",
    setup(){

        let notifications = ref([]);
        let count = ref(0);

        let notificationNotRead = () => {
            if(localStorage.getItem("user")){
                adminApi.get(`/v1/dashboard/getNotNotRead`)
                    .then((res) => {
                        notifications.value = res.data.data.Notifications;
                        count.value = res.data.data.count;
                    })
                    .catch((err) => {
                        console.log(err.response);
                    })
            }
        };

        let clearItem = (id,index) => {
            if(localStorage.getItem("user")){
                adminApi.post(`/v1/dashboard/clearItem/${id}`)
                    .then((res) => {
                        notifications.value.splice(index,index + 1);
                        count.value -= 1;
                    })
                    .catch((err) => {
                        console.log(err.response);
                    })
            }
        };

        let clearAll = () => {
            if(localStorage.getItem("user")){
                adminApi.post(`/v1/dashboard/getNotNotRead`)
                    .then((res) => {
                        notifications.value = [];
                        count.value = 0;
                    })
                    .catch((err) => {
                        console.log(err.response);
                    })
            }
        };

        onMounted(() => {
            notificationNotRead();
        });

        if(localStorage.getItem("user")){
            Echo.private('App.Models.User.'+JSON.parse(localStorage.getItem("user")).id)
                .notification((notification) => {
                    console.log(notification);
                    notifications.value.unshift(notification);
                    count.value += 1;
                });
        }

        return {notifications,count,clearItem,clearAll};
    }
}
</script>

<style scoped>
.notifications .media > .avatar {
    margin: 0 10px !important;
}
</style>
