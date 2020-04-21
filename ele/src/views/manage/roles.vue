<template>
  <div class="app-container hc">
    <el-row :gutter="10">
      <el-col :span="4" v-loading="loading">
        <el-card class="card-roles">
          <div slot="header" class="clearfix">
            <el-input
              @keyup.enter.native="getRoles()"
              placeholder="请输入内容"
              v-model="title"
              clearable>
            </el-input>
          </div>
          <div v-for="(item,index) in roles" :key="index"
               :class="['text item',selected_id===item.id?'selected-active':'ac']" @click="handelRoles(item)">
            <el-link :underline="false">{{item.title}}</el-link>
          </div>
        </el-card>
      </el-col>
      <el-col :span="20" v-loading="loading">
        <el-card class="card-rules">
          <div slot="header" class="clearfix">
            <span>模块功能</span>


            <el-button :loading="loading" style="float: right;" type="primary" @click="handelSave()">保存</el-button>

          </div>
          <el-collapse v-model="activeNames">
            <!--菜单级别-->
            <el-checkbox-group
              v-model="checkList"
              :indeterminate="isIndeterminate"
              @change="handleCheckedCitiesChange"
            >

              <!--<el-collapse-item v-for="(item,index) in getPremiss" :name="item.name" >-->
              <div v-for="(item,index) in getPremiss" :name="item.name">
                <!--{{'列表内容 ' + o }}-->
                <!--{{item.title}}-->
                <span slot="title" class="collapse-title">
                <el-checkbox v-if="item.title" :label="item.id"
                             @change="handelCheck(item)">{{ item.title }}</el-checkbox>
              </span>
                <div class="roles">
                  <div v-for="(citem,cindex) in item.children" class="ch-2">
                    <el-checkbox v-if="citem.title" :label="citem.id" @change="handeltowCheck(citem)">{{ citem.title
                      }}

                    </el-checkbox>
                    <!--二级目录权限-->
                    <!--<div v-for="(citem,cindex) in item.mb">-->
                    <!--{{citem.title}}-->
                    <!--</div>-->

                    //按钮级别
                    <el-checkbox-group v-if="citem.bm" v-model="rolesList">
                      <div v-for="(oitem,oindex) in citem.bm" v-if="citem" class="o-2-or">
                        <div class="or-title">
                          <el-checkbox :label="oitem.id">{{ oitem.title }}</el-checkbox>
                        </div>
                      </div>
                    </el-checkbox-group>

                  </div>

                </div>
              </div>
            </el-checkbox-group>
          </el-collapse>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>
<script>
  import { GetDataByList, GetDataByRule, PostDataBySave } from '@/api/roles'
  import tree from '@/utils/tree'

  import { mapGetters } from 'vuex'

  export default {
    name: 'rules',
    data() {
      return {
        // rolesList: [],
        loading: false,
        title: '',
        activeNames: '',
        roles: [],
        checkList: [],//菜单权限
        isIndeterminate: false,
        rolesList: [],//按钮权限
        selected_id: 0,
        ruleList: []//菜单列表
      }
    },
    created() {
      this.getRoles()
      console.log(this.$root.Permission_Has)
    },
    computed: {
      ...mapGetters([
        'permission_has'
      ]),
      getPremiss() {
        const data = tree.listToTreeMulti(this.ruleList)
        console.log(data)
        return data
        // return tree.listToTreeMulti(this.ruleList)
      }
    },
    methods: {
      handeltowCheck(row) {
        var that = this
        setTimeout(function() {
          let index = that.checkList.indexOf(row.id)
          let pindex = that.checkList.indexOf(row.pid)

          if (index !== -1) {
            if (pindex === -1) {
              that.checkList.push(row.pid)

            }
          }
        }, 100)
      },
      handelCheck(row) {
        let index = this.checkList.indexOf(row.id)
        row.children.map(item => {
          let cindex = this.checkList.indexOf(item.id)
          if (cindex < 0) {
            this.checkList.push(item.id)
          }
        })
        if (index < 0) {
          row.children.map(item => {
            let cindex = this.checkList.indexOf(item.id)
            this.checkList.splice(cindex, 1)
          })
        }
      },
      handleCheckedCitiesChange(val) {
        console.log(val)
      },
      //选中角色
      handelRoles(row) {
        this.loading=true
        this.selected_id = row.id
        GetDataByRule(row.id).then(res => {
          this.ruleList = res.data.Rule
          let group = res.data.group
          console.log(group)
          let rule = group.rules.split(',')
          for (let i = 0; i < rule.length; i++) {
            this.checkList.push(parseInt(rule[i]))
          }

          let btn = group.btn
          if (btn === '') {
            this.rolesList = []
          } else {
            btn = btn.split(',')
            for (let i = 0; i < btn.length; i++) {
              this.rolesList.push(parseInt(btn[i]))
            }
          }

          this.loading=false
        })

      },
      //获取角色
      getRoles() {
        var temp = {
          title: this.title
        }
        this.loading = true
        GetDataByList(temp).then(res => {
          this.roles = res.data
          this.loading = false
        })
      },
      /**
       * 保存角色信息
       */
      handelSave() {
        this.loading=true
        var temp = {
          id: this.selected_id,
          checkList: this.checkList,//菜单级别
          rolesList: this.rolesList//按钮级别
        }
        PostDataBySave(temp).then(res => {
          this.loading=false
        })

      }
    }
  }
</script>
<style lang="scss" scoped>
  .card-rules, .card-roles > > > .el-card__body {
    padding: 0;
    padding-top: 5px;
    padding-bottom: 5px;
    min-height: 300px;
  }

  .card-rules > > > .el-collapse {
    line-height: 2.5;
    border-top:none;
    border-bottom:none;
  }

  .el-collapse > > > .el-checkbox__label {
    font-size: 12px !important;
  }

  .el-collapse > > > .el-checkbox__inner {
    /*width: 12px !important;*/
    /*height: 12px !important;*/
  }

  .card-roles {

    .text {

      padding-top: 5px;
      padding-bottom: 5px;
      text-align: center;
      .el-link {
        line-height: 1.5;

      }
      .el-link--inner {
        font-size: 12px !important;

      }

    }
    .ac {
      .el-link.el-link--default {
        font-size: 12px !important;
      }
    }
    .selected-active {
      background: #409EFF;
      .el-link.el-link--default {
        color: #fff !important;
        font-size: 12px !important;
      }
    }

  }

  .card-rules {

    .text {

      padding-top: 5px;
      padding-bottom: 5px;
      text-align: center;
      .el-link {
        line-height: 1.5;

      }
      .el-link--inner {
        font-size: 12px !important;

      }

    }
    .ac {
      .el-link.el-link--default {
        font-size: 12px !important;
      }
    }
    .selected-active {
      background: #409EFF;
      .el-link.el-link--default {
        color: #fff !important;
        font-size: 12px !important;
      }
    }
    .roles {
      display: flex;
      flex-direction: column;
      margin-left: 30px;
      .el-checkbox-group {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        margin-left: 30px;
        line-height: 2.5;

        .o-2-or {
          line-height: 1.5;
          margin-right: 5px;
          font-size: 12px;
          display: flex;
          flex-direction: row;
          .el-checkbox {
            .el-checkbox__label {
              font-size: 12px !important;
            }
          }
        }
      }
    }

  }
</style>
