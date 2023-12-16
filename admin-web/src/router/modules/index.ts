import { RouteRecordRaw } from 'vue-router'
import asyncRoutes from './async'
import baseRoutes from './base'

/** 常驻路由 */
export const constantRoutes: RouteRecordRaw[] = [
    ...asyncRoutes,
    ...baseRoutes
]