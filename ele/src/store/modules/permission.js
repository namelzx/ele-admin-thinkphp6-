import { asyncRoutes, constantRoutes } from '@/router'
import Layout from '@/layout'

/**
 * Use meta.role to determine if the current user has permission
 * @param roles
 * @param route
 */
function hasPermission(roles, route) {
  if (route.meta && route.meta.roles) {
    return roles.some(role => route.meta.roles.includes(role))
  } else {
    return true
  }
}

/**
 * Filter asynchronous routing tables by recursion
 * @param routes asyncRoutes
 * @param roles
 */
export function filterAsyncRoutes(routes, roles) {
  const res = []

  routes.forEach(route => {
    const tmp = { ...route }
    if (hasPermission(roles, tmp)) {
      if (tmp.children) {
        tmp.children = filterAsyncRoutes(tmp.children, roles)
      }
      res.push(tmp)
    }
  })

  return res
}

const state = {
  routes: [],
  addRoutes: [],
  has: 0
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  },

  SET_HAS: (state, has) => {
    state.has = has
  }
}

const actions = {
  generateRoutes({ commit }, data) {
    return new Promise(resolve => {
      let accessedRoutes

      data.map(function(item) {
        // const item_component = item.component
        // console.log(import(`@/views/${item_component}`))
        item.component = Layout
        item.children.map(function(child) {
          const child_component = child.component
          child.component = () => import(`@/views/${child_component}`)
          if (child.children) {
            child.children.map(function(ch) {
              const ch_component = ch.component
              ch.component = () => import(`@/views/${ch_component}`)
            })
          }
          //
        })
      })
      data.push({ path: '*', redirect: '/404', hidden: true })

      commit('SET_ROUTES', data)
      resolve(data)
    })
  },
  getHas({ commit }, has) {
    commit('SET_HAS', has)

  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
