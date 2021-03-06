<template>
    <div id="articles" v-if="usersLoadStatus === 2">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ trans('catalog.users') }}
            </div>
            <div class="panel-body">
                <form @submit.prevent="getUsers">
                    <search description="Search By Name"></search>
                </form>
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th>
                            <a class="button" @click="initSort('name')">
                                {{ trans('catalog.name') }}
                            </a>
                        </th>
                        <th>
                            <a class="button" @click="initSort('email')">
                                {{ trans('catalog.emailAddress') }}
                            </a>
                        </th>
                        <th>
                            <a class="button" @click="initSort('admin')">
                                {{ trans('catalog.admin') }}
                            </a>
                        </th>
                        <th>
                            <a class="button" @click="initSort('ban')">
                                {{ trans('catalog.ban') }}
                            </a>
                        </th>
                        <th>
                            <a class="button" @click="initSort('created_at')">
                                {{ trans('catalog.registeredAt') }}
                            </a>
                        </th>
                        <th>{{ trans('catalog.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(user, index) in users.data" v-if="user.id !== $root.auth.id">
                        <td>
                            <router-link :to="{ name: 'user', params: { id: user.id } }">
                                {{ user.name }}
                            </router-link>
                        </td>
                        <td>{{ user.email}}</td>

                        <td v-if="user.admin">{{ trans('catalog.yes') }}</td>
                        <td v-else>{{ trans('catalog.no') }}</td>

                        <td v-if="user.ban">{{ trans('catalog.yes') }}</td>
                        <td v-else>{{ trans('catalog.no') }}</td>

                        <td>{{ user.created_at | moment('kk:mm:ss - DD.MM.YYYY') }}</td>
                        <td>
                            <button class="btn btn-warning" @click="initBan(index)">{{ trans('catalog.ban') }}</button>
                            <button class="btn btn-danger" @click="setAdmin(index)" v-if="!user.admin">{{ trans('catalog.admin') }}</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="modal fade" tabindex="-1" role="dialog" id="ban_user_modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title">{{ trans('catalog.banUser') }}</h4>
                            </div>
                            <form enctype="multipart/form-data">
                                <div class="modal-body">
                                    <errors :content="errors"></errors>
                                    <div class="form-group">
                                        <input type="text" name="ban" class="form-control" placeholder="Enter User Ban" v-model="user_ban.ban">
                                        <p class="form-text text-muted">
                                            {{ trans('catalog.format') }}: yyyy-mm-dd hh:ii:ss
                                        </p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('catalog.close') }}</button>
                                    <button type="button" class="btn btn-primary" @click="banUser">{{ trans('catalog.ban') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="paginator">
                    <pagination :data="users" :limit="5" v-on:pagination-change-page="getUsers"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Errors from '../Errors';
    import Search from '../Search';

    export default {
        data() {
            return {
                users: {},
                usersLoadStatus: 0,
                user_ban: {},
                errors: [],
                keywords: '',
                sort: {
                    field: 'updated_at',
                    direction: 'desc',
                }
            }
        },

        created() {
            this.getUsers();
        },

        methods: {
            getUsers(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }

                this.usersLoadStatus = 1;
                axios.get('api/admin/users/?page='+page+'&keywords='+this.keywords+'&field='+this.sort.field+'&direction='+this.sort.direction)
                    .then((response) => {
                        this.users = response.data;
                        this.usersLoadStatus = 2;
                    });
            },

            switchDirection() {
                this.sort.direction = this.sort.direction === 'desc' ? 'asc' : 'desc';
            },

            initSort(field) {
                this.sort.field = field;
                this.switchDirection();
                this.getUsers();
            },

            initBan(index) {
                this.errors = [];
                $('#ban_user_modal').modal('show');
                this.user_ban = this.users.data[index];
            },

            banUser() {
                axios.put('api/users/' + this.user_ban.id, this.user_ban)
                    .then(() => {
                        $('#ban_user_modal').modal('hide');
                    })
                    .catch((error) => {
                        this.errors = [];

                        if (error.response.data.errors.ban) {
                            Array.prototype.push.apply(this.errors, error.response.data.errors.ban);
                        }
                    })
            },

            setAdmin(index) {
                axios.put('api/users/' + this.users.data[index].id + '/admin')
                    .then(() => {
                        this.getUsers();
                    });
            }
        },

        components: {
            Errors,
            Search
        }
    }
</script>