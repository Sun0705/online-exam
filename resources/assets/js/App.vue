<template>
    <el-container>
        <el-header>
            <navbar></navbar>
        </el-header>
        <el-main>
            <router-view></router-view>
        </el-main>
    </el-container>
</template>

<script>
    import Navbar from './components/user/Navbar.vue';

    export default {
        components: {
            Navbar,
        },
        data() {
            return {};
        }
    }
</script>

<style lang="scss" scoped>
    .container {
        font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
    }
</style>