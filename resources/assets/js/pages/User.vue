<template>
    <div id="user" v-if="userLoadStatus === 2">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">{{ user.name }}</div>

                        <div class="panel-body">
                            <div class="description">
                                <div class="col-md-3">
                                    <div class="center">
                                        <img class="avatar" :src="user.avatar">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="right">{{ user.updated_at | moment('kk:mm - DD.MM.YY') }}</div>
                                    <div v-if="user.admin">{{ trans('catalog.role') }}: admin</div>
                                    <div v-else>{{ trans('catalog.role') }}: user</div>
                                    <div v-if="user.email">{{ trans('catalog.emailAddress') }}: {{ user.email }}</div>
                                </div>
                            </div>

                            <comments :from="'user'"></comments>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Comments from '../components/Comments';

    export default {
        data() {
            return {
                user: {},
                userLoadStatus: 0
            }
        },

        created() {
            this.getUser();
        },

        methods: {
            getUser() {
                this.userLoadStatus = 1;
                axios.get('api/users/' + this.$route.params.id)
                    .then((response) => {
                        this.user = response.data;
                        this.userLoadStatus = 2;
                    });
            }
        },

        components: {
            Comments
        }
    }
</script>

<style>
    .right { text-align: right; }
    .description { height: 120px; }
    .center { text-align: center; }
    img.avatar { border-radius: 50%; }
</style>