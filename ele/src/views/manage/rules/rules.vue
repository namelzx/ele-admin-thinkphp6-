<template>
  <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
    <el-dialog
      width="30%"
      title="增加指令"
      :visible.sync="innerVisible"
      append-to-body>
      <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="70px"
               style="width: 100%; overflow-y: scroll;">
        <el-form-item label="名称" prop="title">
          <el-input v-model="temp.title" clearable/>
        </el-form-item>
        <el-form-item label="类型" prop="type">
          <el-select v-model="temp.type" size="mini" placeholder="请选择">
            <el-option
              v-for="item in hasop"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="innerVisible = false">取消</el-button>
        <el-button :loading="btnLoading" type="primary" @click="saveData()">保存</el-button>
      </div>

    </el-dialog>
    <div class=" vux-flex-row">
      <div>
        <el-input placeholder="请输入内容" v-model="listQuery.title" class="input-with-select">
          <el-button @click="getlist()" slot="append" icon="el-icon-search"></el-button>
        </el-input>
      </div>
      <el-button type="primary" size="mini" @click="innerVisible=true">新建</el-button>
    </div>
    <el-table
      v-loading="btnLoading"
      :data="list"
      stripe
      style="width: 100%">
      <el-table-column
        prop="title"
        label="权限名称"
        width="180">
      </el-table-column>
      <el-table-column
        label="作用"
        width="180">
        <template slot-scope="scope">
          <el-tag> {{scope.row.type}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column
        prop="address"
        label="操作">
        <template slot-scope="scope">
          <el-button type="text" @click="handleDelete(scope.$index,scope.row.id)">删除</el-button>

        </template>
      </el-table-column>
    </el-table>
    <div slot="footer" class="dialog-footer">
      <el-button @click="dialogFormVisible = false">取消</el-button>
      <el-button :loading="btnLoading" type="primary" @click="saveData()">保存</el-button>
    </div>
  </el-dialog>
</template>

<script>
  // import { getinfo, save } from '@/api/rules'
  import { GetDataByList, PostDataBySave, GetIdByDelete } from '@/api/instruct'
  import tree from '@/utils/tree'

  export default {
    name: 'RulesForm',
    components: {},
    props: {
      ruleList: {
        type: Array,
        default: []
      }
    },
    data() {
      return {
        innerVisible: false,
        listQuery: {
          page: 1,
          limit: 20
        },
        hasop: [
          {
            label: '搜索',
            value: 1
          },
          {
            label: '删除',
            value: 2
          },
          {
            label: '修改',
            value: 3
          },
          {
            label: '禁用',
            value: 4
          },
          {
            label: '导出',
            value: 5
          }

        ],
        list: [],
        btnLoading: false,
        ruleTop: [{ 'id': 0, 'title': '顶级' }],
        pid: [],

        props_pid: { 'label': 'title', 'value': 'id' },
        temp: {
          id: 0,
          pid: 0
        },
        rule_id: 0,
        dialogFormVisible: false,
        dialogStatus: '',
        textMap: {
          update: '编辑',
          create: '添加'
        },
        rules: {
          title: [{ required: true, message: '名称必填', trigger: 'blur' }],
          type: [{ required: true, message: '标识必填', trigger: 'blur' }]
        }
      }
    },
    computed: {
      getRulesList() {
        return this.ruleTop.concat(tree.listToTreeMulti(this.ruleList))
      }
    },
    watch: {
      dialogFormVisible: function() {
        this.resetTemp()
      },
      temp: {
        handler(newVal, oldVal) {
        },
        immediate: true,
        deep: true
      }
    },
    created() {

    },
    destroyed() {

    },
    methods: {
      handleDelete(index, id) {
        this.list.splice(index, 1)
        GetIdByDelete(id).then(res => {
          this.$message.success('删除成功')
        })
      },
      getlist() {
        this.btnLoading = true
        this.listQuery.rule_id = this.rule_id
        GetDataByList(this.listQuery).then(res => {
          this.list = res.data.data
          this.btnLoading = false
        })
      },
      resetTemp() {
        this.temp = {
          rule_id: 0,
          title: '',
          type: 1
        }
      },
      handleCreate() {
        this.dialogStatus = 'create'
        this.dialogFormVisible = true
        this.currentIndex = -1
        this.pid = []
        this.$nextTick(() => {
          this.$refs['dataForm'].clearValidate()
        })
      },
      handleRules(e) {

      },
      handleUpdate(id) {
        this.dialogStatus = 'update'
        this.dialogFormVisible = true
        const _this = this
        this.rule_id = id
        this.getlist()
      },
      saveData() {
        this.$refs['dataForm'].validate((valid) => {
          if (valid) {
            const _this = this
            this.temp.rule_id=this.rule_id
            const d = this.temp
            PostDataBySave(d).then(res => {
              console.log(res)
              // if(res.data.)
              this.$message.success('添加成功');
              _this.innerVisible = false
              _this.btnLoading = false
              this.getlist()
              this.resetTemp()
            })
          } else {
            this.btnLoading = false
          }
        })
      },
      handleChange(value) {
        if (value.length) {
          this.temp.pid = value[value.length - 1]
        }
      }
    }
  }
</script>

