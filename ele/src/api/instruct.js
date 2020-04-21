import request from '@/utils/request'

export function GetDataByList(query) {
  return request({
    url: 'Instruct/GetDataByList',
    method: 'get',
    params: query
  })
}

export function PostDataBySave(data) {
  return request({
    url: 'Instruct/PostDataBySave',
    method: 'post',
    data
  })
}

export function GetIdByDelete(id) {
  return request({
    url: 'Instruct/GetIdByDelete',
    method: 'get',
    params: { id }
  })
}


