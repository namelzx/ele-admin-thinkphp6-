import request from '@/utils/request'

//角色
export function GetDataByList(query) {
  return request({
    url: 'roles/GetDataByList',
    method: 'get',
    params: query
  })
}

export function GetRoleBylist(query) {
  return request({
    url: 'roles/GetDataByList',
    method: 'get',
    params: query
  })
}



export function GetDataByRule(id) {
  return request({
    url: 'roles/GetDataByRule',
    method: 'get',
    params: { id }
  })
}

export function PostDataBySave(data) {
  return request({
    url: 'roles/PostDataBySave',
    method: 'post',
    data
  })
}

