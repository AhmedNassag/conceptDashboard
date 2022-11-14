<template>
    <div :class="['main-wrapper',link.includes($route.name) ? 'login-body' : '']">

			<!-- Header -->
            <admin-header v-if="!link.includes($route.name)" />

			<!-- /Header -->

			<!-- Sidebar -->
            <sidebar v-if="!link.includes($route.name)" />

            <router-view v-slot="{ Component }">
                <transition name="scale" mode="out-in">
                    <component :is="Component" />
                </transition>
            </router-view>
			<!-- /Sidebar -->

		<!-- /Main Wrapper -->
	</div>
</template>

<script>
    import adminHeader from "./components/admin/general/adminHeader";
    import sidebar from "./components/admin/general/Sidebar";
    import { onMounted } from "@vue/runtime-core";
    import { useI18n } from "vue-i18n";

    export default {
        setup() {

            const { t,locale } = useI18n();

            onMounted(() => {

                if (localStorage.getItem("user")) {
                    Echo.private(
                        "App.Models.User." + JSON.parse(localStorage.getItem("user")).id
                    ).notification((notification) => {
                        VanillaToasts.create({
                            // notification title
                            title: `${t("global.Notification")}`,

                            // notification message
                            text: notification.data.message ?  notification.data.message:`${t("global.ThereIsANewNotification")}`,

                            // success, info, warning, error   / optional parameter
                            type: "warning",

                            timeout: 15000,

                            // path to notification icon
                            icon:  notification.data.image ? notification.data.image: "/web/img/logo.png",

                            // topRight, topLeft, topCenter, bottomRight, bottomLeft, bottomCenter
                            positionClass: locale.value == "en" ? "topLeft" : "topRight",

                            // auto dismiss after 5000ms
                        });
                    });
                }
            });
        },
        mounted() {
        },
        components:{
            adminHeader,
            sidebar,
        },
        data(){
            return {
                link : ['loginLang','login','Page404','forgetPassword','resetPassword']
            }
        }
    }
</script>

<style scope>

.scale-enter-active,
.scale-leave-active {
    transition: all 0.5s ease;
}
.notifications .media > .avatar {
    margin: 0 10px !important;
}

.scale-enter-from,
.scale-leave-to {
    opacity: 0;
    transform: scale(0.9);
}

</style>
