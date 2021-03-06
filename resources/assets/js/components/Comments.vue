<template>
    <div id="comments">
        <comment-create v-if="from === 'article'"></comment-create>
        <div v-if="commentsLoadStatus === 2 && comments.data.length > 0">
            <h4 class="comment-title">{{ trans('catalog.lastComments') }}</h4>
            <div v-for="(comment, index) in comments.data" class="clearfix comment">
                <div class="col-md-3">
                    <div class="comment-from" v-if="from === 'article'">
                        <h5>
                            <router-link :to="{ name: 'user', params: { id: comment.user_id } }">
                                {{ comment.user.name }}
                            </router-link>
                        </h5>
                        <router-link :to="{ name: 'user', params: { id: comment.user_id } }">
                            <img class="user-avatar" :src="comment.user.avatar">
                        </router-link>
                        <div v-if="comment.user_id === $root.auth.id">
                            <button @click="initUpdate(index)" class="btn btn-success btn-xs">Edit</button>
                        </div>
                    </div>
                    <div class="comment-from" v-else>
                        <h5>
                            <router-link :to="{ name: 'article', params: { id: comment.article_id } }">
                                {{ comment.article.title}}
                            </router-link>
                        </h5>
                        <router-link :to="{ name: 'article', params: { id: comment.article_id } }">
                            <img class="article-image" :src="comment.article.image">
                        </router-link>
                    </div>
                </div>
                <div class="col-md-9 comment-content">
                    <p>{{ comment.body }}</p>
                    <span class="comment-likes">
                        <a class="btn" @click="commentLike(index)" v-if="!comment.likes"><i class="fa fa-heart-o" aria-hidden="true"></i> 0</a>
                        <a class="btn" @click="commentLike(index)" v-else><i class="fa fa-heart-o" aria-hidden="true"></i> {{ comment.likes.length }}</a>
                    </span>
                    <span class="comment-date">{{ comment.updated_at | moment('kk:mm:ss - DD.MM.YYYY') }}</span>
                </div>

                <div v-if="from === 'article'" class="modal fade" tabindex="-1" role="dialog" id="update_comment_modal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">{{ trans('catalog.updateComment') }}</h4>
                            </div>
                            <div class="modal-body">
                                <errors :content="errors"></errors>
                                <div class="form-group">
                                    <textarea cols="30" rows="5" class="form-control" placeholder="Task Description" v-model="update_comment.body"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('catalog.close') }}</button>
                                <button type="button" @click="updateComment" class="btn btn-primary">{{ trans('catalog.update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="paginator">
                <pagination :data="comments" :limit="5" v-on:pagination-change-page="getComments"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
    import CommentCreate from '../components/CommentCreate';
    import Errors from '../components/Errors';

    export default {
        props: ['from'],

        data() {
            return {
                comments: {},
                commentsLoadStatus: 0,
                update_comment: {},
                errors: []
            }
        },

        created() {
            this.getComments();
        },

        methods: {
            getComments(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }

                this.commentsLoadStatus = 1;
                axios.get('api/comments/' + this.$route.params.id + '/' + this.from + '/?page=' + page)
                    .then((response) => {
                        this.comments = response.data;
                        this.commentsLoadStatus = 2;
                    });
            },

            commentLike(index) {
                axios.post('api/like/comments/' + this.comments.data[index].id)
                    .then((response) => {
                        if (!response.data.unliked) {
                            this.comments.data[index].likes.push('pushed');
                        } else {
                            this.comments.data[index].likes.pop();
                        }
                    });
            },

            initUpdate(index) {
                this.errors = [];
                $('#update_comment_modal').modal('show');
                this.update_comment = this.comments.data[index];
            },

            updateComment() {
                axios.put('api/comments/' + this.update_comment.id, this.update_comment)
                    .then(() => {
                        $('#update_comment_modal').modal('hide');
                    })
                    .catch((error) => {
                        this.errors = [];

                        if (error.response.data.errors.body) {
                            Array.prototype.push.apply(this.errors, error.response.data.errors.body);
                        }
                    })
            }
        },

        components: {
            CommentCreate,
            Errors
        }
    }
</script>

<style>
    .comment { padding: 20px 0; }
    .comment-title, div.paginator, .comment-from { text-align: center; }
    .comment-date { float: right; }
    .comment-content { padding: 20px; }
    img.article-image { width: 100px; height: 100px; }
    img.user-avatar { border-radius: 50%; }
</style>