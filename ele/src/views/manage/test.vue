<template>
  <div class="app-container">
    <upload-excel-component :on-success="handleSuccess" :before-upload="beforeUpload"/>

    <upload-excel-component :on-success="handleBuild" :before-upload="beforeUpload"/>
    <!--<el-table :data="tableData" border highlight-current-row style="width: 100%;margin-top:20px;">-->
    <!--<el-table-column v-for="item of tableHeader" :key="item" :prop="item" :label="item"/>-->
    <!--</el-table>-->

    <el-cascader :props="props"></el-cascader>

  </div>
</template>

<script>
  import UploadExcelComponent from '@/components/UploadExcel/index.vue'
  import { PostDataByCityAll, PostDataByBuildAll } from '@/api/shop'

  import { getBrand, getSeries ,getModel} from '@/api/tools'

  let id = 0
  export default {
    name: 'UploadExcel',
    components: { UploadExcelComponent },
    data() {
      return {
        tableData: [],
        tableHeader: [],
        props: {
          lazy: true,
          lazyLoad(node, resolve) {
            const { level } = node

            if (level === 0) {
                getBrand().then(res => {

                  var data = res.data

                  let nodes = []
                  for (let i = 0; i < data.length; i++) {
                    nodes.push({ label: data[i].name, value: data[i].brandid })
                  }
                  resolve(nodes)

                })

                // 通过调用resolve将子节点数据返回，通知组件数据加载完成
            }
            if (level === 1) {
                getSeries(node.data.value).then(res => {
                  var data = res.data
                  let nodes = []
                  for (let i = 0; i < data.length; i++) {
                    nodes.push({ label: data[i].factory_name+' '+data[i].series_name, value: data[i].series_id })
                  }
                  resolve(nodes)

                })

                // 通过调用resolve将子节点数据返回，通知组件数据加载完成
            }


            if (level === 2) {
                getModel(node.data.value).then(res => {
                  var data = res.data
                  let nodes = []
                  for (let i = 0; i < data.length; i++) {
                    nodes.push({ label: data[i].label, value: data[i].value ,leaf: level >= 2})
                  }
                  resolve(nodes)

                })

                // 通过调用resolve将子节点数据返回，通知组件数据加载完成
            }
            if(level===3){
              let nodes=[];
              resolve(nodes)
            }
          }
        }
      }
    },
    methods: {
      beforeUpload(file) {
        const isLt1M = file.size / 1024 / 1024 < 1

        if (isLt1M) {
          return true
        }

        this.$message({
          message: 'Please do not upload files larger than 1m in size.',
          type: 'warning'
        })
        return false
      },
      handleSuccess({ results, header }) {
        this.tableData = results
        PostDataByCityAll(this.tableData).then(res => {
          console.log('加载完成')
        })
        this.tableHeader = header
      },

      handleBuild({ results, header }) {
        this.tableData = results
        // console.log(this.tableData )
        PostDataByBuildAll(this.tableData).then(res => {
          console.log('加载完成')
        })
        //   this.tableHeader = header
      }
    }
  }
</script>


<!--<template>-->
<!--<div>-->
<!--<el-table-->
<!--ref="multipleTable"-->
<!--:data="tableData"-->
<!--tooltip-effect="dark"-->
<!--style="width: 100%"-->
<!--@selection-change="handleSelectionChange">-->
<!--<el-table-column-->
<!--type="selection"-->
<!--width="55">-->
<!--</el-table-column>-->
<!--<el-table-column-->
<!--label="日期"-->
<!--width="120">-->
<!--<template slot-scope="scope">{{ scope.row.date }}</template>-->
<!--</el-table-column>-->
<!--<el-table-column-->
<!--prop="name"-->
<!--label="姓名"-->
<!--width="120">-->
<!--</el-table-column>-->
<!--<el-table-column-->
<!--prop="address"-->
<!--label="地址"-->
<!--show-overflow-tooltip>-->
<!--</el-table-column>-->
<!--</el-table>-->
<!--<div style="margin-top: 20px">-->
<!--<el-button @click="toggleSelection([tableData[1], tableData[2]])">切换第二、第三行的选中状态</el-button>-->
<!--<el-button @click="toggleSelection()">取消选择</el-button>-->
<!--</div>-->
<!--</div>-->
<!--</template>-->

<!--<script>-->
<!--export default {-->
<!--data() {-->
<!--return {-->
<!--tableData: [{-->
<!--date: '2016-05-03',-->
<!--name: '王小虎',-->
<!--address: '上海市普陀区金沙江路 1518 弄'-->
<!--}, {-->
<!--date: '2016-05-02',-->
<!--name: '王小虎',-->
<!--address: '上海市普陀区金沙江路 1518 弄'-->
<!--}, {-->
<!--date: '2016-05-04',-->
<!--name: '王小虎',-->
<!--address: '上海市普陀区金沙江路 1518 弄'-->
<!--}, {-->
<!--date: '2016-05-01',-->
<!--name: '王小虎',-->
<!--address: '上海市普陀区金沙江路 1518 弄'-->
<!--}, {-->
<!--date: '2016-05-08',-->
<!--name: '王小虎',-->
<!--address: '上海市普陀区金沙江路 1518 弄'-->
<!--}, {-->
<!--date: '2016-05-06',-->
<!--name: '王小虎',-->
<!--address: '上海市普陀区金沙江路 1518 弄'-->
<!--}, {-->
<!--date: '2016-05-07',-->
<!--name: '王小虎',-->
<!--address: '上海市普陀区金沙江路 1518 弄'-->
<!--}],-->
<!--multipleSelection: []-->
<!--}-->
<!--},-->

<!--methods: {-->
<!--toggleSelection(rows) {-->
<!--if (rows) {-->
<!--rows.forEach(row => {-->
<!--console.log(row)-->
<!--this.$refs.multipleTable.toggleRowSelection(row);-->
<!--});-->
<!--} else {-->
<!--this.$refs.multipleTable.clearSelection();-->
<!--}-->
<!--},-->
<!--handleSelectionChange(val) {-->
<!--this.multipleSelection = val;-->
<!--}-->
<!--}-->
<!--}-->
<!--</script>-->
