<template>
  <el-upload
    class="avatar-uploader"

    action=""
    :http-request="fnUploadRequest"
    :show-file-list="false"
    :on-success="handleAvatarSuccess"
  >
    <img v-if="imageUrl" :src="imageUrl" class="avatar">
    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
  </el-upload>
</template>

<script>
  import myconfig from '@/config'

  import oss from '@/utils/aliOss'

  import { PostDataBySave } from '@/api/oss'

  export default {
    props: {
      imageUrl: String,
      type: {
        type: String,
        default: '未分组'
      }
    },
    data() {
      return {
        url: process.env.BASE_API + 'upload' // api 的 base_url
      }
    },
    created() {

    },
    methods: {

      async fnUploadRequest(option) {

        let op = oss.ossUploadFile(option)
        op.then(res => {
          res.url = myconfig.VUE_APP_BASE_IMG_OSS + res.url
          console.log(res)
          let temp={
            name:res.name,
            url:res.url,
            type:this.type
          }
          PostDataBySave(temp).then(res=>{
            console.log(res)
          })
          this.$emit('showParentComp', res)
        })

      },
      handleAvatarSuccess(res, file) {
        // this.imageUrl = res.path;
        // this.$emit('showParent Comp', res.path)
      },
      beforeAvatarUpload(file) {
        const isJPG = file.type === 'image/jpeg'
        const isLt2M = file.size / 1024 / 1024 < 2
        return isJPG && isLt2M
      }
    }
  }
</script>


<style>
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }

  .avatar-uploader .el-upload:hover {
    border-color: #409eff;
  }

  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 148px;
    height: 148px;
    line-height: 148px;
    text-align: center;
  }

  .avatar {
    width: 148px;
    height: 148px;
    display: block;
  }

</style>
