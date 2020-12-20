<template>
<div>
    <div class="card" style="width: 18rem;">
        <img class="card-img-top" :src="img_src" v-if="img_src !== 'null'" alt="Profile Pic">
        <div v-else>No image available</div>
        <button  
        style="border-radius: 0" 
        class="btn btn-primary" 
        type="button"
        @click="toggleBtn">
            Change photo
        </button>
    </div>
    <div v-if="showUploadBtn" id="simpleCollapse">
        <div class="row p-3">
            <input type="file" id="fileUpload">
        </div>
        <div class="row p-3">
        <button 
        type="button" 
        id="uploadbtn" 
        class="btn btn-sm btn-success" 
        @click="uploadFile">Upload</button>
    </div>
    </div>
</div>
</template>

<script>
    export default {
        name: "ProfileComponent",
        props: {
            curAgent: {
                type: Object
            }
        },
        data(){
            return {
                token: '',
                showUploadBtn: false,
            }
        },
        mounted() {
            this.token = this.getCookie('token');
        },
        methods: {
            uploadFile(){
                $('#uploadbtn').attr('disabled', 'true');
                let file = $('#fileUpload')[0].files[0];
                let fd = new FormData();
                fd.append('profile_pic', file, 'fileUpload');
                fd.append('id', this.$props.curAgent.id);
                $.ajax({
                    type: 'POST',
                    url: '/api/upload_pic',
                    headers: {
                        Authorization: "Bearer " + this.token
                    },
                    processData: false,
                    contentType: false,
                    data: fd
                }).done(resp => {
                    $('#uploadbtn').attr('disabled', false);
                    $('#fileUpload').val('');
                    this.$emit('uploadComplete');
                    alert(resp.msg);
                });
            },
            toggleBtn(){
                this.showUploadBtn ? this.showUploadBtn = false : this.showUploadBtn = true;
            }
        },
        computed: {
            img_src(){
                return encodeURI("storage/" + this.curAgent.picture_url);
            }
        }
    }
</script>

<style scoped>

</style>
