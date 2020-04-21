import request from '@/utils/request'

export function GetDataByList(query) {
  return request({
    url: 'oss/GetDataByList',
    method: 'get',
    params: query
  })
}

export function PostDataBySave(data) {
  return request({
    url: 'oss/PostDataBySave',
    method: 'post',
    data
  })
}
