<template>
  <div class="app-container hc">
    <!-- 操作 -->
    <div  class="filter-container">
      <div class="filter-container-warp">
        <el-form :inline="true" :model="listQuery" class="form-inline">


          <div>
            <el-form-item label="">
              <el-input placeholder="请输入名称" v-model="listQuery.title" clearable>
                <template slot="prepend">名称</template>
              </el-input>
            </el-form-item>

          </div>





        </el-form>
        <div class="el-button-plain">
          <el-button  plain  size="small" @click="handleCreate">新增</el-button>
          <el-button  plain  size="small" :loading="listLoading" @click="handleFilter">搜索</el-button>

        </div>

      </div>

    </div>



    <el-table
      v-loading="listLoading"
      :data="list"
      style="width: 100%;margin-bottom: 20px;"
      border
    >
      <el-table-column label="标识" width="150px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>


      <el-table-column label="名称" min-width="100px">
        <template slot-scope="scope">
          <span class="link-type" @click="handleUpdate(scope.$index,scope.row.id)">{{ scope.row.title }}</span>
        </template>
      </el-table-column>

      <el-table-column label="图标" min-width="80px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.icon }}</span>
        </template>
      </el-table-column>
      <el-table-column label="路径" min-width="150px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.path }}</span>
        </template>
      </el-table-column>
      <el-table-column label="组件" min-width="150px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.component }}</span>
        </template>
      </el-table-column>
      <el-table-column label="状态" width="80px" align="center">
        <template slot-scope="scope">
          <span :class="{'el-icon-success text-green':scope.row.status == 1,'el-icon-error text-red':scope.row.status == 0}" @click="handleModifyStatus(scope.$index,scope.row.id,scope.row.status)">{{ scope.row.status | statusFilter }}</span>
        </template>
      </el-table-column>

      <el-table-column label="操作" align="center" width="120px" class-name="small-padding">
        <template slot-scope="scope">
          <el-button  type="text"   @click="handleRules(scope.$index,scope.row.id)">权限</el-button>
          <el-button  type="text"   @click="handleUpdate(scope.$index,scope.row.id)">编辑</el-button>
          <el-button :loading="scope.row.delete" type="text"   @click="handleDelete(scope.$index,scope.row.id)">删除</el-button>
        </template>
      </el-table-column>

    </el-table>
    <!-- 表单 -->
    <rulesForm ref="fromRules" :rule-list="list" @updateRow="updateRow"/>

    <rulesRules ref="rulesRules" :rule-list="list" @updateRow="updateRow"/>

  </div>
</template>

<script>
  import { getList, del, change, delAll, changeAll } from '@/api/rules'
  import waves from '@/directive/waves' // 水波纹指令
  import { getArrByKey } from '@/utils'
  import tree from '@/utils/tree'
  import rulesForm from './rules/form'
  import rulesRules from './rules/rules'
  import treeTable from '@/components/TreeTable'

  export default {
    name: 'Rules',
    components: { rulesForm, treeTable,rulesRules },
    directives: {
      waves
    },
    filters: {
      statusFilter(status) {
        const statusMap = {
          0: '禁用',
          1: '正常'
        }
        return statusMap[status]
      }
    },
    data() {
      return {
        loading:false,
        tableKey: 0,
        list: [],

        selectedRows: null,
        listLoading: true,
        expandAll: true,
        columns: [
          {
            text: '名称',
            value: 'title'
          }
        ],
        listQuery: {
          status: '-1',
          title: ''
        },
        buttonDisabled: true,
        deleting: false
      }
    },
    computed: {
      getRulesList() {
        return tree.listToTreeMulti(this.list, 0, 'id', 'pid', 'children', { 'delete': false })
      }
    },
    watch: {
    },
    created() {
      this.fetchList()
    },
    methods: {
      fetchList() {
        this.listLoading = true
        getList(this.listQuery).then(response => {
          this.list = response.data.data

          this.listLoading = false
        })
      },
      handleRules(index,id) {
        this.$refs.rulesRules.handleUpdate(id)
      },
      handleFilter(){
        this.fetchList()
      },
      handleFilterClear() {
        this.listQuery = {
          status: '-1',
          title: ''
        }
        this.fetchList()
      },
      handleSelectionChange(val) {
        if (val.length > 0) {
          this.buttonDisabled = false
        } else {
          this.buttonDisabled = true
        }
        this.selectedRows = val
      },
      handleCreate() {
        this.$refs.fromRules.handleCreate()
      },
      handleUpdate(index, id) {
        this.$refs.fromRules.handleUpdate(id)
      },
      handleModifyStatus(index, id, status) {
        const statusObj = { 'status': 1 - status }
        this.list = tree.upadteArr(this.list, 'id', id, statusObj)
        change(id, 'status', 1 - status).then(response => {})
      },
      updateRow(temp) {
        this.fetchList()
      },
      handleDelete(index, id) {
        const _this = this
        this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          const delObj = { 'delete': true }
          _this.list = tree.upadteArr(_this.list, 'id', id, delObj)
          del(id).then(response => {
            if (response.status === 1) {
              _this.$notify.success(response.msg)
              _this.fetchList()
            } else {
              _this.$notify.error(response.msg)
            }
            const delObj = { 'delete': false }
            _this.list = tree.upadteArr(_this.list, 'id', id, delObj)
          }).catch((error) => {
            const delObj = { 'delete': false }
            _this.list = tree.upadteArr(_this.list, 'id', id, delObj)
          })
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消删除'
          })
        })
      },
      handleDeleteAll() {
        const _this = this
        if (this.selectedRows.length > 0) {
          this.$confirm('此操作将永久删除该记录, 是否继续?', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            _this.deleting = true
            const ids = getArrByKey(_this.selectedRows, 'id')
            const idstr = ids.join(',')
            delAll({ ids: idstr }).then(response => {
              if (response.status === 1) {
                _this.$message.success(response.msg)
                _this.fetchList()
              } else {
                _this.$message.error(response.msg)
              }
              _this.deleting = false
            }).catch((error) => {
              _this.deleting = false
            })
          }).catch(() => {
            this.$message({
              type: 'info',
              message: '已取消删除'
            })
          })
        } else {
          _this.$message.error('请选择要删除的数据')
        }
      },
      handleCommand(command) {
        const _this = this
        if (this.selectedRows.length > 0) {
          const ids = getArrByKey(this.selectedRows, 'id')
          const idstr = ids.join(',')
          changeAll({ val: idstr, field: 'status', value: command }).then(response => {
            if (response.status === 1) {
              _this.$message.success(response.msg)
              _this.fetchList()
            } else {
              _this.$message.error(response.msg)
            }
          }).catch((error) => {
          })
        } else {
          _this.$message.error('请选择要操作的数据')
        }
      }
    }
  }
</script>
<style rel="stylesheet/scss" lang="scss">
  .text-red{
    color: red;
    cursor: pointer;
  }
  .text-green{
    color: green;
    cursor: pointer;
  }

</style>
