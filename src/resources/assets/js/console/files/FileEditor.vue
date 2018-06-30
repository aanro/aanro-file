<style scoped>
</style>

<template>

    <someline-form-panel
            @form-submit="onFormSubmit"
            v-loading.body="isLoading"
    >
        <template slot="PanelHeading">
            文章信息
        </template>

        <someline-form-group-input
                placeholder="标题"
                :rounded="true"
                v-model="form_data.title"
                :required="true"
        >
            <template slot="Label">标题</template>
        </someline-form-group-input>
        <someline-form-group-line/>

        <someline-form-group-image-upload v-model="form_data.someline_image"
                                          :limit-size="1000"
                                          :max-image="1"
        >
            <template slot="Label">封面图片</template>
        </someline-form-group-image-upload>
        <someline-form-group-line/>

        <div class="form-group">
            <label class="col-sm-2 control-label">分类</label>
            <div class="col-sm-9">
                <select name="account" v-model="form_data.someline_category_id" class="form-control m-b">
                    <option value="0" hidden>请选择分类</option>
                    <option v-for="someline_category in someline_categories" v-text="someline_category.category_name"
                            :value="someline_category.someline_category_id"></option>
                </select>
            </div>
        </div>
        <someline-form-group-line/>

        <someline-form-group-editor
                height="500px"
                :wang-image-upload="true"
                :format-text.sync="form_data.body_text"
                v-model="form_data.body_html"
        >
            <template slot="Label">内容</template>
        </someline-form-group-editor>
        <someline-form-group-line/>

        <someline-form-group-switch-list
                name="example_switch"
                :items="single_checkbox_items"
                v-model="form_data.is_pinned">
            <template slot="Label">置顶</template>
            <template slot="HelpText">是否置顶文章</template>
        </someline-form-group-switch-list>
        <someline-form-group-line/>

        <someline-form-group>
            <template slot="ControlArea">
                <button type="submit" class="btn btn-primary">保存</button>
            </template>
            <!--<pre class="m-t-sm m-b-none">{{ form_data }}</pre>-->
        </someline-form-group>

    </someline-form-panel>

</template>

<script>
    export default{
        props: {
            itemId: {
                type: String,
                required: false
            }
        },
        data(){
            return {

                isLoading: false,

                single_checkbox_items: [
                    {
                        text: '置顶',
                        value: 'yes',
                    }
                ],

                editor: null,

                form_data: {
                    title: null,
                    is_pinned: false,

                    body_html: null,
                    body_text: null,

                    someline_image: null,

                    someline_category_id: 0,
                },

                data: null,
                someline_categories: [],
                remoteTagsLoading: false,

            }
        },
        computed: {
            inEditMode(){
                return !!this.itemId;
            }
        },
        components: {},
        mounted(){
            console.log('Component Ready.');

            this.fetchCategoryData();

            if (this.inEditMode) {
                this.fetchData();
            }
        },
        watch: {},
        events: {},
        methods: {
            fetchData() {
                console.log('fetchData');

                if (this.isLoading) {
                    return;
                }
                this.isLoading = true;

                this.$api
                    .get(`articles/${this.itemId}`, {
                        params: {
                            include: 'tags'
                        }
                    })
                    .then(this.updateDataFromResponse, this.handleResponseError)
                    .finally(this.handleFormResponseComplete);
            },
            fetchCategoryData() {

                this.$api
                    .get('categories', {
                        params: {
                            all: true
                        }
                    })
                    .then((response) => {
                        this.someline_categories = response.data.data;
                    }, this.handleResponseError)

            },
            updateDataFromResponse(response){
                let data = response.data.data;

                this.form_data = Object.assign({}, this.form_data, data);
                this.form_data.someline_image = data.someline_image_url;
                this.form_data.someline_category_id = data.someline_category_id;

            },
            arrayColumn(array = [], column) {
                let result = [];
                array.forEach(function (item) {
                    result.push(item[column]);
                });
                return result;
            },
            onFormSubmit(){
                console.log('onFormSubmit');

                this.isLoading = true;

                if (this.inEditMode) {
                    this.$api.put(`/articles/${this.form_data.someline_article_id}`, this.form_data)
                        .then(this.handleUpdatedResponseSuccess, this.handleResponseError)
                        .finally(this.handleFormResponseComplete);
                } else {
                    this.$api.post('articles', this.form_data)
                        .then(this.handleCreatedResponseSuccess, this.handleResponseError)
                        .finally(this.handleFormResponseComplete);
                }

            },
            handleCreatedResponseSuccess(response) {
                this.$message({
                    message: '保存成功',
                    type: 'success'
                });
                this.redirectToUrlFromBaseUrl(`/console/articles/${response.data.data.someline_article_id}`);
            },
            handleUpdatedResponseSuccess(response) {
                this.$message({
                    message: '更新成功',
                    type: 'success'
                });
                this.redirectToUrlFromBaseUrl(`/console/articles/${this.itemId}`);
            },
            handleResponseError(error){

                var error_message = '请求失败';
                try {
                    var response_error_message = error.response.data.message;
                    if (response_error_message) {
                        console.error(response_error_message);
                        error_message = this.$options.filters.truncate(response_error_message, 80);
                    }
                } catch (e) {
                    console.error(e.stack);
                }

                this.$message({
                    message: error_message,
                    type: 'error'
                });

            },
            handleFormResponseComplete(){

                this.isLoading = false;

            },
        },
    }
</script>