import request from '@/utils/request'

export function getList(query) {
  return request({
    url: 'rules/index',
    method: 'post',
    data: query
  })
}

export function getListAll() {
  return request({
    url: 'rules/getLists',
    method: 'post'
  })
}

export function getinfo(id) {
  return request({
    url: 'rules/getinfo',
    method: 'get',
    params: { id }
  })
}

export function save(data) {
  return request({
    url: 'rules/save',
    method: 'post',
    data
  })
}

export function del(id) {
  return request({
    url: 'rules/del',
    method: 'get',
    params: { id }
  })
}

export function change(id, field, value) {
  const data = {
    val: id,
    field: field,
    value: value
  }
  return request({
    url: 'rules/change',
    method: 'post',
    data
  })
}

export function delAll(data) {
  return request({
    url: 'rules/delall',
    method: 'post',
    data
  })
}

export function changeAll(data) {
  return request({
    url: 'rules/changeall',
    method: 'post',
    data
  })
}
