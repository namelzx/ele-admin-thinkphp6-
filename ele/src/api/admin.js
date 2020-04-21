import request from '@/utils/request'

export function GetDataByList(query) {
  return request({
    url: 'admin/GetDataByList',
    method: 'get',
    params: query
  })
}

export function GetIdByInfo(id) {
  return request({
    url: 'admin/GetIdByInfo',
    method: 'get',
    params: { id }
  })
}

export function save(data) {
  return request({
    url: 'admin/PostDataBySave',
    method: 'post',
    data
  })
}


export function del(id) {
  return request({
    url: 'admin/del',
    method: 'get',
    params:{id}
  })
}




